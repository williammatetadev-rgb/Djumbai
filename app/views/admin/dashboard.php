<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: admin/dashboard.php (Console Profissional)        ║
     ╚══════════════════════════════════════════════════════════╝ -->

<?php
$totalGeral   = (int) ($panorama['total'] ?? 0);
$pendentes    = (int) ($panorama['pendentes'] ?? 0);
$emAnalise    = (int) ($panorama['em_analise'] ?? 0);
$resolvidos   = (int) ($panorama['resolvidos'] ?? 0);
$rejeitados   = (int) ($panorama['rejeitados'] ?? 0);
?>

<!-- ── 1. CABEÇALHO DO DASHBOARD ───────────────────────────── -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
  <div>
    <span style="font-size: var(--font-xs); font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px;">
      Painel de Gestão Municipal
    </span>
    <h1 style="font-size: var(--font-2xl); font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1.1;">
      Centro de Despacho Comunitário
    </h1>
    <p style="color: var(--text-secondary); font-size: var(--font-sm); margin-top: 4px;">
      Monitorização em tempo real das vias, saneamento, água e iluminação em Angola.
    </p>
  </div>

  <div style="display: flex; gap: var(--space-3);">
    <a href="<?= url('admin/problemas?estado=1') ?>" class="btn btn--primary btn--sm">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Ver <?= $pendentes ?> Pendentes
    </a>
    <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm">
      Todas as Ocorrências →
    </a>
  </div>
</div>

<!-- ── 2. CARDS DE KPI (MÉTRICAS ELEVADAS) ─────────────────── -->
<div class="stats-grid" style="margin-bottom: var(--space-8);">

  <!-- Total de Ocorrências -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-baia);">
    <div class="stat-card__header">
      <span class="stat-card__label">Total Registado</span>
      <div class="stat-card__icon" style="background: var(--brand-baia-subtle); color: var(--brand-baia);">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--text-primary);" data-target="<?= $totalGeral ?>"><?= number_format($totalGeral) ?></div>
    <span style="font-size: var(--font-xs); color: var(--text-muted);">
      Em Luanda e Províncias
    </span>
  </div>

  <!-- Ocorrências Pendentes -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-dendem);">
    <div class="stat-card__header">
      <span class="stat-card__label">Pendentes de Ação</span>
      <div class="stat-card__icon" style="background: var(--brand-dendem-subtle); color: #8C6211;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: #8C6211;" data-target="<?= $pendentes ?>"><?= number_format($pendentes) ?></div>
    <span style="font-size: var(--font-xs); color: #8C6211; font-weight: 600;">
      Aguardam despacho inicial
    </span>
  </div>

  <!-- Em Análise / Brigada enviada -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-baia);">
    <div class="stat-card__header">
      <span class="stat-card__label">Em Intervenção</span>
      <div class="stat-card__icon" style="background: var(--brand-baia-subtle); color: var(--brand-baia);">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--brand-baia);" data-target="<?= $emAnalise ?>"><?= number_format($emAnalise) ?></div>
    <span style="font-size: var(--font-xs); color: var(--brand-baia); font-weight: 600;">
      Equipas técnicas acionadas
    </span>
  </div>

  <!-- Taxa de Resolução -->
  <div class="stat-card" style="border-left: 4px solid var(--brand-capim);">
    <div class="stat-card__header">
      <span class="stat-card__label">Taxa de Resolução</span>
      <div class="stat-card__icon" style="background: var(--brand-capim-subtle); color: var(--brand-capim);">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
    <div class="stat-card__value" style="color: var(--brand-capim);"><?= $taxaResolucao ?>%</div>
    <div style="width: 100%; height: 6px; background: var(--border-subtle); border-radius: 3px; overflow: hidden; margin-top: 4px;">
      <div style="width: <?= min(100, max(5, $taxaResolucao)) ?>%; height: 100%; background: var(--brand-capim); border-radius: 3px;"></div>
    </div>
  </div>

</div>

<!-- ── 3. PAINEL DE INTELIGÊNCIA CÍVICA & GRÁFICOS (2 COLUNAS) ── -->
<div style="display: grid; grid-template-columns: 1fr; gap: var(--space-6); margin-bottom: var(--space-8); @media(min-width:992px){grid-template-columns: 1.1fr 0.9fr;}">

  <!-- Distribuição por Categoria -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-5);">
      <div>
        <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary);">Ocorrências por Categoria</h2>
        <span style="font-size: var(--font-xs); color: var(--text-muted);">Volume relativo de queixas reportadas</span>
      </div>
      <span class="badge badge--analysis" style="font-size: 11px;">
        <?= count($distribuicaoCategorias) ?> Áreas
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

  <!-- Zonas Críticas em Luanda & Auditoria Recente -->
  <div style="display: flex; flex-direction: column; gap: var(--space-6);">

    <!-- Top Municípios -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
        <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary);">Zonas com Maior Demanda</h2>
        <span style="font-size: var(--font-xs); color: var(--text-muted);">Municípios</span>
      </div>

      <?php if (empty($distribuicaoMunicipios)): ?>
        <p style="font-size: var(--font-xs); color: var(--text-muted);">Nenhum dado geográfico acumulado.</p>
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
      <h2 style="font-size: var(--font-md); font-weight: 800; color: var(--text-primary); margin-bottom: var(--space-4);">
        Despachos Administrativos Recentes
      </h2>

      <?php if (empty($historicoAuditoria)): ?>
        <p style="font-size: var(--font-xs); color: var(--text-muted);">Nenhuma alteração de estado registada recentemente.</p>
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

<!-- ── 4. TABELA DE OCORRÊNCIAS RECENTES COM DESPACHO RÁPIDO ─ -->
<div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-5); flex-wrap: wrap; gap: var(--space-3);">
    <div>
      <h2 style="font-size: var(--font-lg); font-weight: 800; color: var(--text-primary);">Últimos Reportes Registados</h2>
      <p style="font-size: var(--font-xs); color: var(--text-secondary);">Fila de entrada para avaliação técnica e encaminhamento</p>
    </div>

    <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm">
      Gerir Todas as Ocorrências (<?= $totalGeral ?>) →
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
          <th style="padding: 12px 10px; text-align: right;">Ação de Despacho</th>
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
              <a href="<?= url('admin/problemas') ?>" class="btn btn--primary btn--sm" style="font-size: 11px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 4px;">
                <span>Despachar</span>
                <?= icon('settings', 12) ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>
