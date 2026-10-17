<?php
/**
 * View: admin/problemas.php (Gestão, Despacho e Triagem de Ocorrências)
 */
?>

<style>
  .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }
  .problem-row:hover { background: var(--surface-sunken); }
  .js-dispatch-close:hover { background: rgba(255,255,255,0.15) !important; color: #FFF !important; }
</style>

<div style="padding: 24px;">

  <!-- ══ 1. CABEÇALHO & ATALHOS ════════════════════════════════════ -->
  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <div style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--brand-terra), #8F2C0B); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #FFF;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--text-primary); margin: 0;">
          Gestão e Despacho de Ocorrências
        </h1>
      </div>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
        Avalie reportes comunitários, atribua brigadas municipais e despache soluções em tempo real.
      </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center;">
      <a href="<?= url('admin/mapa') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
        Ver no Mapa Virtual
      </a>
      <a href="<?= url('admin/reportar') ?>" class="btn btn--primary btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Novo Reporte Admin
      </a>
    </div>
  </div>

  <!-- ══ 2. CARDS DE KPI (VISÃO GERAL DO MUNICÍPIO) ═══════════════ -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px;">
    
    <!-- Total Geral -->
    <a href="<?= url('admin/problemas') ?>" style="text-decoration: none; display: flex; align-items: center; gap: 14px; background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 14px 18px; transition: transform 0.15s, box-shadow 0.15s;" class="kpi-card">>
      <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(31,111,107,0.1); color: var(--brand-baia); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      </div>
      <div>
        <div style="font-size: 1.45rem; font-weight: 800; color: var(--text-primary); line-height: 1;"><?= number_format($kpis['total']) ?></div>
        <div style="font-size: 11.5px; font-weight: 600; color: var(--text-muted); margin-top: 3px;">Total de Ocorrências</div>
      </div>
    </a>

    <!-- Pendentes -->
    <a href="<?= url('admin/problemas?estado=1') ?>" style="text-decoration: none; display: flex; align-items: center; gap: 14px; background: rgba(217,154,30,0.06); border: 1px solid rgba(217,154,30,0.3); border-radius: 12px; padding: 14px 18px; transition: transform 0.15s, box-shadow 0.15s;" class="kpi-card">>
      <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(217,154,30,0.18); color: var(--brand-dendem); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div>
        <div style="font-size: 1.45rem; font-weight: 800; color: var(--brand-dendem); line-height: 1;"><?= number_format($kpis['pendentes']) ?></div>
        <div style="font-size: 11.5px; font-weight: 600; color: var(--text-muted); margin-top: 3px;">Pendentes de Avaliação</div>
      </div>
    </a>

    <!-- Em Análise -->
    <a href="<?= url('admin/problemas?estado=2') ?>" style="text-decoration: none; display: flex; align-items: center; gap: 14px; background: rgba(31,111,107,0.06); border: 1px solid rgba(31,111,107,0.3); border-radius: 12px; padding: 14px 18px; transition: transform 0.15s, box-shadow 0.15s;" class="kpi-card">>
      <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(31,111,107,0.18); color: var(--brand-baia); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      </div>
      <div>
        <div style="font-size: 1.45rem; font-weight: 800; color: var(--brand-baia); line-height: 1;"><?= number_format($kpis['analise']) ?></div>
        <div style="font-size: 11.5px; font-weight: 600; color: var(--text-muted); margin-top: 3px;">Em Análise / Brigada</div>
      </div>
    </a>

    <!-- Resolvidos -->
    <a href="<?= url('admin/problemas?estado=3') ?>" style="text-decoration: none; display: flex; align-items: center; gap: 14px; background: rgba(76,175,80,0.06); border: 1px solid rgba(76,175,80,0.3); border-radius: 12px; padding: 14px 18px; transition: transform 0.15s, box-shadow 0.15s;" class="kpi-card">>
      <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(76,175,80,0.18); color: #2E7D32; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div>
        <div style="font-size: 1.45rem; font-weight: 800; color: #2E7D32; line-height: 1;"><?= number_format($kpis['resolvidos']) ?></div>
        <div style="font-size: 11.5px; font-weight: 600; color: var(--text-muted); margin-top: 3px;">Problemas Resolvidos</div>
      </div>
    </a>

  </div>

  <!-- ══ 3. BARRA DE PESQUISA E FILTROS DINÂMICOS ═══════════════════ -->
  <form id="filterForm" action="<?= url('admin/problemas') ?>" method="GET" style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; box-shadow: var(--shadow-sm);">
    
    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">

      <!-- Busca Textual -->
      <div style="flex: 1; min-width: 260px; position: relative;">
        <input 
          type="text" 
          name="q" 
          value="<?= e($busca) ?>" 
          placeholder="Pesquisar por título, rua, morador ou referência..." 
          class="form-input"
          style="padding-left: 36px; font-size: 13px;"
        />
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>

      <!-- Filtro Estado -->
      <select name="estado" class="form-select js-filter-select" style="width: auto; font-size: 12.5px; padding: 8px 30px 8px 12px;">
        <option value="0">Todos os Estados</option>
        <?php foreach ($estados as $est): ?>
          <option value="<?= $est['id'] ?>" <?= $estadoId === (int)$est['id'] ? 'selected' : '' ?>>
            <?= e($est['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Filtro Categoria -->
      <select name="categoria" class="form-select js-filter-select" style="width: auto; font-size: 12.5px; padding: 8px 30px 8px 12px;">
        <option value="0">Todas as Categorias</option>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $categoriaId === (int)$cat['id'] ? 'selected' : '' ?>>
            <?= e($cat['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Filtro Município -->
      <select name="municipio" class="form-select js-filter-select" style="width: auto; font-size: 12.5px; padding: 8px 30px 8px 12px;">
        <option value="0">Todos os Municípios</option>
        <?php foreach ($municipios as $mun): ?>
          <option value="<?= $mun['id'] ?>" <?= $municipioId === (int)$mun['id'] ? 'selected' : '' ?>>
            <?= e($mun['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Botão Aplicar Busca -->
      <button type="submit" class="btn btn--primary btn--sm" style="font-size: 12px; padding: 8px 16px;">
        Filtrar
      </button>

      <?php if (!empty($busca) || $estadoId > 0 || $categoriaId > 0 || $municipioId > 0): ?>
        <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm" style="font-size: 12px; color: var(--brand-terra);">
          Limpar Filtros
        </a>
      <?php endif; ?>

      <div style="margin-left: auto; font-size: 12px; color: var(--text-muted); font-weight: 700;">
        <?= number_format($total) ?> ocorrência<?= $total !== 1 ? 's' : '' ?>
      </div>

    </div>
  </form>

  <!-- ══ 4. LISTA DE OCORRÊNCIAS EM TABELA MODERNA ══════════════════ -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; box-shadow: var(--shadow-sm); overflow: hidden;">

    <?php if (empty($problemas)): ?>
      <div style="text-align: center; padding: 64px 20px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--surface-sunken); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--text-muted);">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 6px;">Nenhuma ocorrência encontrada</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Tente alterar os termos da pesquisa ou limpar os filtros seleccionados.</p>
      </div>
    <?php else: ?>

      <!-- Cabeçalho da Tabela -->
      <div style="display: grid; grid-template-columns: 80px 2.8fr 1.6fr 1.3fr 1fr 1.6fr; gap: 0; padding: 12px 20px; background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle); font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
        <span>Foto</span>
        <span>Ocorrência & Morador</span>
        <span>Localização</span>
        <span>Estado Atual</span>
        <span>Apoios</span>
        <span style="text-align: right;">Ações de Gestão</span>
      </div>

      <!-- Linhas de Ocorrências -->
      <?php foreach ($problemas as $p):
        $fotoRaw = $p['foto'] ?? null;
        if (!empty($fotoRaw)) {
          $thumbUrl = str_starts_with($fotoRaw, 'http') ? $fotoRaw : url(ltrim($fotoRaw, '/'));
        } else {
          $thumbUrl = null;
        }
        $estadoCor = $p['estado_cor'] ?? '#D99A1E';
        $problemJson = htmlspecialchars(json_encode([
          'id'                 => (int)$p['id'],
          'titulo'             => $p['titulo'],
          'descricao'          => $p['descricao'] ?? '',
          'foto_url'           => $thumbUrl,
          'referencia_local'   => $p['referencia_local'] ?? '',
          'bairro_nome'        => $p['bairro_nome'] ?? '',
          'municipio_nome'     => $p['municipio_nome'] ?? '',
          'autor_nome'         => $p['autor_nome'] ?? 'Cidadão',
          'autor_email'        => $p['autor_email'] ?? '',
          'categoria_nome'     => $p['categoria_nome'] ?? '',
          'estado_id'          => (int)$p['estado_id'],
          'estado_nome'        => $p['estado_nome'] ?? '',
          'estado_cor'         => $estadoCor,
          'total_confirmacoes' => (int)$p['total_confirmacoes'],
          'total_comentarios'  => (int)($p['total_comentarios'] ?? 0),
          'criado_em'          => dataAngolana($p['criado_em']),
          'url'                => url('problemas/' . $p['id']),
        ]), ENT_QUOTES, 'UTF-8');
      ?>
        <div class="problem-row" data-problem='<?= $problemJson ?>' style="display: grid; grid-template-columns: 80px 2.8fr 1.6fr 1.3fr 1fr 1.6fr; gap: 0; padding: 14px 20px; border-bottom: 1px solid var(--border-subtle); align-items: center; cursor: pointer; transition: background 0.15s;" title="Clique para ver os detalhes completos desta ocorrência">

          <!-- Foto Thumbnail -->
          <div>
            <?php if ($thumbUrl): ?>
              <!-- Fundo: Djumbai SVG (aparece se a foto falhar) -->
              <div style="width: 54px; height: 54px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-subtle); background: #F6EEDD; position: relative; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="44" height="44" style="flex-shrink:0; opacity:0.7;">
                  <rect width="512" height="512" rx="112" fill="#F6EEDD"/>
                  <g transform="translate(-38 18)">
                    <path d="M236 420 C236 420 124 322 124 232 A112 112 0 0 1 348 232 C348 322 236 420 236 420 Z" fill="#B4451F"/>
                    <circle cx="236" cy="232" r="46" fill="#FFFBF2"/>
                    <circle cx="360" cy="160" r="17" fill="#D99A1E"/>
                    <path d="M410 160 A50 50 0 0 0 360 110" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                    <path d="M452 160 A92 92 0 0 0 360 68" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                  </g>
                </svg>
                <!-- Foto real por cima; ao falhar esconde e o SVG fica visível -->
                <img src="<?= $thumbUrl ?>" alt="Foto da ocorrência" class="js-thumb-img" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;">
              </div>
            <?php else: ?>
              <!-- Sem fotografia: Thumbnail com logótipo Djumbai -->
              <div style="width: 54px; height: 54px; border-radius: 8px; background: #F6EEDD; border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Ocorrência sem imagem registada">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40" height="40" style="opacity: 0.85;">
                  <rect width="512" height="512" rx="112" fill="#F6EEDD"/>
                  <g transform="translate(-38 18)">
                    <path d="M236 420 C236 420 124 322 124 232 A112 112 0 0 1 348 232 C348 322 236 420 236 420 Z" fill="#B4451F"/>
                    <circle cx="236" cy="232" r="46" fill="#FFFBF2"/>
                    <circle cx="360" cy="160" r="17" fill="#D99A1E"/>
                    <path d="M410 160 A50 50 0 0 0 360 110" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                    <path d="M452 160 A92 92 0 0 0 360 68" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                  </g>
                </svg>
              </div>
            <?php endif; ?>
          </div>

          <!-- Título & Autor -->
          <div style="min-width: 0; padding-right: 14px;">
            <div style="font-weight: 700; font-size: 13.5px; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">
              <?= e($p['titulo']) ?>
            </div>
            <div style="font-size: 11.5px; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
              <span>Por <strong><?= e($p['autor_nome']) ?></strong></span>
              <span>•</span>
              <span><?= timeAgo($p['criado_em']) ?></span>
            </div>
            <div style="margin-top: 4px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
              <span style="display: inline-block; font-size: 10.5px; font-weight: 600; color: var(--brand-baia); background: rgba(31,111,107,0.08); padding: 1px 7px; border-radius: 4px;">
                <?= e($p['categoria_nome']) ?>
              </span>
              <?php if ($thumbUrl): ?>
                <span title="Esta ocorrência tem imagem" style="display: inline-flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 700; color: var(--brand-terra); background: rgba(180,69,31,0.08); padding: 1px 6px; border-radius: 4px; border: 1px solid rgba(180,69,31,0.18);">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="13" height="13" style="flex-shrink:0;">
                    <rect width="512" height="512" rx="112" fill="#F6EEDD"/>
                    <g transform="translate(-38 18)">
                      <path d="M236 420 C236 420 124 322 124 232 A112 112 0 0 1 348 232 C348 322 236 420 236 420 Z" fill="#B4451F"/>
                      <circle cx="236" cy="232" r="46" fill="#FFFBF2"/>
                      <circle cx="360" cy="160" r="17" fill="#D99A1E"/>
                      <path d="M410 160 A50 50 0 0 0 360 110" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                      <path d="M452 160 A92 92 0 0 0 360 68" fill="none" stroke="#D99A1E" stroke-width="24" stroke-linecap="round"/>
                    </g>
                  </svg>
                  Imagem
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Localização -->
          <div style="font-size: 12.5px; color: var(--text-secondary);">
            <div style="font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 4px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($p['bairro_nome']) ?>
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
              <?= e($p['municipio_nome']) ?>
            </div>
          </div>

          <!-- Estado Atual com Badge Colorida -->
          <div>
            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: <?= $estadoCor ?>18; color: <?= $estadoCor ?>; border: 1px solid <?= $estadoCor ?>35;">
              <span style="width: 6px; height: 6px; border-radius: 50%; background: <?= $estadoCor ?>;"></span>
              <?= e($p['estado_nome']) ?>
            </span>
          </div>

          <!-- Apoios / Confirmações -->
          <div>
            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 700; color: var(--text-primary);">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg> <?= number_format($p['total_confirmacoes']) ?>
            </span>
            <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 2px;">
              <?= (int)($p['total_comentarios'] ?? 0) ?> comentários
            </div>
          </div>

          <!-- Ações Rápidas (Abrir Despacho, Ver, Eliminar) -->
          <div class="js-row-actions" style="display: flex; gap: 6px; justify-content: flex-end;">
            <button type="button" class="js-btn-dispatch btn btn--primary btn--sm" style="font-size: 11px; padding: 6px 12px; background: var(--brand-baia); border: none; outline: none; box-shadow: none;">
              Despachar
            </button>

            <a href="<?= url('problemas/' . $p['id']) ?>" target="_blank" class="btn btn--ghost btn--sm" style="font-size: 11px; padding: 6px 8px;" title="Ver no Portal Público">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>

            <form action="<?= url('admin/problemas/' . $p['id'] . '/eliminar') ?>" method="POST" style="margin: 0;" class="js-delete-problem-form">
              <?= csrfField() ?>
              <button type="submit" class="btn btn--ghost btn--sm" style="font-size: 11px; padding: 6px 8px; color: #C0392B;" title="Eliminar Ocorrência">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </form>
          </div>

        </div>
      <?php endforeach; ?>

      <!-- Paginação -->
      <?php if ($totalPaginas > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); font-size: 12px;">
          <span style="color: var(--text-muted);">
            Página <strong><?= $pagina ?></strong> de <strong><?= $totalPaginas ?></strong>
          </span>

          <div style="display: flex; gap: 6px;">
            <?php if ($pagina > 1): ?>
              <a href="<?= url('admin/problemas?' . http_build_query(array_merge($_GET, ['pagina' => $pagina - 1]))) ?>" class="btn btn--ghost btn--sm">
                Anterior
              </a>
            <?php endif; ?>

            <?php if ($pagina < $totalPaginas): ?>
              <a href="<?= url('admin/problemas?' . http_build_query(array_merge($_GET, ['pagina' => $pagina + 1]))) ?>" class="btn btn--primary btn--sm">
                Seguinte
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>

</div>

<!-- ══ 5. MODAL DE DESPACHO E DETALHES COMPLETOS DA OCORRÊNCIA ══════ -->
<div id="dispatchModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(15,10,8,0.75); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 16px;">
  <div class="no-scrollbar" style="background: var(--surface-card); max-width: 680px; width: 100%; max-height: 92vh; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; border-radius: 18px; box-shadow: 0 32px 64px rgba(0,0,0,0.35); border: 1px solid var(--border-subtle); animation: scaleUp 0.3s cubic-bezier(0.16,1,0.3,1);">

    <!-- Header do Modal -->
    <div style="background: linear-gradient(135deg, #1A1410, #2D221A); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid var(--brand-baia);">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(31,111,107,0.2); border: 1px solid rgba(31,111,107,0.4); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
          <div style="font-size: 10.5px; font-weight: 700; color: var(--brand-dendem); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 2px;">Mesa de Despacho Municipal</div>
          <h3 id="dmTitulo" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #FFF;"></h3>
        </div>
      </div>
      <button class="js-dispatch-close" style="background: rgba(255,255,255,0.08); border: none; color: rgba(255,255,255,0.7); width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px; display: flex; align-items: center; justify-content: center; transition: all 0.15s;">&times;</button>
    </div>

    <!-- Conteúdo do Modal -->
    <div style="padding: 24px;">

      <!-- Fotografia em Alta (se houver) -->
      <div id="dmFotoWrapper" style="display: none; margin-bottom: 18px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-subtle); position: relative; background: var(--surface-sunken); height: 260px;">
        <!-- Foto real da ocorrência -->
        <img id="dmFotoImg" src="" alt="Fotografia da Ocorrência"
             style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <!-- Fallback caso o ficheiro não seja encontrado -->
        <div id="dmFotoFallback" style="display: none; position: absolute; inset: 0; align-items: center; justify-content: center; flex-direction: column; gap: 8px; color: var(--text-muted); background: var(--surface-sunken);">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <span style="font-size: 12px;">Não foi possível carregar o arquivo da imagem</span>
        </div>
        <!-- Botão Ver Original -->
        <a id="dmFotoLink" href="" target="_blank"
           style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: #FFF; font-size: 11px; font-weight: 700; padding: 5px 11px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 5px; backdrop-filter: blur(4px); z-index: 2;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Ver original
        </a>
      </div>

      <!-- Resumo de Metadados -->
      <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 14px 16px; margin-bottom: 18px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
        <div>
          <span style="color: var(--text-muted);">Cidadão Autor:</span>
          <strong id="dmAutor" style="color: var(--text-primary); margin-left: 4px;"></strong>
        </div>
        <div>
          <span style="color: var(--text-muted);">Localização:</span>
          <strong id="dmLocalizacao" style="color: var(--text-primary); margin-left: 4px;"></strong>
        </div>
        <div>
          <span style="color: var(--text-muted);">Categoria:</span>
          <strong id="dmCategoria" style="color: var(--brand-baia); margin-left: 4px;"></strong>
        </div>
        <div>
          <span style="color: var(--text-muted);">Apoios Comunitários:</span>
          <strong id="dmApoios" style="color: var(--text-primary); margin-left: 4px;"></strong>
        </div>
        <div style="grid-column: span 2;" id="dmReferenciaRow">
          <span style="color: var(--text-muted);">Ponto de Referência:</span>
          <span id="dmReferencia" style="color: var(--text-primary); font-weight: 600; margin-left: 4px;"></span>
        </div>
      </div>

      <!-- Descrição da Ocorrência -->
      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
          Descrição Detalhada do Problema
        </label>
        <div id="dmDescricao" style="background: var(--surface-ground); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px; font-size: 13px; line-height: 1.6; color: var(--text-primary); max-height: 150px; overflow-y: auto;"></div>
      </div>

      <!-- Formulário de Despacho de Estado -->
      <form id="dmForm" action="" method="POST" style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 18px;">
        <?= csrfField() ?>

        <div style="font-size: 11.5px; font-weight: 800; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Atualizar Estado & Emitir Despacho
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); margin-bottom: 4px;">Novo Estado:</label>
            <select name="estado_id" id="dmEstadoSelect" class="form-select" style="font-size: 12.5px;">
              <?php foreach ($estados as $est): ?>
                <option value="<?= $est['id'] ?>"><?= e($est['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); margin-bottom: 4px;">Atalhos de Despacho:</label>
            <select class="form-select js-dispatch-preset" style="font-size: 12.5px;">
              <option value="">Selecione um modelo...</option>
              <option value="Brigada municipal de saneamento destacada para o local.">Brigada destacada</option>
              <option value="Material em aprovisionamento junto do governo provincial.">Material em aprovisionamento</option>
              <option value="Trabalhos de reparação concluídos e vistoriados com sucesso.">Reparação concluída</option>
              <option value="Ocorrência duplicada ou dados insuficientes após vistoria técnica.">Ocorrência rejeitada</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom: 14px;">
          <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); margin-bottom: 4px;">Nota de Despacho Pública:</label>
          <input type="text" name="nota" id="dmNotaInput" class="form-input" placeholder="Ex: Brigada nº 4 enviada para intervenção no pavimento..." style="font-size: 12.5px; padding: 9px 12px;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="js-close-dispatch btn btn--ghost btn--sm">Cancelar</button>
          <button type="submit" class="btn btn--primary btn--sm" style="padding: 9px 20px; font-weight: 700;">
            Salvar Despacho
          </button>
        </div>
      </form>

    </div>

  </div>
</div>

<script nonce="<?= e(CSP_NONCE) ?>">
function handleProblemRowClick(e) {
  const row = this;
  if (e.target.closest('button') || e.target.closest('a') || e.target.closest('form')) {
    return;
  }
  const raw = row.getAttribute('data-problem');
  if (!raw) return;
  try {
    const prob = JSON.parse(raw);
    openDispatchModal(prob);
  } catch (err) {
    console.error('Erro ao abrir detalhes:', err);
  }
}

function openDispatchModal(prob) {
  document.getElementById('dmTitulo').textContent = prob.titulo || 'Ocorrência';
  document.getElementById('dmAutor').textContent = prob.autor_nome + (prob.autor_email ? ' (' + prob.autor_email + ')' : '');
  document.getElementById('dmLocalizacao').textContent = (prob.bairro_nome || '—') + (prob.municipio_nome ? (', ' + prob.municipio_nome) : '');
  document.getElementById('dmCategoria').textContent = prob.categoria_nome || 'Geral';
  document.getElementById('dmApoios').textContent = prob.total_confirmacoes + ' confirmações comunitárias';
  
  if (prob.referencia_local) {
    document.getElementById('dmReferenciaRow').style.display = 'block';
    document.getElementById('dmReferencia').textContent = prob.referencia_local;
  } else {
    document.getElementById('dmReferenciaRow').style.display = 'none';
  }

  document.getElementById('dmDescricao').textContent = prob.descricao || 'Sem descrição adicional.';
  document.getElementById('dmEstadoSelect').value = prob.estado_id;
  document.getElementById('dmNotaInput').value = '';
  document.getElementById('dmForm').action = '<?= url('admin/problemas/') ?>' + prob.id + '/estado';

  const fotoWrap = document.getElementById('dmFotoWrapper');
  const fotoImg = document.getElementById('dmFotoImg');
  const fotoLink = document.getElementById('dmFotoLink');
  const fotoFallback = document.getElementById('dmFotoFallback');

  if (prob.foto_url) {
    if (fotoFallback) fotoFallback.style.display = 'none';
    fotoImg.src = prob.foto_url;
    fotoLink.href = prob.foto_url;
    fotoImg.style.display = 'block';
    fotoWrap.style.display = 'block';
  } else {
    fotoWrap.style.display = 'none';
  }

  document.getElementById('dispatchModal').style.display = 'flex';
}

function closeDispatchModal() {
  document.getElementById('dispatchModal').style.display = 'none';
}

document.getElementById('dispatchModal').addEventListener('click', function(e) {
  if (e.target === this) closeDispatchModal();
});

// ── EVENT BINDINGS (CSP-compliant) ───────────────────────────────
document.querySelectorAll('.problem-row').forEach(r => r.addEventListener('click', handleProblemRowClick));
document.querySelectorAll('.js-filter-select').forEach(s => s.addEventListener('change', function(){ this.form.submit(); }));
document.querySelectorAll('.js-thumb-img').forEach(img => img.addEventListener('error', function(){ this.style.display = 'none'; }));
document.querySelectorAll('.js-row-actions').forEach(el => el.addEventListener('click', function(e){ e.stopPropagation(); }));
document.querySelectorAll('.js-btn-dispatch').forEach(b => b.addEventListener('click', function(){
  const raw = this.closest('.problem-row').getAttribute('data-problem');
  if (raw) { try { openDispatchModal(JSON.parse(raw)); } catch(err){ console.error(err); } }
}));
document.querySelectorAll('.js-delete-problem-form').forEach(f => f.addEventListener('submit', function(e){
  if (!confirm('Tem a certeza que deseja eliminar esta ocorrência definitivamente?')) e.preventDefault();
}));
document.querySelectorAll('.js-dispatch-close').forEach(b => b.addEventListener('click', closeDispatchModal));
document.querySelectorAll('.js-close-dispatch').forEach(b => b.addEventListener('click', closeDispatchModal));
document.getElementById('dmFotoImg').addEventListener('error', function(){
  this.style.display = 'none';
  document.getElementById('dmFotoFallback').style.display = 'flex';
});
document.querySelectorAll('.js-dispatch-preset').forEach(s => s.addEventListener('change', function(){
  if (this.value) document.getElementById('dmNotaInput').value = this.value;
}));

// ── PESQUISA EM TEMPO REAL DE OCORRÊNCIAS ───────────────────────
const inputBuscaProblemas = document.querySelector('input[name="q"]');
if (inputBuscaProblemas) {
  let searchTimer = null;
  inputBuscaProblemas.addEventListener('input', function() {
    const termo = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('.problem-row');

    rows.forEach(r => {
      const txt = (r.textContent || '').toLowerCase();
      r.style.display = (!termo || txt.includes(termo)) ? '' : 'none';
    });

    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      if (inputBuscaProblemas.form) {
        inputBuscaProblemas.form.submit();
      }
    }, 600);
  });
}
</script>
