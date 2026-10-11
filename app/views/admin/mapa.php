<?php
/**
 * View: admin/mapa.php
 * Mapa Visual de Ocorrências – Painel de Administração Djumbai
 * Em ecrã total genuíno (100vw x 100vh) com as cores oficiais do sistema Djumbai.
 */
?>

<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="admin-map-viewport">
  
  <!-- Barra Superior Oficial do Mapa (Cores e Botões Padrão Djumbai) -->
  <header class="admin-map-topbar">
    <div class="admin-map-topbar__left">
      <a href="<?= url('admin') ?>" class="btn btn--ghost btn--sm" style="display:inline-flex;align-items:center;gap:6px;">
        ← Voltar ao Painel
      </a>
      <div class="topbar-divider"></div>
      <div>
        <h1 class="topbar-title">Mapa de Ocorrências</h1>
        <p class="topbar-subtitle">Visualização geográfica dos problemas comunitários em Angola</p>
      </div>
    </div>

    <div class="admin-map-topbar__right">
      <div class="mapa-stats-pills">
        <div class="mapa-stat-pill">
          <span class="mapa-stat-pill__label">Total:</span>
          <strong id="stat-total-pontos"><?= count($pontos) ?></strong>
        </div>
        <div class="mapa-stat-pill mapa-stat-pill--critico">
          <span class="mapa-stat-pill__dot"></span>
          <span class="mapa-stat-pill__label">Críticos:</span>
          <strong id="stat-pontos-criticos">
            <?php
              $criticos = array_filter($pontos, fn($p) => $p['total_confirmacoes'] >= 3 || $p['estado_id'] == 1);
              echo count($criticos);
            ?>
          </strong>
        </div>
      </div>
      <a href="<?= url('admin/problemas') ?>" class="btn btn--primary btn--sm" style="display:inline-flex;align-items:center;gap:6px;">
        <?= icon('list', 16, '', '#FFF') ?> Gerir Ocorrências
      </a>
    </div>
  </header>

  <!-- Barra de Filtros Integrada (Fundo de Cartão Padrão) -->
  <div class="admin-map-filterbar">
    <div class="mapa-filters">
      <!-- Filtro por Província -->
      <div class="filter-group">
        <label for="filtro-provincia" class="filter-label">Província:</label>
        <select id="filtro-provincia" class="form-select form-select--sm">
          <option value="0">Todas as Províncias</option>
          <?php foreach ($provincias as $prov): ?>
            <option value="<?= (int) $prov['id'] ?>" <?= ($filtros['provincia'] == $prov['id']) ? 'selected' : '' ?>>
              <?= e($prov['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por Município -->
      <div class="filter-group">
        <label for="filtro-municipio" class="filter-label">Município:</label>
        <select id="filtro-municipio" class="form-select form-select--sm">
          <option value="0">Todos os Municípios</option>
          <?php foreach ($municipios as $mun): ?>
            <option value="<?= (int) $mun['id'] ?>" data-provincia="<?= (int) $mun['provincia_id'] ?>" <?= ($filtros['municipio'] == $mun['id']) ? 'selected' : '' ?>>
              <?= e($mun['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por Categoria -->
      <div class="filter-group">
        <label for="filtro-categoria" class="filter-label">Categoria:</label>
        <select id="filtro-categoria" class="form-select form-select--sm">
          <option value="0">Todas as Categorias</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= (int) $cat['id'] ?>" <?= ($filtros['categoria'] == $cat['id']) ? 'selected' : '' ?>>
              <?= e($cat['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por Estado -->
      <div class="filter-group">
        <label for="filtro-estado" class="filter-label">Estado:</label>
        <select id="filtro-estado" class="form-select form-select--sm">
          <option value="0">Todos os Estados</option>
          <?php foreach ($estados as $est): ?>
            <option value="<?= (int) $est['id'] ?>" <?= ($filtros['estado'] == $est['id']) ? 'selected' : '' ?>>
              <?= e($est['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Botão Reset -->
      <button id="btn-reset-mapa" class="btn btn--ghost btn--xs" title="Restaurar visualização inicial">
        <?= icon('refresh', 14, '', 'currentColor') ?> Reset
      </button>
    </div>
  </div>

  <!-- Área Principal (Mapa a 100% + Sidebar Lateral com Estilos Oficiais) -->
  <div class="admin-map-main-area">
    
    <!-- Contentor do Leaflet -->
    <div class="admin-map-container-wrap">
      <div id="djumbai-leaflet-map" class="djumbai-map-container"></div>

      <!-- Legenda Flutuante -->
      <div class="mapa-legend">
        <div class="mapa-legend__title">Legenda de Estados</div>
        <div class="mapa-legend__grid">
          <span class="mapa-legend__item"><i style="background:#D99A1E;"></i> Pendente</span>
          <span class="mapa-legend__item"><i style="background:#1F6F6B;"></i> Em análise</span>
          <span class="mapa-legend__item"><i style="background:#3F5A3C;"></i> Resolvido</span>
          <span class="mapa-legend__item"><i style="background:#B4451F;"></i> Rejeitado</span>
          <span class="mapa-legend__item"><i style="background:#E63946;border-radius:50%;box-shadow:0 0 8px rgba(230,57,70,.8);"></i> Ponto Crítico</span>
        </div>
      </div>
    </div>

    <!-- Sidebar de Ocorrências no Padrão de Superfície Clara do Sistema -->
    <aside class="admin-map-sidebar">
      <div class="admin-map-sidebar__header">
        <h3>Ocorrências em Foco</h3>
        <span id="sidebar-count-badge" class="badge badge--baia"><?= count($pontos) ?></span>
      </div>

      <div id="mapa-sidebar-list" class="admin-map-sidebar__list">
        <?php if (empty($pontos)): ?>
          <div class="mapa-empty-state">
            <?= icon('alert-circle', 24, '', 'var(--text-muted)') ?>
            <p>Nenhuma ocorrência encontrada com os filtros selecionados.</p>
          </div>
        <?php else: ?>
          <?php foreach ($pontos as $p): ?>
            <article class="mapa-card-item" data-id="<?= (int) $p['id'] ?>" data-lat="<?= (float) $p['latitude'] ?>" data-lng="<?= (float) $p['longitude'] ?>">
              <div class="mapa-card-item__top">
                <span class="badge" style="background:<?= e($p['estado_cor']) ?>20;color:<?= e($p['estado_cor']) ?>;">
                  <span class="badge__dot" style="background:<?= e($p['estado_cor']) ?>;"></span>
                  <?= e($p['estado_nome']) ?>
                </span>
                <?php if ($p['total_confirmacoes'] >= 3): ?>
                  <span class="badge badge--critico" title="Elevada confirmação comunitária">
                    🔥 Crítico
                  </span>
                <?php endif; ?>
              </div>

              <h4 class="mapa-card-item__title">
                <a href="<?= url('admin/problemas') ?>" onclick="event.stopPropagation();"><?= e($p['titulo']) ?></a>
              </h4>

              <div class="mapa-card-item__meta">
                <span><?= icon('map-pin', 12, '', 'var(--brand-terra)') ?> <?= e($p['bairro_nome']) ?><?= $p['municipio_nome'] ? ', ' . e($p['municipio_nome']) : '' ?></span>
                <span>•</span>
                <span><strong><?= (int) $p['total_confirmacoes'] ?></strong> confirmações</span>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </aside>

  </div>
</div>

<!-- Estilos do Mapa em Ecrã Total com as Cores Oficiais do Sistema -->
<style>
.admin-map-viewport {
  display: flex;
  flex-direction: column;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: var(--bg-warm, #F5EFE3);
}

.admin-map-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background: var(--surface-card, #FFFDF9);
  border-bottom: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  flex-shrink: 0;
  z-index: 1001;
  gap: 16px;
  flex-wrap: wrap;
}

.admin-map-topbar__left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.topbar-divider {
  width: 1px;
  height: 24px;
  background: var(--border-subtle, rgba(43, 29, 20, 0.1));
}

.topbar-title {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--text-heading, #231710);
  margin: 0;
  line-height: 1.2;
}

.topbar-subtitle {
  font-size: 11.5px;
  color: var(--text-muted, #8C7C6E);
  margin: 0;
}

.admin-map-topbar__right {
  display: flex;
  align-items: center;
  gap: 14px;
}

.admin-map-filterbar {
  background: var(--surface-card, #FFFDF9);
  border-bottom: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  padding: 8px 20px;
  display: flex;
  align-items: center;
  flex-shrink: 0;
  z-index: 1000;
  box-shadow: 0 2px 8px rgba(43, 29, 20, 0.03);
}

.mapa-filters {
  display: flex;
  align-items: center;
  gap: var(--space-3, 12px);
  flex-wrap: wrap;
  flex: 1;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 6px;
}

.filter-label {
  font-size: 11px;
  font-weight: 700;
  color: var(--text-muted, #8C7C6E);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.mapa-stats-pills {
  display: flex;
  align-items: center;
  gap: 10px;
}

.mapa-stat-pill {
  background: var(--bg-warm, #F5EFE3);
  border: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 11.5px;
  display: flex;
  align-items: center;
  gap: 6px;
  color: var(--text-main, #231710);
}

.mapa-stat-pill--critico {
  background: #FFF5F5;
  border-color: rgba(230, 57, 70, 0.2);
  color: #C53030;
}

.mapa-stat-pill__dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #E63946;
  box-shadow: 0 0 6px rgba(230, 57, 70, 0.8);
}

.admin-map-main-area {
  display: flex;
  flex: 1;
  overflow: hidden;
  position: relative;
  height: calc(100vh - 110px);
}

.admin-map-container-wrap {
  flex: 1;
  position: relative;
  height: 100%;
  width: 100%;
  background: #E5E3DF;
}

.djumbai-map-container {
  width: 100%;
  height: 100%;
  z-index: 1;
}

.admin-map-sidebar {
  width: 340px;
  background: var(--surface-card, #FFFDF9);
  border-left: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  display: flex;
  flex-direction: column;
  height: 100%;
  flex-shrink: 0;
  z-index: 1000;
  box-shadow: -4px 0 16px rgba(43, 29, 20, 0.04);
}

.admin-map-sidebar__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  background: var(--surface-card, #FFFDF9);
}

.admin-map-sidebar__header h3 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--text-heading, #231710);
  margin: 0;
}

.admin-map-sidebar__list {
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 12px;
  flex: 1;
}

.mapa-card-item {
  background: var(--bg-warm, #F5EFE3);
  border: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  border-radius: 8px;
  padding: 10px 12px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.mapa-card-item:hover,
.mapa-card-item.is-active {
  border-color: var(--brand-terra, #B4451F);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(180, 69, 31, 0.12);
}

.mapa-card-item__top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}

.badge--critico {
  background: rgba(230, 57, 70, 0.15);
  color: #C53030;
  border: 1px solid rgba(230, 57, 70, 0.3);
  font-size: 9.5px;
}

.mapa-card-item__title {
  font-size: 0.92rem;
  font-weight: 700;
  line-height: 1.35;
  margin-bottom: 5px;
}

.mapa-card-item__title a {
  color: var(--text-heading, #231710);
  text-decoration: none;
}

.mapa-card-item__title a:hover {
  color: var(--brand-terra, #B4451F);
}

.mapa-card-item__meta {
  font-size: 11px;
  color: var(--text-muted, #8C7C6E);
  display: flex;
  align-items: center;
  gap: 6px;
}

.mapa-empty-state {
  text-align: center;
  padding: 30px 15px;
  color: var(--text-muted, #8C7C6E);
}

.mapa-legend {
  position: absolute;
  bottom: 20px;
  left: 20px;
  z-index: 1000;
  background: rgba(255, 251, 242, 0.95);
  backdrop-filter: blur(8px);
  border: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  border-radius: 8px;
  padding: 8px 14px;
  box-shadow: 0 4px 14px rgba(43, 29, 20, 0.1);
}

.mapa-legend__title {
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  color: var(--text-muted, #8C7C6E);
  margin-bottom: 4px;
}

.mapa-legend__grid {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.mapa-legend__item {
  font-size: 11px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 5px;
  color: var(--text-main, #231710);
}

.mapa-legend__item i {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  display: inline-block;
}

/* Pins Oficiais do Djumbai */
.djumbai-map-pin {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  color: #FFF;
  font-weight: bold;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  border: 2px solid #FFF;
  cursor: pointer;
  transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.djumbai-map-pin:hover {
  transform: scale(1.25);
  z-index: 9999 !important;
}

.djumbai-map-pin--pulse {
  animation: mapPinPulse 1.8s infinite;
}

@keyframes mapPinPulse {
  0% { box-shadow: 0 0 0 0 rgba(230, 57, 70, 0.7); }
  70% { box-shadow: 0 0 0 14px rgba(230, 57, 70, 0); }
  100% { box-shadow: 0 0 0 0 rgba(230, 57, 70, 0); }
}

/* Leaflet Popup com Cores Padrão */
.leaflet-popup-content-wrapper {
  background: var(--surface-card, #FFFDF9);
  border-radius: 12px;
  padding: 0;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(43, 29, 20, 0.16);
  border: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
}

.leaflet-popup-content {
  margin: 0;
  width: 260px !important;
}

.mapa-popup {
  padding: 14px;
}

.mapa-popup__tag {
  display: inline-block;
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  padding: 2px 8px;
  border-radius: 4px;
  margin-bottom: 6px;
}

.mapa-popup__title {
  font-size: 0.95rem;
  font-weight: 800;
  margin-bottom: 6px;
  line-height: 1.3;
}

.mapa-popup__title a {
  color: var(--text-heading, #231710);
  text-decoration: none;
}

.mapa-popup__meta {
  font-size: 11px;
  color: var(--text-muted, #8C7C6E);
  margin-bottom: 10px;
}

.mapa-popup__btn {
  display: block;
  width: 100%;
  text-align: center;
  background: var(--brand-terra, #B4451F);
  color: #FFF;
  font-size: 12px;
  font-weight: 700;
  padding: 8px 12px;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.2s ease;
}

.mapa-popup__btn:hover {
  background: #8F3517;
  color: #FFF;
}
</style>

<!-- Script Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const defaultLat = -8.838333;
  const defaultLng = 13.234444;
  const defaultZoom = 12;

  // 1. Inicializa o Mapa Leaflet
  const mapElement = document.getElementById('djumbai-leaflet-map');
  if (!mapElement) return;

  const map = L.map('djumbai-leaflet-map', {
    center: [defaultLat, defaultLng],
    zoom: defaultZoom,
    zoomControl: true,
  });

  setTimeout(() => {
    map.invalidateSize();
  }, 200);

  // Camada Tile do OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> · Djumbai Angola',
  }).addTo(map);

  let markersLayer = L.layerGroup().addTo(map);
  let currentPontos = [];

  // 2. Carrega pontos da API / Dados Iniciais
  const initialPontos = <?= json_encode(array_map(function($p) {
    return [
      'id'                 => (int) $p['id'],
      'titulo'             => $p['titulo'],
      'descricao'          => $p['descricao'],
      'foto'               => $p['foto'] ? url('assets/images/uploads/' . $p['foto']) : null,
      'referencia_local'   => $p['referencia_local'],
      'criado_em'          => timeAgo($p['criado_em']),
      'categoria'          => $p['categoria_nome'],
      'estado'             => $p['estado_nome'],
      'estado_cor'         => $p['estado_cor'],
      'bairro'             => $p['bairro_nome'],
      'municipio'          => $p['municipio_nome'],
      'provincia'          => $p['provincia_nome'],
      'total_confirmacoes' => (int) $p['total_confirmacoes'],
      'lat'                => (float) $p['latitude'],
      'lng'                => (float) $p['longitude'],
      'url'                => url('admin/problemas'),
    ];
  }, $pontos), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

  function renderMapMarkers(pontos) {
    markersLayer.clearLayers();
    currentPontos = pontos;

    const bounds = [];

    pontos.forEach(p => {
      const isCritico = p.total_confirmacoes >= 3 || p.estado_cor === '#D99A1E';
      const pulseClass = isCritico ? 'djumbai-map-pin--pulse' : '';

      // Ícone SVG Personalizado com as Cores de Angola / Djumbai
      const customIcon = L.divIcon({
        className: 'custom-leaflet-marker',
        html: `
          <div class="djumbai-map-pin ${pulseClass}" style="background:${p.estado_cor};border-color:${isCritico ? '#E63946' : '#FFF'};">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
        `,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -30]
      });

      const marker = L.marker([p.lat, p.lng], { icon: customIcon });

      // Popup Interativo com as Cores Oficiais
      const popupHtml = `
        <div class="mapa-popup">
          <span class="mapa-popup__tag" style="background:${p.estado_cor}20;color:${p.estado_cor};">
            ${p.estado}
          </span>
          ${isCritico ? '<span class="badge badge--critico" style="font-size:9px;margin-left:4px;">🔥 Crítico</span>' : ''}
          <h4 class="mapa-popup__title">
            <a href="${p.url}">${p.titulo}</a>
          </h4>
          <div class="mapa-popup__meta">
            📍 <strong>${p.bairro}</strong>${p.municipio ? ', ' + p.municipio : ''}<br>
            💬 ${p.total_confirmacoes} confirmações comunitárias
          </div>
          <a href="${p.url}" class="mapa-popup__btn">Gerir Ocorrência →</a>
        </div>
      `;

      marker.bindPopup(popupHtml);
      markersLayer.addLayer(marker);

      bounds.push([p.lat, p.lng]);
    });

    if (bounds.length > 0) {
      if (bounds.length === 1) {
        map.setView(bounds[0], 14);
      } else {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 });
      }
    }
  }

  // Renderiza Inicialmente
  renderMapMarkers(initialPontos);

  // 3. Atualização via Filtros AJAX sem Recarregar a Página
  const provSelect = document.getElementById('filtro-provincia');
  const munSelect  = document.getElementById('filtro-municipio');
  const catSelect  = document.getElementById('filtro-categoria');
  const estSelect  = document.getElementById('filtro-estado');
  const btnReset   = document.getElementById('btn-reset-mapa');

  function filtrarPontos() {
    const provId = provSelect ? provSelect.value : 0;
    const munId  = munSelect  ? munSelect.value  : 0;
    const catId  = catSelect  ? catSelect.value  : 0;
    const estId  = estSelect  ? estSelect.value  : 0;

    if (munSelect) {
      Array.from(munSelect.options).forEach(opt => {
        if (opt.value === "0") return;
        const provAttr = opt.getAttribute('data-provincia');
        if (provId === "0" || provAttr === provId) {
          opt.style.display = 'block';
        } else {
          opt.style.display = 'none';
        }
      });
    }

    const queryParams = new URLSearchParams({
      provincia: provId,
      municipio: munId,
      categoria: catId,
      estado:    estId
    });

    fetch('<?= url("api/mapa-ocorrencias") ?>?' + queryParams.toString())
      .then(r => r.json())
      .then(data => {
        if (data.sucesso) {
          const pontosFormatados = data.pontos.map(p => ({
            ...p,
            url: '<?= url("admin/problemas") ?>'
          }));
          renderMapMarkers(pontosFormatados);
          updateSidebarAndStats(pontosFormatados);
        }
      })
      .catch(err => console.error('Erro ao atualizar mapa:', err));
  }

  function updateSidebarAndStats(pontos) {
    document.getElementById('stat-total-pontos').textContent = pontos.length;
    const criticos = pontos.filter(p => p.total_confirmacoes >= 3 || p.estado === 'Pendente');
    document.getElementById('stat-pontos-criticos').textContent = criticos.length;
    document.getElementById('sidebar-count-badge').textContent = pontos.length;

    const listContainer = document.getElementById('mapa-sidebar-list');
    if (!listContainer) return;

    if (pontos.length === 0) {
      listContainer.innerHTML = `
        <div class="mapa-empty-state">
          <p>Nenhuma ocorrência encontrada com os filtros selecionados.</p>
        </div>
      `;
      return;
    }

    listContainer.innerHTML = pontos.map(p => `
      <article class="mapa-card-item" data-id="${p.id}" data-lat="${p.lat}" data-lng="${p.lng}">
        <div class="mapa-card-item__top">
          <span class="badge" style="background:${p.estado_cor}20;color:${p.estado_cor};">
            <span class="badge__dot" style="background:${p.estado_cor};"></span>
            ${p.estado}
          </span>
          ${p.total_confirmacoes >= 3 ? '<span class="badge badge--critico">🔥 Crítico</span>' : ''}
        </div>
        <h4 class="mapa-card-item__title">
          <a href="${p.url}" onclick="event.stopPropagation();">${p.titulo}</a>
        </h4>
        <div class="mapa-card-item__meta">
          <span>📍 ${p.bairro}${p.municipio ? ', ' + p.municipio : ''}</span>
          <span>•</span>
          <span><strong>${p.total_confirmacoes}</strong> confirmações</span>
        </div>
      </article>
    `).join('');

    bindSidebarCardClicks();
  }

  function bindSidebarCardClicks() {
    document.querySelectorAll('.mapa-card-item').forEach(card => {
      card.addEventListener('click', function () {
        const lat = parseFloat(this.getAttribute('data-lat'));
        const lng = parseFloat(this.getAttribute('data-lng'));
        if (!isNaN(lat) && !isNaN(lng)) {
          map.flyTo([lat, lng], 15, { duration: 1.2 });
        }
      });
    });
  }

  bindSidebarCardClicks();

  if (provSelect) provSelect.addEventListener('change', filtrarPontos);
  if (munSelect)  munSelect.addEventListener('change',  filtrarPontos);
  if (catSelect)  catSelect.addEventListener('change',  filtrarPontos);
  if (estSelect)  estSelect.addEventListener('change',  filtrarPontos);

  if (btnReset) {
    btnReset.addEventListener('click', function () {
      if (provSelect) provSelect.value = '0';
      if (munSelect)  munSelect.value  = '0';
      if (catSelect)  catSelect.value  = '0';
      if (estSelect)  estSelect.value  = '0';
      filtrarPontos();
      map.setView([defaultLat, defaultLng], defaultZoom);
    });
  }
});
</script>
