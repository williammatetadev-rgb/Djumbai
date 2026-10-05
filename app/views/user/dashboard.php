<?php
// ─── Calcular nº de não lidas ───────────────────────────────────
$naoLidas = count(array_filter($notificacoes, fn($n) => !(int)$n['lida']));
?>

<!-- ═══════════════════════════════════════════════════════════════
     HERO — Cabeçalho do Perfil
═══════════════════════════════════════════════════════════════ -->
<section style="background: linear-gradient(135deg, #1A1410 0%, #2D221A 60%, #3A2D20 100%); padding: 40px 0 0; border-bottom: 3px solid var(--brand-dendem); position: relative; overflow: hidden;">

  <!-- Padrão decorativo Samakaka subtil -->
  <div style="position:absolute;inset:0;opacity:0.04;background-image:repeating-linear-gradient(60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px),repeating-linear-gradient(-60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px);pointer-events:none;"></div>

  <div class="container" style="position:relative;z-index:2;">
    <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px;padding-bottom:32px;">

      <!-- Avatar + Info -->
      <div style="display:flex;align-items:center;gap:20px;">
        <div style="position:relative;">
          <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--brand-terra),var(--brand-dendem));display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#FFF;border:3px solid rgba(217,154,30,0.4);box-shadow:0 8px 24px rgba(0,0,0,0.4);">
            <?= strtoupper(substr($perfil['nome'], 0, 1)) ?>
          </div>
          <div style="position:absolute;bottom:2px;right:2px;width:16px;height:16px;border-radius:50%;background:#4CAF50;border:2px solid #1A1410;"></div>
        </div>
        <div>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
            <h1 style="font-family:var(--font-display);font-size:1.75rem;font-weight:800;color:#FFF;margin:0;line-height:1;"><?= e($perfil['nome']) ?></h1>
            <span style="background:rgba(76,175,80,0.2);border:1px solid rgba(76,175,80,0.5);color:#81C784;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">Morador Ativo</span>
          </div>
          <p style="font-size:13px;color:rgba(255,251,245,0.6);margin:0;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:4px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($perfil['bairro'] ?? '—') ?>, <?= e($perfil['municipio'] ?? '—') ?>
            </span>
            <span>·</span>
            <span><?= e($perfil['email']) ?></span>
            <span>·</span>
            <span>Registado em <?= dataAngolana($perfil['criado_em']) ?></span>
          </p>
        </div>
      </div>

      <!-- Botões -->
      <div style="display:flex;gap:10px;align-items:center;">
        <?php if ($naoLidas > 0): ?>
          <div style="position:relative;">
            <a href="#notificacoes" style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#FFF;padding:8px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              Notificações
            </a>
            <span style="position:absolute;top:-6px;right:-6px;background:var(--brand-terra);color:#FFF;width:18px;height:18px;border-radius:50%;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #1A1410;"><?= $naoLidas ?></span>
          </div>
        <?php endif; ?>
        <a href="<?= url('perfil/definicoes') ?>" style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);color:#FFF;padding:9px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;" title="Editar dados da conta">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          Definições
        </a>
        <a href="<?= url('reportar') ?>" style="display:flex;align-items:center;gap:6px;background:var(--brand-terra);color:#FFF;padding:10px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:700;box-shadow:0 4px 12px rgba(180,69,31,0.35);">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Novo Reporte
        </a>
      </div>
    </div>

    <!-- KPIs integrados no hero -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);background:rgba(0,0,0,0.25);border-top:1px solid rgba(255,255,255,0.07);border-radius:0;">
      <?php
      $kpis = [
        ['label' => 'Reportes Submetidos', 'value' => $totalReportes,   'color' => '#FFF',                'icon' => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2'],
        ['label' => 'Resolvidos',          'value' => $totalResolvidos,  'color' => '#81C784',             'icon' => 'M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4 12 14.01l-3-3'],
        ['label' => 'Em Análise',          'value' => $totalEmAnalise,   'color' => '#4FC3F7',             'icon' => 'M12 2v10l4 2M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z'],
        ['label' => 'Apoios Recebidos',    'value' => $totalApoios,      'color' => 'var(--brand-dendem)', 'icon' => 'M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3'],
      ];
      foreach ($kpis as $i => $k): ?>
        <div style="padding:20px 24px;<?= $i < 3 ? 'border-right:1px solid rgba(255,255,255,0.07);' : '' ?>">
          <div style="font-size:11px;color:rgba(255,251,245,0.45);text-transform:uppercase;letter-spacing:0.7px;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="<?= $k['color'] ?>" stroke-width="2"><path d="<?= $k['icon'] ?>"/></svg>
            <?= $k['label'] ?>
          </div>
          <div style="font-size:1.9rem;font-weight:800;color:<?= $k['color'] ?>;line-height:1;"><?= $k['value'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL
═══════════════════════════════════════════════════════════════ -->
<div class="container" style="padding-block:36px;">
  <div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">

    <!-- ── Coluna 1: Minhas Ocorrências ───────────────────────── -->
    <div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin:0;">
          Minhas Ocorrências Reportadas
        </h2>
        <span style="font-size:11px;color:var(--text-muted);background:var(--surface-sunken);padding:3px 10px;border-radius:10px;border:1px solid var(--border-subtle);"><?= count($meusReportes) ?> registos</span>
      </div>

      <?php if (empty($meusReportes)): ?>
        <div style="background:var(--surface-card);border-radius:16px;border:1px solid var(--border-subtle);padding:56px 32px;text-align:center;">
          <div style="width:60px;height:60px;border-radius:50%;background:var(--surface-sunken);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/></svg>
          </div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 6px;">Ainda não submeteu nenhum reporte</h3>
          <p style="font-size:13px;color:var(--text-muted);margin:0 0 20px;max-width:320px;margin-inline:auto;line-height:1.5;">Ajude a melhorar o seu bairro reportando faltas de água, buracos ou falhas elétricas.</p>
          <a href="<?= url('reportar') ?>" class="btn btn--primary btn--sm">Submeter Primeiro Reporte</a>
        </div>
      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
          <?php foreach ($meusReportes as $rep):
            $corEstado = $rep['estado_cor'] ?? '#6B5A4A'; ?>
            <div style="background:var(--surface-card);border-radius:12px;border:1px solid var(--border-subtle);padding:20px;transition:box-shadow 0.2s;" onmouseenter="this.style.boxShadow='var(--shadow-md)'" onmouseleave="this.style.boxShadow='none'">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
                <div style="flex:1;min-width:0;">
                  <!-- Chips de estado e categoria -->
                  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                    <span style="background:<?= e($corEstado) ?>22;color:<?= e($corEstado) ?>;border:1px solid <?= e($corEstado) ?>55;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">
                      <?= e($rep['estado_nome']) ?>
                    </span>
                    <span style="background:var(--surface-sunken);color:var(--text-muted);font-size:11px;padding:3px 10px;border-radius:20px;border:1px solid var(--border-subtle);">
                      <?= e($rep['categoria_nome']) ?>
                    </span>
                  </div>

                  <!-- Título -->
                  <a href="<?= url('problemas/' . $rep['id']) ?>" style="font-weight:700;color:var(--text-primary);font-size:0.95rem;text-decoration:none;display:block;margin-bottom:10px;line-height:1.3;">
                    <?= e($rep['titulo']) ?>
                  </a>

                  <!-- Meta -->
                  <div style="display:flex;gap:18px;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:var(--text-muted);">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                      <?= e($rep['bairro_nome'] ?? '—') ?>
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:var(--text-muted);">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                      <?= (int)$rep['total_confirmacoes'] ?> apoios
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:var(--text-muted);">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                      <?= dataAngolana($rep['criado_em']) ?>
                    </span>
                  </div>
                </div>

                <!-- Botão -->
                <a href="<?= url('problemas/' . $rep['id']) ?>" style="flex-shrink:0;display:flex;align-items:center;gap:5px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;white-space:nowrap;transition:all 0.15s;" onmouseenter="this.style.background='var(--brand-terra)';this.style.color='#FFF';this.style.borderColor='var(--brand-terra)'" onmouseleave="this.style.background='var(--surface-sunken)';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-subtle)'">
                  Ver Detalhes
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Coluna 2: Notificações ─────────────────────────────── -->
    <div id="notificacoes" style="position:sticky;top:80px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin:0;">Notificações</h2>
        <?php if ($naoLidas > 0): ?>
          <span style="background:var(--brand-terra);color:#FFF;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;"><?= $naoLidas ?> nova<?= $naoLidas > 1 ? 's' : '' ?></span>
        <?php else: ?>
          <span style="background:var(--surface-sunken);color:var(--text-muted);font-size:11px;padding:3px 10px;border-radius:20px;border:1px solid var(--border-subtle);">Todas lidas</span>
        <?php endif; ?>
      </div>

      <div style="background:var(--surface-card);border-radius:16px;border:1px solid var(--border-subtle);overflow:hidden;">
        <?php if (empty($notificacoes)): ?>
          <div style="padding:40px 24px;text-align:center;">
            <div style="width:44px;height:44px;border-radius:50%;background:var(--surface-sunken);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin:0;">Nenhuma notificação recente.</p>
          </div>
        <?php else: ?>
          <div style="max-height:520px;overflow-y:auto;">
            <?php foreach ($notificacoes as $i => $notif):
              $isLida = (int)$notif['lida'];
              $isWarning = str_contains($notif['mensagem'], 'Bons Modos') || str_contains($notif['mensagem'], 'Notificação'); ?>
              <div style="padding:16px 20px;<?= $i < count($notificacoes)-1 ? 'border-bottom:1px solid var(--border-subtle);' : '' ?>background:<?= !$isLida ? ($isWarning ? '#FFFBF0' : '#F0F7FF') : 'transparent' ?>;">
                <!-- Ícone + mensagem -->
                <div style="display:flex;gap:12px;align-items:flex-start;">
                  <div style="width:32px;height:32px;border-radius:50%;background:<?= $isWarning ? 'rgba(217,154,30,0.12)' : 'rgba(31,111,107,0.1)' ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <?php if ($isWarning): ?>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <?php else: ?>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <?php endif; ?>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <p style="margin:0 0 6px;font-size:13px;color:var(--text-primary);line-height:1.45;"><?= e($notif['mensagem']) ?></p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                      <span style="font-size:11px;color:var(--text-muted);"><?= dataAngolana($notif['criado_em']) ?></span>
                      <?php if (!$isLida): ?>
                        <form action="<?= url('notificacoes/' . $notif['id'] . '/ler') ?>" method="POST" style="margin:0;">
                          <?= csrfField() ?>
                          <button type="submit" style="background:none;border:none;color:var(--brand-terra);font-size:11px;font-weight:700;cursor:pointer;padding:0;">Marcar lida</button>
                        </form>
                      <?php else: ?>
                        <span style="display:flex;align-items:center;gap:3px;font-size:11px;color:#4CAF50;">
                          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                          Lida
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
