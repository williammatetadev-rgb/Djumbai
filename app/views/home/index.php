<?php
/**
 * View: home/index.php
 * Homepage do Djumbai — Panorama + Destaque
 */

// Atalhos para as estatísticas
$pendentes  = (int) ($panorama['pendentes']  ?? 0);
$emAnalise  = (int) ($panorama['em_analise'] ?? 0);
$resolvidos = (int) ($panorama['resolvidos'] ?? 0);
$rejeitados = (int) ($panorama['rejeitados'] ?? 0);
?>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="hero__grid">
      <div class="hero__content">

        <div class="pulse-pill">
          <span class="pulse-pill__dot"></span>
          <span>AO VIVO · <?= e(date('Y')) ?> · Angola</span>
        </div>

        <h1 class="hero__title">
          Os bairros de Angola<br>
          melhoram quando <em>falamos juntos.</em>
        </h1>

        <p class="hero__subtitle">
          Djumbai é a plataforma cívica onde os moradores registam problemas comunitários — buracos nas estradas, cortes de água, lixo — e acompanham a solução em tempo real.
        </p>

        <div class="hero__ctas">
          <a href="<?= url('reportar') ?>" class="btn btn--primary btn--lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Reportar um Problema
          </a>
          <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--lg">
            Ver Reportes Activos →
          </a>
        </div>

        <div class="hero__proof">
          <div class="hero__avatars" aria-hidden="true">
            <span class="hero__avatar" style="background:var(--brand-terra)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
            </span>
            <span class="hero__avatar" style="background:var(--brand-baia)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
            </span>
            <span class="hero__avatar" style="background:var(--brand-capim)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
            </span>
          </div>
          <div class="hero__proof-text">
            <strong><?= number_format($panorama['total'] ?? 0) ?>+ Reportes submetidos</strong>
            <span>pela comunidade angolana</span>
          </div>
        </div>
      </div>

      <!-- Stats Card Visual (lado direito) -->
      <div class="hero__visual" aria-hidden="true">
        <div class="hero-widget">
          <div class="hero-widget__bar">
            <div class="hero-widget__window-dots"><span></span><span></span><span></span></div>
            <span class="hero-widget__title">Panorama da Plataforma</span>
            <div style="width:44px"></div>
          </div>
          <div class="hero-widget__body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
              <div style="background:rgba(255,251,245,.07);border:1px solid rgba(255,251,245,.08);border-radius:10px;padding:14px;text-align:center;">
                <div style="font-size:1.6rem;font-weight:800;color:var(--brand-dendem);line-height:1;"><?= number_format($pendentes) ?></div>
                <div style="font-size:10px;font-weight:600;color:rgba(255,251,245,.5);text-transform:uppercase;margin-top:4px;">Pendentes</div>
              </div>
              <div style="background:rgba(255,251,245,.07);border:1px solid rgba(255,251,245,.08);border-radius:10px;padding:14px;text-align:center;">
                <div style="font-size:1.6rem;font-weight:800;color:#6AD2CE;line-height:1;"><?= number_format($emAnalise) ?></div>
                <div style="font-size:10px;font-weight:600;color:rgba(255,251,245,.5);text-transform:uppercase;margin-top:4px;">Em Análise</div>
              </div>
              <div style="background:rgba(255,251,245,.07);border:1px solid rgba(255,251,245,.08);border-radius:10px;padding:14px;text-align:center;">
                <div style="font-size:1.6rem;font-weight:800;color:#86C083;line-height:1;"><?= number_format($resolvidos) ?></div>
                <div style="font-size:10px;font-weight:600;color:rgba(255,251,245,.5);text-transform:uppercase;margin-top:4px;">Resolvidos</div>
              </div>
              <div style="background:rgba(255,251,245,.07);border:1px solid rgba(255,251,245,.08);border-radius:10px;padding:14px;text-align:center;">
                <div style="font-size:1.6rem;font-weight:800;color:#F4957A;line-height:1;"><?= number_format($rejeitados) ?></div>
                <div style="font-size:10px;font-weight:600;color:rgba(255,251,245,.5);text-transform:uppercase;margin-top:4px;">Rejeitados</div>
              </div>
            </div>

            <?php if (!empty($destaque)): ?>
            <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
              <?php foreach (array_slice($destaque, 0, 3) as $prob): ?>
              <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:rgba(255,251,245,.06);border:1px solid rgba(255,251,245,.07);border-radius:8px;">
                <span style="width:7px;height:7px;border-radius:50%;background:<?= e($prob['estado_cor']) ?>;flex-shrink:0;"></span>
                <span style="flex:1;font-size:12px;font-weight:600;color:rgba(255,251,245,.85);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($prob['titulo']) ?></span>
                <span style="font-size:11px;font-weight:700;color:rgba(255,251,245,.45);"><?= number_format($prob['total_confirmacoes']) ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<section class="stats-bar" aria-label="Panorama Geral">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-card__header">
          <span class="stat-card__label">Pendentes</span>
          <div class="stat-card__icon" style="background:var(--brand-dendem-subtle);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8C6211" stroke-width="2"><path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/></svg>
          </div>
        </div>
        <div class="stat-card__value" style="color:#8C6211;" data-target="<?= $pendentes ?>"><?= number_format($pendentes) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card__header">
          <span class="stat-card__label">Em Análise</span>
          <div class="stat-card__icon" style="background:var(--brand-baia-subtle);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </div>
        </div>
        <div class="stat-card__value" style="color:var(--brand-baia);" data-target="<?= $emAnalise ?>"><?= number_format($emAnalise) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card__header">
          <span class="stat-card__label">Resolvidos</span>
          <div class="stat-card__icon" style="background:var(--brand-capim-subtle);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--brand-capim)" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
        </div>
        <div class="stat-card__value" style="color:var(--brand-capim);" data-target="<?= $resolvidos ?>"><?= number_format($resolvidos) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card__header">
          <span class="stat-card__label">Rejeitados</span>
          <div class="stat-card__icon" style="background:var(--brand-terra-subtle);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          </div>
        </div>
        <div class="stat-card__value" style="color:var(--brand-terra);" data-target="<?= $rejeitados ?>"><?= number_format($rejeitados) ?></div>
      </div>
    </div>
  </div>
</section>

<!-- MINI-MAPA VISUAL DE OCORRÊNCIAS TEASER -->
<section class="section" style="padding: var(--space-10) 0; background: var(--surface-card); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
  <div class="container">
    <div style="display:grid; grid-template-columns: 1fr 1.2fr; gap: var(--space-8); align-items: center;">
      <div>
        <span class="section-header__kicker" style="color:var(--brand-terra);font-weight:700;">Geolocalização & Transparência Cívica</span>
        <h2 class="section-header__title" style="margin-top:4px;font-size:1.8rem;">Mini-Mapa Visual de Ocorrências</h2>
        <p style="color:var(--text-muted); font-size: 0.95rem; margin-top: 12px; line-height: 1.6;">
          Acompanhe os pontos críticos dos bairros de Luanda (Rangel, Cazenga, Talatona, Sambizanga, Hoji-ya-Henda, Viana...) e províncias. Visualize focos de buracos nas vias, cortes de água, falhas na iluminação e lixo acumulado.
        </p>

        <div style="margin-top: 24px; display:flex; gap: 12px; flex-wrap: wrap; align-items:center;">
          <a href="<?= url('mapa') ?>" class="btn btn--primary btn--md">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Abrir Mapa Interativo Completo
          </a>
          <span style="font-size: var(--font-xs); color: var(--text-muted);">
            ⚡ <?= number_format($panorama['total'] ?? 0) ?> pontos assinalados
          </span>
        </div>
      </div>

      <div style="position:relative; border-radius: var(--radius-xl); overflow:hidden; border: 2px solid var(--border-subtle); box-shadow: 0 8px 24px rgba(43,29,20,0.08); background: #E5E3DF; height: 280px;">
        <div id="home-mini-map-container" style="width:100%; height:100%;"></div>
        <div style="position:absolute; bottom:12px; right:12px; z-index:1000;">
          <a href="<?= url('mapa') ?>" class="btn btn--secondary btn--xs" style="background:rgba(255,251,245,0.92);backdrop-filter:blur(4px);box-shadow:0 4px 10px rgba(0,0,0,0.15);">
            Maximizar Mapa ⤢
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Include Leaflet CSS/JS for Home Teaser -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const homeMapContainer = document.getElementById('home-mini-map-container');
  if (!homeMapContainer) return;

  const miniMap = L.map('home-mini-map-container', {
    center: [-8.838333, 13.234444],
    zoom: 11,
    zoomControl: false,
    scrollWheelZoom: false,
    dragging: true
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap · Djumbai'
  }).addTo(miniMap);

  fetch('<?= url("api/mapa-ocorrencias") ?>')
    .then(res => res.json())
    .then(data => {
      if (data.sucesso && data.pontos) {
        const bounds = [];
        data.pontos.slice(0, 15).forEach(p => {
          const isCritico = p.total_confirmacoes >= 3 || p.estado_cor === '#D99A1E';
          const pinHtml = `
            <div style="width:24px;height:24px;border-radius:50%;background:${p.estado_cor};border:2px solid #FFF;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#FFF;font-size:10px;font-weight:bold;">
              !
            </div>
          `;
          const icon = L.divIcon({
            className: 'mini-map-pin',
            html: pinHtml,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
          });
          const m = L.marker([p.lat, p.lng], { icon: icon }).addTo(miniMap);
          m.bindPopup(`
            <div style="font-family:sans-serif;padding:4px;">
              <strong style="font-size:12px;display:block;margin-bottom:2px;">${p.titulo}</strong>
              <span style="font-size:11px;color:#666;">📍 ${p.bairro}</span><br>
              <a href="${p.url}" style="font-size:11px;color:#B4451F;font-weight:bold;margin-top:4px;display:inline-block;">Ver mais →</a>
            </div>
          `);
          bounds.push([p.lat, p.lng]);
        });
        if (bounds.length > 0) {
          miniMap.fitBounds(bounds, { padding: [20, 20], maxZoom: 13 });
        }
      }
    });
});
</script>

<!-- PROBLEMAS EM DESTAQUE -->
<?php if (!empty($destaque)): ?>
<section class="section" aria-labelledby="destaque-heading">
  <div class="container">
    <div class="section-header" style="flex-direction:row;justify-content:space-between;align-items:flex-end;display:flex;margin-bottom:var(--space-8);">
      <div>
        <span class="section-header__kicker">Mais Confirmados pela Comunidade</span>
        <h2 id="destaque-heading" class="section-header__title" style="margin-top:4px;">Reportes em Destaque</h2>
      </div>
      <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--sm">Ver Todos →</a>
    </div>

    <div style="display:flex;flex-direction:column;gap:var(--space-4);">
      <?php foreach ($destaque as $index => $prob): ?>
      <article class="problem-card">
        <div class="problem-card__header">
          <div style="display:flex;gap:12px;align-items:flex-start;">
            <span style="font-size:1.6rem;font-weight:800;color:var(--border-subtle);line-height:1;min-width:28px;"><?= $index + 1 ?></span>
            <div>
              <span class="badge" style="background:<?= e($prob['estado_cor']) ?>20;color:<?= e($prob['estado_cor']) ?>;margin-bottom:6px;">
                <span class="badge__dot" style="background:<?= e($prob['estado_cor']) ?>"></span>
                <?= e($prob['estado_nome']) ?>
              </span>
              <h3 class="problem-card__title">
                <a href="<?= url('problemas/' . $prob['id']) ?>" style="color:inherit;"><?= e($prob['titulo']) ?></a>
              </h3>
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <strong style="display:block;font-size:1.2rem;font-weight:800;color:var(--brand-baia);"><?= number_format($prob['total_confirmacoes']) ?></strong>
            <span style="font-size:var(--font-xs);color:var(--text-muted);">confirmações</span>
          </div>
        </div>
        <div class="problem-card__meta">
          <span><?= icon('map-pin', 14, '', 'var(--brand-terra)') ?> <?= e($prob['bairro_nome']) ?><?= $prob['municipio_nome'] ? ', ' . e($prob['municipio_nome']) : '' ?></span>
          <span>•</span>
          <span><?= e($prob['categoria_nome']) ?></span>
          <span>•</span>
          <span><?= timeAgo($prob['criado_em']) ?></span>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
