<?php
// ─── Calcular nº de não lidas ───────────────────────────────────
$naoLidas = count(array_filter($notificacoes, fn($n) => !(int)$n['lida']));
$biStatus = $perfil['bi_status'] ?? 'nao_enviado';
$temBi    = !empty($perfil['bi_frente']) && !empty($perfil['bi_verso']);
if ($temBi && $biStatus === 'nao_enviado') {
    $biStatus = 'pendente';
}
?>

<!-- ═══════════════════════════════════════════════════════════════
     HERO — Cabeçalho do Perfil & Score Cívico
═══════════════════════════════════════════════════════════════ -->
<section style="background: linear-gradient(135deg, #1A1410 0%, #2D221A 60%, #3A2D20 100%); padding: 36px 0 0; border-bottom: 3px solid var(--brand-dendem); position: relative; overflow: hidden;">

  <!-- Padrão decorativo Samakaka subtil -->
  <div style="position:absolute;inset:0;opacity:0.04;background-image:repeating-linear-gradient(60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px),repeating-linear-gradient(-60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px);pointer-events:none;"></div>

  <div class="container" style="position:relative;z-index:2;">
    <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px;padding-bottom:28px;">

      <!-- Avatar + Info + Badges -->
      <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div style="position:relative;">
          <div style="width:76px;height:76px;border-radius:50%;background:linear-gradient(135deg,var(--brand-terra),var(--brand-dendem));display:flex;align-items:center;justify-content:center;font-size:2.1rem;font-weight:800;color:#FFF;border:3px solid rgba(217,154,30,0.4);box-shadow:0 8px 24px rgba(0,0,0,0.4);">
            <?= strtoupper(substr($perfil['nome'], 0, 1)) ?>
          </div>
          <?php if ($biStatus === 'verificado'): ?>
            <div title="Identidade Civil Verificada" style="position:absolute;bottom:0px;right:0px;width:22px;height:22px;border-radius:50%;background:#4CAF50;border:2px solid #1A1410;display:flex;align-items:center;justify-content:center;color:#FFF;font-size:12px;font-weight:900;">✓</div>
          <?php else: ?>
            <div style="position:absolute;bottom:2px;right:2px;width:16px;height:16px;border-radius:50%;background:#4CAF50;border:2px solid #1A1410;"></div>
          <?php endif; ?>
        </div>
        <div>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
            <h1 style="font-family:var(--font-display);font-size:1.75rem;font-weight:800;color:#FFF;margin:0;line-height:1;"><?= e($perfil['nome']) ?></h1>
            
            <?php if ($biStatus === 'verificado'): ?>
              <span style="background:rgba(76,175,80,0.2);border:1px solid rgba(76,175,80,0.6);color:#81C784;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;display:inline-flex;align-items:center;gap:4px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                Selo Verificado
              </span>
            <?php elseif ($biStatus === 'pendente'): ?>
              <span style="background:rgba(217,154,30,0.2);border:1px solid rgba(217,154,30,0.6);color:var(--brand-dendem);font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">
                BI em Análise ⏳
              </span>
            <?php elseif ($biStatus === 'rejeitado'): ?>
              <span style="background:rgba(229,57,53,0.2);border:1px solid rgba(229,57,53,0.6);color:#EF5350;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">
                BI Rejeitado ✗
              </span>
            <?php endif; ?>

            <span style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.8);font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">
              <?= $perfil['tipo'] === 'admin' ? 'Administrador' : 'Morador Ativo' ?>
            </span>
          </div>
          <p style="font-size:13px;color:rgba(255,251,245,0.65);margin:0;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:4px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($perfil['bairro'] ?? '—') ?>, <?= e($perfil['municipio'] ?? '—') ?>
            </span>
            <span>·</span>
            <span><?= e($perfil['email']) ?></span>
            <span>·</span>
            <span>Membro desde <?= dataAngolana($perfil['criado_em']) ?></span>
          </p>
        </div>
      </div>

      <!-- Botões Rápidos -->
      <div style="display:flex;gap:10px;align-items:center;">
        <?php if ($naoLidas > 0): ?>
            <div style="position:relative;">
              <a href="#notificacoes" style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#FFF;padding:9px 14px;border-radius:9px;text-decoration:none;font-size:13px;font-weight:600;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notificações
              </a>
              <span style="position:absolute;top:-6px;right:-6px;background:var(--brand-terra);color:#FFF;width:18px;height:18px;border-radius:50%;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #1A1410;"><?= $naoLidas ?></span>
            </div>
          <?php endif; ?>

          <a href="<?= url('perfil/definicoes') ?>" style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);color:#FFF;padding:9px 14px;border-radius:9px;text-decoration:none;font-size:13px;font-weight:600;" title="Definições da conta">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Definições
          </a>

          <a href="<?= url('reportar') ?>" style="display:flex;align-items:center;gap:6px;background:var(--brand-terra);color:#FFF;padding:10px 18px;border-radius:9px;text-decoration:none;font-size:13px;font-weight:700;box-shadow:0 4px 14px rgba(180,69,31,0.35);transition:filter 0.15s;" onmouseenter="this.style.filter='brightness(1.08)'" onmouseleave="this.style.filter='none'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Reporte
          </a>
        </div>
      </div>
    </div>

    <!-- 5 KPIs integrados no hero -->
    <div style="display:grid;grid-template-columns:repeat(5,1fr);background:rgba(0,0,0,0.3);border-top:1px solid rgba(255,255,255,0.08);border-radius:0;">
      <?php
      $kpis = [
        ['label' => 'Total Submetidos', 'value' => $totalReportes,    'color' => '#FFF',                'icon' => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2'],
        ['label' => 'Resolvidos',       'value' => $totalResolvidos,   'color' => '#81C784',             'icon' => 'M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4 12 14.01l-3-3'],
        ['label' => 'Em Análise',       'value' => $totalEmAnalise,    'color' => '#4FC3F7',             'icon' => 'M12 2v10l4 2M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z'],
        ['label' => 'Pendentes',        'value' => $totalPendentes,    'color' => 'var(--brand-dendem)', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z'],
        ['label' => 'Apoios Moradores', 'value' => $totalApoios,       'color' => '#FFB74D',             'icon' => 'M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3'],
      ];
      foreach ($kpis as $i => $k): ?>
        <div style="padding:18px 20px;<?= $i < 4 ? 'border-right:1px solid rgba(255,255,255,0.08);' : '' ?>">
          <div style="font-size:10.5px;color:rgba(255,251,245,0.5);text-transform:uppercase;letter-spacing:0.7px;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="<?= $k['color'] ?>" stroke-width="2"><path d="<?= $k['icon'] ?>"/></svg>
            <?= $k['label'] ?>
          </div>
          <div style="font-size:1.8rem;font-weight:800;color:<?= $k['color'] ?>;line-height:1;"><?= $k['value'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL: OCORRÊNCIAS + NOTIFICAÇÕES
═══════════════════════════════════════════════════════════════ -->
<div class="container" style="padding-block:36px;">

  <!-- Banner de Ação de BI se não estiver verificado -->
  <?php if ($biStatus !== 'verificado'): ?>
    <div style="background:<?= $biStatus === 'rejeitado' ? 'rgba(229,57,53,0.08)' : 'rgba(217,154,30,0.1)' ?>;border:1px solid <?= $biStatus === 'rejeitado' ? 'rgba(229,57,53,0.3)' : 'rgba(217,154,30,0.3)' ?>;border-radius:14px;padding:16px 20px;margin-bottom:28px;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:10px;background:<?= $biStatus === 'rejeitado' ? 'rgba(229,57,53,0.15)' : 'rgba(217,154,30,0.2)' ?>;display:flex;align-items:center;justify-content:center;color:<?= $biStatus === 'rejeitado' ? '#C62828' : 'var(--brand-dendem)' ?>;flex-shrink:0;">
          <?php if ($biStatus === 'rejeitado'): ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          <?php else: ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
          <?php endif; ?>
        </div>
        <div>
          <strong style="font-size:13.5px;color:var(--text-primary);display:block;">
            <?= $biStatus === 'rejeitado' ? 'Atenção: A sua verificação de BI foi rejeitada' : ($biStatus === 'pendente' ? 'Documento em Análise pela Administração' : 'Complete o seu Perfil com o Bilhete de Identidade') ?>
          </strong>
          <span style="font-size:12.5px;color:var(--text-secondary);">
            <?= $biStatus === 'rejeitado' ? (!empty($perfil['bi_nota']) ? 'Motivo: "' . e($perfil['bi_nota']) . '"' : 'Por favor reenvie imagens legíveis nas definições.') : ($biStatus === 'pendente' ? 'Aguarde a validação para desbloquear o Selo Cívico.' : 'Adicione o seu BI para aumentar a prioridade dos seus reportes nos serviços municipais.') ?>
          </span>
        </div>
      </div>
      <a href="<?= url('perfil/definicoes') ?>" style="display:inline-flex;align-items:center;gap:6px;background:var(--surface-card);border:1px solid var(--border-subtle);color:var(--text-primary);padding:8px 16px;border-radius:9px;font-size:12.5px;font-weight:700;text-decoration:none;box-shadow:var(--shadow-sm);transition:all 0.15s;">
        <?= $biStatus === 'rejeitado' ? 'Reenviar Documento' : ($biStatus === 'pendente' ? 'Ver Estado' : 'Verificar Agora') ?>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 360px;gap:28px;align-items:start;">

    <!-- ── Coluna 1: Minhas Ocorrências ───────────────────────── -->
    <div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);margin:0;">
          Minhas Ocorrências Submetidas
        </h2>
        <span style="font-size:11px;font-weight:700;color:var(--text-muted);background:var(--surface-sunken);padding:4px 12px;border-radius:12px;border:1px solid var(--border-subtle);"><?= count($meusReportes) ?> registos</span>
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
        <div style="display:flex;flex-direction:column;gap:14px;">
          <?php foreach ($meusReportes as $rep):
            $corEstado = $rep['estado_cor'] ?? '#6B5A4A'; ?>
            <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);padding:20px;transition:all 0.2s;box-shadow:var(--shadow-sm);" onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'" onmouseleave="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
                <div style="flex:1;min-width:0;">
                  <!-- Chips de estado e categoria -->
                  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                    <span style="background:<?= e($corEstado) ?>22;color:<?= e($corEstado) ?>;border:1px solid <?= e($corEstado) ?>55;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">
                      <?= e($rep['estado_nome']) ?>
                    </span>
                    <span style="background:var(--surface-sunken);color:var(--text-muted);font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;border:1px solid var(--border-subtle);">
                      <?= e($rep['categoria_nome']) ?>
                    </span>
                  </div>

                  <!-- Título -->
                  <a href="<?= url('problemas/' . $rep['id']) ?>" style="font-weight:700;color:var(--text-primary);font-size:1rem;text-decoration:none;display:block;margin-bottom:10px;line-height:1.35;">
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
                <a href="<?= url('problemas/' . $rep['id']) ?>" style="flex-shrink:0;display:flex;align-items:center;gap:5px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);padding:8px 16px;border-radius:9px;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;transition:all 0.15s;" onmouseenter="this.style.background='var(--brand-terra)';this.style.color='#FFF';this.style.borderColor='var(--brand-terra)'" onmouseleave="this.style.background='var(--surface-sunken)';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-subtle)'">
                  Ver Detalhes
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Coluna 2: Notificações com Ler Todas ─────────────────── -->
    <div id="notificacoes" style="position:sticky;top:80px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);margin:0;">Notificações</h2>
          <?php if ($naoLidas > 0): ?>
            <span style="background:var(--brand-terra);color:#FFF;font-size:10.5px;font-weight:800;padding:2px 8px;border-radius:20px;"><?= $naoLidas ?> nova<?= $naoLidas > 1 ? 's' : '' ?></span>
          <?php endif; ?>
        </div>
        
        <?php if ($naoLidas > 0): ?>
          <form action="<?= url('notificacoes/ler-todas') ?>" method="POST" style="margin:0;">
            <?= csrfField() ?>
            <button type="submit" style="background:none;border:none;color:var(--brand-terra);font-size:11.5px;font-weight:700;cursor:pointer;padding:0;text-decoration:underline;">Marcar todas lidas</button>
          </form>
        <?php else: ?>
          <span style="background:var(--surface-sunken);color:var(--text-muted);font-size:11px;padding:3px 10px;border-radius:20px;border:1px solid var(--border-subtle);">Todas lidas</span>
        <?php endif; ?>
      </div>

      <div style="background:var(--surface-card);border-radius:16px;border:1px solid var(--border-subtle);overflow:hidden;box-shadow:var(--shadow-sm);">
        <?php if (empty($notificacoes)): ?>
          <div style="padding:44px 24px;text-align:center;">
            <div style="width:48px;height:48px;border-radius:50%;background:var(--surface-sunken);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;color:var(--text-muted);">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin:0;">Nenhuma notificação recente.</p>
          </div>
        <?php else: ?>
          <div style="max-height:560px;overflow-y:auto;">
            <?php foreach ($notificacoes as $i => $notif):
              $isLida = (int)$notif['lida'];
              $isWarning = str_contains($notif['mensagem'], 'Bons Modos') || str_contains($notif['mensagem'], 'Notificação') || str_contains($notif['mensagem'], 'rejeitada');
              $isSuccess = str_contains($notif['mensagem'], 'verificado com sucesso') || str_contains($notif['mensagem'], 'Resolvido');
            ?>
              <div style="padding:16px 18px;<?= $i < count($notificacoes)-1 ? 'border-bottom:1px solid var(--border-subtle);' : '' ?>background:<?= !$isLida ? ($isWarning ? '#FFFBF0' : ($isSuccess ? '#F1F8E9' : '#F0F7FF')) : 'transparent' ?>;">
                <!-- Ícone + mensagem -->
                <div style="display:flex;gap:12px;align-items:flex-start;">
                  <div style="width:32px;height:32px;border-radius:50%;background:<?= $isWarning ? 'rgba(217,154,30,0.15)' : ($isSuccess ? 'rgba(76,175,80,0.15)' : 'rgba(31,111,107,0.12)') ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <?php if ($isWarning): ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <?php elseif ($isSuccess): ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php else: ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <?php endif; ?>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <p style="margin:0 0 6px;font-size:12.5px;color:var(--text-primary);line-height:1.45;"><?= e($notif['mensagem']) ?></p>
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
