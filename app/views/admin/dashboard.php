<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: admin/dashboard.php — CENTRO DE DESPACHO & SLA    ║
     ╚══════════════════════════════════════════════════════════╝ -->

<?php
$totalGeral     = (int) ($panorama['total'] ?? 0);
$pendentes      = (int) ($panorama['pendentes'] ?? 0);
$emAnalise      = (int) ($panorama['em_analise'] ?? 0);
$resolvidos     = (int) ($panorama['resolvidos'] ?? 0);
$rejeitados     = (int) ($panorama['rejeitados'] ?? 0);

$mediaDiasSLA   = $metricasSLA['media_dias_resolucao'] ?? 0;
$novosHoje      = $metricasSLA['novos_hoje'] ?? 0;
$atrasadosSLA   = $metricasSLA['atrasados_criticos'] ?? 0;

$rotulosPeriodo = [
    'todos'  => 'Histórico Total',
    '30dias' => 'Últimos 30 Dias',
    '7dias'  => 'Últimos 7 Dias',
    'hoje'   => 'Hoje',
];
$periodoAtualLabel = $rotulosPeriodo[$periodo] ?? 'Histórico Total';
?>

<!-- Leaflet CSS para o Mini-Mapa Integrado -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<style>
  /* Estilização Executiva do Dashboard */
  .dash-period-pills {
    display: inline-flex;
    background: var(--surface-sunken);
    padding: 3px;
    border-radius: var(--radius-full);
    border: 1px solid var(--border-subtle);
    gap: 2px;
  }
  .dash-period-pill {
    padding: 6px 14px;
    border-radius: var(--radius-full);
    font-size: 11.5px;
    font-weight: 700;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .dash-period-pill:hover {
    color: var(--text-primary);
  }
  .dash-period-pill.is-active {
    background: var(--brand-terra);
    color: #FFF;
    box-shadow: 0 2px 6px rgba(180,69,31,0.25);
  }

  .alert-card-item {
    background: var(--surface-card);
    border: 1px solid var(--border-subtle);
    border-left: 4px solid var(--brand-terra);
    border-radius: var(--radius-md);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }
  .alert-card-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }

  .alert-badge--sla {
    background: rgba(180, 69, 31, 0.12);
    color: var(--brand-terra);
    border: 1px solid rgba(180, 69, 31, 0.3);
    font-weight: 800;
    font-size: 10.5px;
    padding: 3px 8px;
    border-radius: var(--radius-xs);
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .alert-badge--surto {
    background: rgba(217, 154, 30, 0.15);
    color: #93630A;
    border: 1px solid rgba(217, 154, 30, 0.4);
    font-weight: 800;
    font-size: 10.5px;
    padding: 3px 8px;
    border-radius: var(--radius-xs);
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  /* Mini-Mapa Container */
  #miniMapaDashboard {
    width: 100%;
    height: 380px;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
    z-index: 10;
  }

  /* Pulse para o Mini-Mapa */
  .mini-marker-pulse {
    position: relative;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.35);
  }

  /* Modal de Despacho Rápido */
  .quick-dispatch-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(18, 14, 11, 0.65);
    backdrop-filter: blur(4px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .quick-dispatch-modal.is-active {
    display: flex;
  }
  .quick-dispatch-dialog {
    background: var(--surface-card);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
    box-shadow: var(--shadow-modal);
    width: 100%;
    max-width: 520px;
    overflow: hidden;
    animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Estilos de Impressão de Boletim Municipal */
  @media print {
    body { background: #FFF !important; color: #000 !important; }
    .admin-sidebar, .admin-topbar, .dash-period-pills, .dash-header-actions, .btn, #miniMapaDashboard, .quick-dispatch-modal {
      display: none !important;
    }
    .admin-canvas { margin-left: 0 !important; width: 100% !important; }
    .admin-content { padding: 0 !important; }
    .print-only-header { display: block !important; margin-bottom: 24px; border-bottom: 2px solid #333; padding-bottom: 12px; }
  }
  .print-only-header { display: none; }
</style>

<!-- Cabeçalho visível apenas na impressão -->
<div class="print-only-header">
  <h1 style="font-size: 20pt; font-weight: 800; margin: 0; color: #111;">REPÚBLICA DE ANGOLA — PLATAFORMA DJUMBAI</h1>
  <p style="font-size: 11pt; color: #555; margin: 4px 0 0;">Relatório e Boletim Executivo de Monitorização Municipal • Emissão: <?= date('d/m/Y H:i') ?></p>
</div>

<!-- ── 1. CABEÇALHO DO DASHBOARD COM FILTROS DE PERÍODO ────────────── -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
  <div>
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
      <span style="font-size: var(--font-xs); font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 1px;">
        Centro de Despacho Comunitário
      </span>
      <span style="display: inline-block; width: 4px; height: 4px; border-radius: 50%; background: var(--text-muted);"></span>
      <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">
        Período: <strong style="color: var(--text-primary);"><?= $periodoAtualLabel ?></strong>
      </span>
    </div>
    <h1 style="font-size: 1.65rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1.1; margin: 0 0 6px;">
      Monitorização &amp; Inteligência Municipal
    </h1>
    <p style="color: var(--text-secondary); font-size: var(--font-sm); margin: 0;">
      Acompanhamento operacional, cumprimento de SLA e despacho de infraestruturas em Angola.
    </p>
  </div>

  <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;" class="dash-header-actions">
    <!-- Seletor de Período Temporal -->
    <div class="dash-period-pills">
      <a href="<?= url('admin?periodo=todos') ?>" class="dash-period-pill <?= $periodo === 'todos' ? 'is-active' : '' ?>">Geral</a>
      <a href="<?= url('admin?periodo=30dias') ?>" class="dash-period-pill <?= $periodo === '30dias' ? 'is-active' : '' ?>">30 Dias</a>
      <a href="<?= url('admin?periodo=7dias') ?>" class="dash-period-pill <?= $periodo === '7dias' ? 'is-active' : '' ?>">7 Dias</a>
      <a href="<?= url('admin?periodo=hoje') ?>" class="dash-period-pill <?= $periodo === 'hoje' ? 'is-active' : '' ?>">Hoje</a>
    </div>

    <!-- Botões de Ação Rápida e Impressão -->
    <div style="display: flex; gap: var(--space-2); flex-wrap: wrap;">
      <a href="<?= url('admin/reportar') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;" title="Registar ocorrência administrativa">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        <span>Registar Ocorrência</span>
      </a>

      <button type="button" id="btnPrint" class="btn btn--ghost btn--sm" title="Gerar relatório impresso ou salvar PDF">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        <span>Imprimir Boletim</span>
      </button>

      <a href="<?= url('admin/problemas?estado=1') ?>" class="btn btn--primary btn--sm">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>Triagem (<?= $pendentes ?> Pendentes)</span>
      </a>
    </div>
  </div>
</div>

<!-- ── 2. CARDS DE KPI & MÉTRICAS DE SLA OPERACIONAL (5 CARDS) ─────── -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: var(--space-6);">

  <!-- Total do Período -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-baia);">
    <div class="stat-card__header">
      <span class="stat-card__label">Ocorrências</span>
      <div class="stat-card__icon" style="background: var(--brand-baia-subtle); color: var(--brand-baia);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--text-primary);" data-target="<?= $totalGeral ?>"><?= number_format($totalGeral) ?></div>
    <span style="font-size: var(--font-xs); color: var(--text-muted);">
      <?= $periodoAtualLabel ?>
    </span>
  </div>

  <!-- Ocorrências Pendentes -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-dendem);">
    <div class="stat-card__header">
      <span class="stat-card__label">Aguardam Ação</span>
      <div class="stat-card__icon" style="background: var(--brand-dendem-subtle); color: #8C6211;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: #8C6211;" data-target="<?= $pendentes ?>"><?= number_format($pendentes) ?></div>
    <span style="font-size: var(--font-xs); color: #8C6211; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
      <?php if ($atrasadosSLA > 0): ?>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> <?= $atrasadosSLA ?> com SLA &gt; 3 dias
      <?php else: ?>
        Fila em dia
      <?php endif; ?>
    </span>
  </div>

  <!-- Em Intervenção Técnica -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-baia);">
    <div class="stat-card__header">
      <span class="stat-card__label">Em Intervenção</span>
      <div class="stat-card__icon" style="background: var(--brand-baia-subtle); color: var(--brand-baia);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--brand-baia);" data-target="<?= $emAnalise ?>"><?= number_format($emAnalise) ?></div>
    <span style="font-size: var(--font-xs); color: var(--brand-baia); font-weight: 600;">
      Brigadas em campo
    </span>
  </div>

  <!-- Tempo Médio de Resolução (SLA) -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-capim);">
    <div class="stat-card__header">
      <span class="stat-card__label">SLA Médio</span>
      <div class="stat-card__icon" style="background: var(--brand-capim-subtle); color: var(--brand-capim);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--brand-capim);">
      <?= $mediaDiasSLA > 0 ? $mediaDiasSLA . ' <span style="font-size:14px;font-weight:600;">dias</span>' : '—' ?>
    </div>
    <span style="font-size: var(--font-xs); color: var(--text-muted);">
      Tempo médio de desfecho
    </span>
  </div>

  <!-- Taxa de Sucesso / Resolução -->
  <div class="stat-card" style="border-left: 4px solid #4CAF50;">
    <div class="stat-card__header">
      <span class="stat-card__label">Eficácia Global</span>
      <div class="stat-card__icon" style="background: rgba(76,175,80,0.12); color: #2E7D32;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: #2E7D32;"><?= $taxaResolucao ?>%</div>
    <div style="width: 100%; height: 5px; background: var(--border-subtle); border-radius: 3px; overflow: hidden; margin-top: 5px;">
      <div style="width: <?= min(100, max(4, $taxaResolucao)) ?>%; height: 100%; background: #4CAF50; border-radius: 3px;"></div>
    </div>
  </div>

</div>

<!-- ── 3. PAINEL DE OCORRÊNCIAS CRÍTICAS / ALERTA MUNICIPAL ────────── -->
<?php if (!empty($ocorrenciasCriticas)): ?>
  <div style="background: var(--surface-card); border: 1px solid rgba(180,69,31,0.25); border-radius: var(--radius-lg); padding: var(--space-5); margin-bottom: var(--space-6); box-shadow: var(--shadow-sm);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 8px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(180,69,31,0.12); color: var(--brand-terra); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
          <h2 style="font-size: var(--font-sm); font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
            Sirene Municipal — Casos que Exigem Intervenção Prioritária
          </h2>
          <span style="font-size: 11.5px; color: var(--text-muted);">
            Ocorrências com atraso de triagem ou forte clamor comunitário
          </span>
        </div>
      </div>
      <span class="badge" style="background: rgba(180,69,31,0.12); color: var(--brand-terra); font-weight: 800;">
        <?= count($ocorrenciasCriticas) ?> Prioridades Ativas
      </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php foreach ($ocorrenciasCriticas as $crit): ?>
        <div class="alert-card-item">
          <div style="display: flex; align-items: flex-start; gap: 14px; flex: 1;">
            <div style="padding-top: 2px;">
              <?php if ($crit['motivo_alerta'] === 'sla_estourado'): ?>
                <span class="alert-badge--sla">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Atraso SLA (<?= $crit['dias_aberto'] ?> dias)
                </span>
              <?php else: ?>
                <span class="alert-badge--surto">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                  Clamor Popular (<?= $crit['total_confirmacoes'] ?> apoios)
                </span>
              <?php endif; ?>
            </div>

            <div style="flex: 1; min-width: 0;">
              <a href="<?= url('problemas/' . $crit['id']) ?>" target="_blank" style="font-size: var(--font-sm); font-weight: 800; color: var(--text-primary); text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= e($crit['titulo']) ?>
              </a>
              <div style="font-size: 11px; color: var(--text-secondary); margin-top: 2px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> <strong><?= e($crit['bairro_nome']) ?></strong> (<?= e($crit['municipio_nome']) ?>)</span>
                <span>•</span>
                <span>Área: <?= e($crit['categoria_nome']) ?></span>
                <span>•</span>
                <span style="color: var(--text-muted);"><?= timeAgo($crit['criado_em']) ?></span>
              </div>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 8px;">
            <button 
              type="button" 
              class="btn btn--primary btn--sm js-quick-dispatch" 
              data-id="<?= (int)$crit['id'] ?>"
              data-titulo="<?= e($crit['titulo']) ?>"
              data-estado-id="<?= (int)$crit['estado_id'] ?>"
              style="padding: 6px 12px; font-size: 11px;"
            >
              <span>Despacho Rápido</span>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<!-- ── 4. MINI-MAPA DE OCORRÊNCIAS INTEGRADO NO DASHBOARD ───────────── -->
<div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-5); margin-bottom: var(--space-6); box-shadow: var(--shadow-sm);">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 8px;">
    <div>
      <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary); margin: 0 0 2px;">
        Geolocalização &amp; Manchas Críticas
      </h2>
      <p style="font-size: var(--font-xs); color: var(--text-muted); margin: 0;">
        Distribuição territorial instantânea dos pontos comunitários em Angola.
      </p>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-secondary);">
        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #D99A1E;"></span> Pendente
        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #1F6F6B; margin-left: 6px;"></span> Em Análise
        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #3F5A3C; margin-left: 6px;"></span> Resolvido
      </div>

      <a href="<?= url('admin/mapa') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
        <span>Ecrã Total</span>
      </a>
    </div>
  </div>

  <div id="miniMapaDashboard"></div>
</div>

<!-- ── 5. PAINEL DE INTELIGÊNCIA CÍVICA (2 COLUNAS) ────────────────── -->
<div style="display: grid; grid-template-columns: 1fr; gap: var(--space-6); margin-bottom: var(--space-8); @media(min-width:992px){grid-template-columns: 1.1fr 0.9fr;}">

  <!-- Distribuição por Categoria -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-5);">
      <div>
        <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary); margin: 0 0 2px;">Demandas por Categoria</h2>
        <span style="font-size: var(--font-xs); color: var(--text-muted);">Volume relativo de queixas reportadas</span>
      </div>
      <span class="badge badge--analysis" style="font-size: 11px;">
        <?= count($distribuicaoCategorias) ?> Setores
      </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: var(--space-4);">
      <?php foreach ($distribuicaoCategorias as $cat): ?>
        <?php 
          $catTotal = (int) $cat['total'];
          $perc = $totalGeral > 0 ? round(($catTotal / $totalGeral) * 100) : 0;
        ?>
        <div>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; font-size: var(--font-xs);">
            <strong style="color: var(--text-primary); font-weight: 700;"><?= e($cat['nome']) ?></strong>
            <span style="color: var(--text-secondary); font-weight: 600;"><?= $catTotal ?> ocorrência(s) (<?= $perc ?>%)</span>
          </div>
          <div style="width: 100%; height: 8px; background: var(--surface-inset); border-radius: 4px; overflow: hidden; display: flex;">
            <div style="width: <?= $perc ?>%; height: 100%; background: var(--brand-terra); border-radius: 4px; transition: width 0.8s ease;"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Zonas com Maior Demanda & Trilha de Auditoria -->
  <div style="display: flex; flex-direction: column; gap: var(--space-6);">

    <!-- Top Municípios -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
        <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary); margin: 0 0 2px;">Municípios com Maior Demanda</h2>
        <span style="font-size: var(--font-xs); color: var(--text-muted);">Incidência</span>
      </div>

      <?php if (empty($distribuicaoMunicipios)): ?>
        <p style="font-size: var(--font-xs); color: var(--text-muted); margin: 0;">Nenhum dado geográfico acumulado.</p>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <?php foreach ($distribuicaoMunicipios as $index => $m): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm);">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 22px; height: 22px; border-radius: 50%; background: var(--brand-terra-subtle); color: var(--brand-terra); font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                  <?= $index + 1 ?>
                </span>
                <div>
                  <strong style="font-size: var(--font-xs); color: var(--text-primary);"><?= e($m['municipio']) ?></strong>
                  <span style="display: block; font-size: 10px; color: var(--text-muted);"><?= e($m['provincia']) ?></span>
                </div>
              </div>
              <span class="badge badge--analysis"><?= $m['total'] ?> reportes</span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Trilha de Auditoria Recente -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
      <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary); margin: 0 0 var(--space-4);">
        Despachos Administrativos Recentes
      </h2>

      <?php if (empty($historicoAuditoria)): ?>
        <p style="font-size: var(--font-xs); color: var(--text-muted); margin: 0;">Nenhuma alteração de estado registada recentemente.</p>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 10px;">
          <?php foreach ($historicoAuditoria as $hist): ?>
            <div style="padding: 10px; background: var(--surface-elevated); border-left: 3px solid <?= e($hist['estado_novo_cor']) ?>; border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: var(--font-xs);">
              <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                <strong style="color: var(--text-primary);"><?= e($hist['problema_titulo']) ?></strong>
                <span style="color: var(--text-muted); font-size: 10px;"><?= timeAgo($hist['criado_em']) ?></span>
              </div>
              <div style="color: var(--text-secondary);">
                Alterado para <strong style="color: <?= e($hist['estado_novo_cor']) ?>"><?= e($hist['estado_novo_nome']) ?></strong> por <em><?= e($hist['admin_nome']) ?></em>
              </div>
              <?php if (!empty($hist['observacao'])): ?>
                <div style="font-size: 11px; color: var(--text-muted); font-style: italic; margin-top: 3px;">
                  "<?= e($hist['observacao']) ?>"
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

</div>

<!-- ── 6. TABELA DE ÚLTIMOS REPORTES COM DESPACHO RÁPIDO ───────────── -->
<div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-5); flex-wrap: wrap; gap: var(--space-3);">
    <div>
      <h2 style="font-size: var(--font-lg); font-weight: 800; color: var(--text-primary); margin: 0 0 2px;">Fila de Entrada &amp; Últimos Reportes</h2>
      <p style="font-size: var(--font-xs); color: var(--text-secondary); margin: 0;">Avaliação técnica imediata com ação de despacho integrado</p>
    </div>

    <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm">
      Gerir Todas as Ocorrências (<?= $totalGeral ?>)
    </a>
  </div>

  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: var(--font-sm);">
      <thead>
        <tr style="border-bottom: 2px solid var(--border-subtle); color: var(--text-muted); font-size: var(--font-xs); text-transform: uppercase;">
          <th style="padding: 12px 10px;">Ocorrência</th>
          <th style="padding: 12px 10px;">Localização</th>
          <th style="padding: 12px 10px;">Categoria</th>
          <th style="padding: 12px 10px;">Apoio Popular</th>
          <th style="padding: 12px 10px;">Estado Atual</th>
          <th style="padding: 12px 10px; text-align: right;">Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentes as $r): ?>
          <tr style="border-bottom: 1px solid var(--border-subtle); transition: background 0.15s ease;">
            <td style="padding: 14px 10px;">
              <a href="<?= url('problemas/' . $r['id']) ?>" target="_blank" style="font-weight: 700; color: var(--text-primary); text-decoration: none;">
                <?= e($r['titulo']) ?>
              </a>
              <span style="display: block; font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                Por <?= e($r['autor_nome']) ?> • <?= timeAgo($r['criado_em']) ?>
              </span>
            </td>
            <td style="padding: 14px 10px; color: var(--text-secondary); font-size: var(--font-xs);">
              <strong><?= e($r['bairro_nome']) ?></strong>
              <span style="display: block; color: var(--text-muted);"><?= e($r['municipio_nome']) ?></span>
            </td>
            <td style="padding: 14px 10px;">
              <span class="badge" style="background: var(--surface-inset); color: var(--text-secondary);">
                <?= e($r['categoria_nome']) ?>
              </span>
            </td>
            <td style="padding: 14px 10px;">
              <span class="badge badge--analysis">
                <?= icon('vote', 12) ?> <?= number_format($r['total_confirmacoes']) ?>
              </span>
            </td>
            <td style="padding: 14px 10px;">
              <span class="badge" style="background: <?= e($r['estado_cor']) ?>20; color: <?= e($r['estado_cor']) ?>; font-weight: 700;">
                <span class="badge__dot" style="background: <?= e($r['estado_cor']) ?>"></span>
                <?= e($r['estado_nome']) ?>
              </span>
            </td>
            <td style="padding: 14px 10px; text-align: right;">
              <button 
                type="button" 
                class="btn btn--primary btn--sm js-quick-dispatch" 
                data-id="<?= (int)$r['id'] ?>"
                data-titulo="<?= e($r['titulo']) ?>"
                data-estado-id="<?= (int)$r['estado_id'] ?>"
                style="font-size: 11px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 4px;"
              >
                <span>Despachar</span>
                <?= icon('settings', 12) ?>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- ── 7. MODAL DE DESPACHO RÁPIDO INTEGRADO ───────────────────────── -->
<div class="quick-dispatch-modal" id="quickDispatchModal">
  <div class="quick-dispatch-dialog">
    <div style="padding: 18px 24px; background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--brand-terra-subtle); color: var(--brand-terra); display: flex; align-items: center; justify-content: center;">
          <?= icon('settings', 16) ?>
        </div>
        <div>
          <h3 style="font-size: var(--font-sm); font-weight: 800; color: var(--text-primary); margin: 0;">Despacho de Ocorrência</h3>
          <span style="font-size: 11px; color: var(--text-muted);" id="dispatchModalSubtitle">Atualizar estado e nota municipal</span>
        </div>
      </div>
      <button type="button" class="js-close-quick-dispatch" style="background: transparent; border: none; font-size: 20px; line-height: 1; cursor: pointer; color: var(--text-muted);">&times;</button>
    </div>

    <form id="formQuickDispatch" method="POST" action="">
      <?= csrfField() ?>
      <input type="hidden" name="origem" value="dashboard" />

      <div style="padding: 24px; display: flex; flex-direction: column; gap: 18px;">
        <div>
          <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
            Ocorrência Selecionada
          </label>
          <div id="dispatchProbTitle" style="font-size: var(--font-sm); font-weight: 700; color: var(--text-primary); padding: 10px 14px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm);">
            —
          </div>
        </div>

        <div>
          <label for="dispatchEstadoId" style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
            Novo Estado de Atendimento *
          </label>
          <select id="dispatchEstadoId" name="estado_id" class="form-select" required style="width: 100%;">
            <?php foreach ($estados as $est): ?>
              <option value="<?= (int)$est['id'] ?>">
                <?= e($est['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="dispatchNota" style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
            Nota de Despacho Técnico / Observação
          </label>
          <textarea id="dispatchNota" name="nota" rows="3" class="form-input" placeholder="Ex: Brigada enviada para limpeza e desobstrução da via..."></textarea>
        </div>
      </div>

      <div style="padding: 16px 24px; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn btn--ghost btn--sm js-close-quick-dispatch">
          Cancelar
        </button>
        <button type="submit" class="btn btn--primary btn--sm">
          Salvar Despacho
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Leaflet JS & Scripts de Inicialização -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin="" nonce="<?= e(CSP_NONCE) ?>"></script>
<script nonce="<?= e(CSP_NONCE) ?>">
document.addEventListener('DOMContentLoaded', function () {
  // ── 1. MODAL DE DESPACHO RÁPIDO ────────────────────────────────
  const modal = document.getElementById('quickDispatchModal');
  const form = document.getElementById('formQuickDispatch');
  const probTitle = document.getElementById('dispatchProbTitle');
  const estadoSelect = document.getElementById('dispatchEstadoId');
  const notaInput = document.getElementById('dispatchNota');

  window.abrirDespachoRapido = function (id, titulo, estadoAtualId) {
    if (!modal) return;
    form.action = '<?= url("admin/problemas/") ?>' + id + '/estado';
    probTitle.textContent = titulo;
    if (estadoSelect) {
      estadoSelect.value = estadoAtualId;
    }
    if (notaInput) {
      notaInput.value = '';
    }
    modal.classList.add('is-active');
  };

  window.fecharDespachoRapido = function () {
    if (!modal) return;
    modal.classList.remove('is-active');
  };

  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        fecharDespachoRapido();
      }
    });
  }

  // ── 2. MINI-MAPA LEAFLET INTEGRADO NO DASHBOARD ────────────────
  const mapElement = document.getElementById('miniMapaDashboard');
  if (mapElement && typeof L !== 'undefined') {
    // Centro inicial padrão (Luanda)
    const map = L.map('miniMapaDashboard', {
      zoomControl: true,
      scrollWheelZoom: false,
    }).setView([-8.838333, 13.234444], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const pontos = <?= json_encode($pontosMapa, JSON_UNESCAPED_UNICODE) ?>;
    const markers = [];

    pontos.forEach(p => {
      const lat = parseFloat(p.latitude);
      const lng = parseFloat(p.longitude);
      if (isNaN(lat) || isNaN(lng)) return;

      const cor = p.estado_cor || '#D99A1E';

      // Ícone com marcador circular oficial Djumbai
      const customIcon = L.divIcon({
        className: 'custom-pin',
        html: `<div style="
          width: 22px; 
          height: 22px; 
          background: ${cor}; 
          border: 2px solid #FFF; 
          border-radius: 50%; 
          box-shadow: 0 2px 6px rgba(0,0,0,0.35);
          display: flex;
          align-items: center;
          justify-content: center;
          color: #FFF;
          font-size: 10px;
          font-weight: 800;
        "></div>`,
        iconSize: [22, 22],
        iconAnchor: [11, 11]
      });

      const popupContent = `
        <div style="font-family: inherit; font-size: 12px; max-width: 230px; line-height: 1.4;">
          <strong style="display: block; font-size: 13px; color: #111; margin-bottom: 4px;">${p.titulo}</strong>
          <span style="display: flex; align-items: center; gap: 4px; color: #666; margin-bottom: 6px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> ${p.bairro_nome || '—'}, ${p.municipio_nome || ''}</span>
          <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #EEE; padding-top: 6px;">
            <span style="font-weight: 700; color: ${cor};">${p.estado_nome}</span>
            <span style="color: #777; display: flex; align-items: center; gap: 4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg> ${p.total_confirmacoes} apoios</span>
          </div>
          <div style="margin-top: 8px;">
            <a href="<?= url('problemas/') ?>${p.id}" target="_blank" style="display: inline-block; padding: 4px 8px; background: #B4451F; color: #FFF; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 700;">Ver Ocorrência</a>
          </div>
        </div>
      `;

      const m = L.marker([lat, lng], { icon: customIcon }).bindPopup(popupContent).addTo(map);
      markers.push([lat, lng]);
    });

    if (markers.length > 0) {
      map.fitBounds(markers, { padding: [30, 30], maxZoom: 14 });
    }
  }

  // ── EVENT BINDINGS (CSP-compliant) ──────────────────────────────
  const btnPrint = document.getElementById('btnPrint');
  if (btnPrint) btnPrint.addEventListener('click', function(){ window.print(); });
  document.querySelectorAll('.js-quick-dispatch').forEach(b => b.addEventListener('click', function(){
    abrirDespachoRapido(this.dataset.id, this.dataset.titulo, parseInt(this.dataset.estadoId, 10));
  }));
  document.querySelectorAll('.js-close-quick-dispatch').forEach(b => b.addEventListener('click', fecharDespachoRapido));
});
</script>
