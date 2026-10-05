<?php
$nomeSite     = $settings['nome_site']           ?? 'Djumbai';
$slogan       = $settings['slogan']              ?? 'Plataforma Cívica Digital de Angola';
$emailSup     = $settings['email_suporte']       ?? 'suporte@djumbai.ao';
$telEmer      = $settings['telefone_emergencia'] ?? '+244 923 000 000';
$modoManu     = !empty($settings['modo_manutencao']);
$aprovAuto    = !empty($settings['aprovacao_automatica']);
$maxReportes  = (int) ($settings['max_reportes_hora'] ?? 10);
$regras       = $settings['regras_comunidade']   ?? '';
$atualizadoEm  = $settings['atualizado_em'] ?? null;
$atualizadoPor = $settings['atualizado_por'] ?? null;
?>

<div style="padding:28px;max-width:1000px;">

  <!-- ══ CABEÇALHO ═══════════════════════════════════════════════ -->
  <div style="margin-bottom:32px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div>
      <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0 0 4px;display:flex;align-items:center;gap:10px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#4A3728,#2D221A);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(217,154,30,0.3);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </div>
        Definições da Plataforma
      </h1>
      <p style="font-size:13px;color:var(--text-muted);margin:0;">Configurações globais, moderação e identidade do Djumbai.</p>
    </div>

    <?php if ($atualizadoEm): ?>
      <div style="background:rgba(63,90,60,0.08);border:1px solid rgba(63,90,60,0.2);border-radius:10px;padding:10px 16px;font-size:12px;color:var(--text-secondary);">
        <div style="font-weight:700;color:var(--brand-capim);margin-bottom:2px;">Última atualização</div>
        <div><?= dataAngolana($atualizadoEm) ?> por <?= e($atualizadoPor) ?></div>
      </div>
    <?php endif; ?>
  </div>

  <!-- ══ ALERTAS DE STATUS ════════════════════════════════════════ -->
  <?php if ($modoManu): ?>
    <div style="background:rgba(180,69,31,0.08);border:1px solid rgba(180,69,31,0.3);border-radius:12px;padding:14px 20px;margin-bottom:24px;display:flex;align-items:center;gap:12px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      <div>
        <strong style="color:var(--brand-terra);font-size:13px;">Modo de Manutenção ATIVO</strong>
        <p style="margin:0;font-size:12px;color:var(--text-secondary);">O portal público está a exibir uma mensagem de manutenção para os cidadãos.</p>
      </div>
    </div>
  <?php endif; ?>

  <form action="<?= url('admin/definicoes') ?>" method="POST">
    <?= csrfField() ?>
    <div style="display:flex;flex-direction:column;gap:20px;">

      <!-- ─── PAINEL 1: IDENTIDADE & CONTACTOS ─────────────── -->
      <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);overflow:hidden;">
        <div style="padding:18px 24px;background:var(--surface-sunken);border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;gap:10px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <h2 style="margin:0;font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.5px;">Identidade & Contactos</h2>
        </div>
        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
          <div>
            <label style="display:block;font-size:11.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:7px;">Nome da Plataforma</label>
            <input type="text" name="nome_site" class="form-input" value="<?= e($nomeSite) ?>" required>
          </div>
          <div>
            <label style="display:block;font-size:11.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:7px;">Slogan / Tagline</label>
            <input type="text" name="slogan" class="form-input" value="<?= e($slogan) ?>" required>
          </div>
          <div>
            <label style="display:block;font-size:11.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:7px;">E-mail de Suporte ao Cidadão</label>
            <input type="email" name="email_suporte" class="form-input" value="<?= e($emailSup) ?>" required>
          </div>
          <div>
            <label style="display:block;font-size:11.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:7px;">Linha de Emergência Municipal</label>
            <input type="text" name="telefone_emergencia" class="form-input" value="<?= e($telEmer) ?>" required>
          </div>
        </div>
      </div>

      <!-- ─── PAINEL 2: MODERAÇÃO & SEGURANÇA ────────────────── -->
      <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);overflow:hidden;">
        <div style="padding:18px 24px;background:var(--surface-sunken);border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;gap:10px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <h2 style="margin:0;font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.5px;">Moderação & Segurança</h2>
        </div>
        <div style="padding:24px;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">

            <!-- Toggle: Aprovação Automática -->
            <label for="toggleAprov" style="display:flex;align-items:flex-start;gap:14px;padding:18px;background:var(--surface-sunken);border-radius:12px;border:1px solid <?= $aprovAuto ? 'rgba(63,90,60,0.3)' : 'var(--border-subtle)' ?>;cursor:pointer;transition:border-color 0.2s;">
              <div style="position:relative;flex-shrink:0;margin-top:2px;">
                <input type="checkbox" id="toggleAprov" name="aprovacao_automatica" value="1" <?= $aprovAuto ? 'checked' : '' ?> style="position:absolute;opacity:0;width:0;height:0;" onchange="updateToggle('toggleAprov','trackAprov')">
                <div id="trackAprov" style="width:44px;height:24px;border-radius:12px;background:<?= $aprovAuto ? 'var(--brand-capim)' : 'var(--border-subtle)' ?>;transition:background 0.2s;position:relative;">
                  <div style="position:absolute;top:3px;left:<?= $aprovAuto ? '23px' : '3px' ?>;width:18px;height:18px;border-radius:50%;background:#FFF;box-shadow:0 1px 3px rgba(0,0,0,0.2);transition:left 0.2s;" id="thumbAprov"></div>
                </div>
              </div>
              <div>
                <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:4px;">Aprovação Automática de Reportes</div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.4;">Se ativo, as ocorrências ficam visíveis imediatamente. Se inativo, entram em fila de moderação prévia.</div>
              </div>
            </label>

            <!-- Toggle: Modo Manutenção -->
            <label for="toggleManu" style="display:flex;align-items:flex-start;gap:14px;padding:18px;background:<?= $modoManu ? 'rgba(180,69,31,0.05)' : 'var(--surface-sunken)' ?>;border-radius:12px;border:1px solid <?= $modoManu ? 'rgba(180,69,31,0.3)' : 'var(--border-subtle)' ?>;cursor:pointer;transition:border-color 0.2s;">
              <div style="position:relative;flex-shrink:0;margin-top:2px;">
                <input type="checkbox" id="toggleManu" name="modo_manutencao" value="1" <?= $modoManu ? 'checked' : '' ?> style="position:absolute;opacity:0;width:0;height:0;" onchange="updateToggle('toggleManu','trackManu')">
                <div id="trackManu" style="width:44px;height:24px;border-radius:12px;background:<?= $modoManu ? 'var(--brand-terra)' : 'var(--border-subtle)' ?>;transition:background 0.2s;position:relative;">
                  <div style="position:absolute;top:3px;left:<?= $modoManu ? '23px' : '3px' ?>;width:18px;height:18px;border-radius:50%;background:#FFF;box-shadow:0 1px 3px rgba(0,0,0,0.2);transition:left 0.2s;" id="thumbManu"></div>
                </div>
              </div>
              <div>
                <div style="font-size:13px;font-weight:700;color:<?= $modoManu ? 'var(--brand-terra)' : 'var(--text-primary)' ?>;margin-bottom:4px;">Modo de Manutenção</div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.4;">Exibe aviso de manutenção programada para os cidadãos no portal público.</div>
              </div>
            </label>
          </div>

          <!-- Limite de reportes -->
          <div style="display:flex;align-items:center;gap:20px;padding:18px;background:var(--surface-sunken);border-radius:12px;border:1px solid var(--border-subtle);">
            <div style="flex:1;">
              <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:3px;">Limite de Reportes por Hora</div>
              <div style="font-size:12px;color:var(--text-muted);">Número máximo de publicações que um utilizador/IP pode submeter por hora.</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
              <input type="number" name="max_reportes_hora" min="1" max="100" value="<?= $maxReportes ?>" style="width:80px;text-align:center;font-size:1.2rem;font-weight:800;color:var(--text-primary);border:2px solid var(--border-subtle);border-radius:8px;padding:8px;background:var(--surface-card);" required>
              <span style="font-size:12px;color:var(--text-muted);">/ hora</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ─── PAINEL 3: REGRAS DE CONDUTA ─────────────────────── -->
      <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);overflow:hidden;">
        <div style="padding:18px 24px;background:var(--surface-sunken);border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/></svg>
            <h2 style="margin:0;font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.5px;">Regras de Conduta da Comunidade</h2>
          </div>
          <span style="font-size:11px;color:var(--text-muted);">Enviado automaticamente nos avisos de bons modos</span>
        </div>
        <div style="padding:24px;">
          <textarea name="regras_comunidade" rows="4" style="width:100%;font-size:13.5px;color:var(--text-primary);line-height:1.6;border:1px solid var(--border-subtle);border-radius:10px;padding:14px 16px;background:var(--surface-sunken);resize:vertical;font-family:inherit;box-sizing:border-box;" placeholder="Defina aqui as regras de conduta e bom uso da plataforma..."><?= e($regras) ?></textarea>
        </div>
      </div>

      <!-- ─── BOTÃO GUARDAR ─────────────────────────────────────── -->
      <div style="display:flex;justify-content:flex-end;gap:12px;align-items:center;padding-top:4px;">
        <span style="font-size:12px;color:var(--text-muted);">As alterações são guardadas imediatamente e registadas em auditoria.</span>
        <button type="submit" style="display:flex;align-items:center;gap:8px;background:var(--brand-terra);color:#FFF;padding:12px 28px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(180,69,31,0.3);transition:all 0.2s;" onmouseenter="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(180,69,31,0.4)'" onmouseleave="this.style.transform='';this.style.boxShadow='0 4px 12px rgba(180,69,31,0.3)'">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Guardar Definições
        </button>
      </div>

    </div>
  </form>
</div>

<script>
function updateToggle(inputId, trackId) {
  const input = document.getElementById(inputId);
  const track = document.getElementById(trackId);
  const thumb = track.querySelector('div');
  if (input.checked) {
    track.style.background = inputId === 'toggleManu' ? 'var(--brand-terra)' : 'var(--brand-capim)';
    thumb.style.left = '23px';
  } else {
    track.style.background = 'var(--border-subtle)';
    thumb.style.left = '3px';
  }
}
</script>
