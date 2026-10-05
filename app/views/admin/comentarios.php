<?php
$total = count($comentarios);
?>

<div style="padding:28px;">

  <!-- ══ CABEÇALHO ═══════════════════════════════════════════════ -->
  <div style="margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
      <div>
        <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0 0 4px;display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--brand-baia),#155E5A);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          Moderação de Comentários
        </h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0;">Analise e modere todos os comentários publicados pelos moradores nas ocorrências.</p>
      </div>

      <div style="display:flex;align-items:center;gap:10px;">
        <div style="background:var(--surface-sunken);border:1px solid var(--border-subtle);border-radius:10px;padding:8px 16px;display:flex;align-items:center;gap:8px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <span style="font-size:13px;font-weight:700;color:var(--text-primary);"><?= $total ?></span>
          <span style="font-size:12px;color:var(--text-muted);">comentário<?= $total !== 1 ? 's' : '' ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ LISTA DE COMENTÁRIOS ════════════════════════════════════ -->
  <?php if (empty($comentarios)): ?>
    <div style="background:var(--surface-card);border-radius:16px;border:1px solid var(--border-subtle);padding:72px 32px;text-align:center;">
      <div style="width:64px;height:64px;border-radius:50%;background:var(--surface-sunken);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 6px;">Nenhum comentário registado</h3>
      <p style="font-size:13px;color:var(--text-muted);margin:0;">Os comentários submetidos pelos cidadãos aparecerão aqui para moderação.</p>
    </div>

  <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:12px;">
      <?php foreach ($comentarios as $c):
        $isBannedAuthor = ($c['autor_status'] ?? 'ativo') === 'banido';
      ?>
        <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);overflow:hidden;transition:box-shadow 0.2s;" onmouseenter="this.style.boxShadow='var(--shadow-md)'" onmouseleave="this.style.boxShadow='none'">
          <div style="display:grid;grid-template-columns:1fr auto;gap:0;">

            <!-- Conteúdo principal -->
            <div style="padding:20px 24px;">

              <!-- Meta header: autor + data -->
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <!-- Avatar -->
                <div style="width:38px;height:38px;border-radius:50%;background:<?= $isBannedAuthor ? 'var(--brand-terra)' : 'var(--brand-baia)' ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#FFF;flex-shrink:0;">
                  <?= strtoupper(substr($c['autor_nome'], 0, 1)) ?>
                </div>
                <div style="flex:1;min-width:0;">
                  <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <strong style="font-size:13.5px;color:var(--text-primary);"><?= e($c['autor_nome']) ?></strong>
                    <?php if ($isBannedAuthor): ?>
                      <span style="background:rgba(180,69,31,0.1);border:1px solid rgba(180,69,31,0.25);color:var(--brand-terra);font-size:10px;font-weight:700;padding:1px 7px;border-radius:10px;">Suspenso</span>
                    <?php endif; ?>
                    <span style="color:var(--text-muted);font-size:11px;"><?= e($c['autor_email']) ?></span>
                  </div>
                  <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
                    <span style="font-size:11px;color:var(--text-muted);">
                      <?= dataAngolana($c['criado_em']) ?>
                    </span>
                    <span style="color:var(--border-subtle);">·</span>
                    <span style="font-size:11px;">
                      Em:&nbsp;<a href="<?= url('problemas/' . $c['problema_id']) ?>" target="_blank" style="color:var(--brand-terra);font-weight:600;text-decoration:none;"><?= e($c['problema_titulo']) ?></a>
                    </span>
                  </div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;margin-left:auto;"></div>
              </div>

              <!-- Texto do comentário -->
              <div style="background:var(--surface-sunken);border-radius:10px;border-left:3px solid var(--brand-baia);padding:14px 16px;font-size:13.5px;color:var(--text-primary);line-height:1.55;">
                <?= nl2br(e($c['texto'])) ?>
              </div>
            </div>

            <!-- Painel de ação -->
            <div style="background:var(--surface-sunken);border-left:1px solid var(--border-subtle);padding:20px 18px;display:flex;flex-direction:column;justify-content:center;align-items:center;gap:10px;min-width:140px;">
              <a href="<?= url('problemas/' . $c['problema_id']) ?>" target="_blank" style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--brand-baia);text-decoration:none;padding:6px 12px;border-radius:7px;border:1px solid rgba(31,111,107,0.25);background:rgba(31,111,107,0.06);width:100%;justify-content:center;transition:all 0.15s;" onmouseenter="this.style.background='var(--brand-baia)';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(31,111,107,0.06)';this.style.color='var(--brand-baia)'">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Ver Ocorrência
              </a>

              <form action="<?= url('admin/comentarios/' . $c['id'] . '/eliminar') ?>" method="POST" style="margin:0;width:100%;">
                <?= csrfField() ?>
                <button type="submit" style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--brand-terra);padding:6px 12px;border-radius:7px;border:1px solid rgba(180,69,31,0.25);background:rgba(180,69,31,0.06);width:100%;justify-content:center;cursor:pointer;transition:all 0.15s;" onmouseenter="this.style.background='var(--brand-terra)';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(180,69,31,0.06)';this.style.color='var(--brand-terra)'">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  Eliminar
                </button>
              </form>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
