<?php
// Filtrar admins - apenas cidadãos aparecem nesta lista
$cidadaos = array_filter($utilizadores, fn($u) => $u['tipo'] !== 'admin');
$totalCidadaos = count($cidadaos);
$totalAtivos   = count(array_filter($cidadaos, fn($u) => ($u['status'] ?? 'ativo') === 'ativo'));
$totalBanidos  = $totalCidadaos - $totalAtivos;
$totalOnline   = count(array_filter($cidadaos, fn($u) => !empty($u['ultimo_acesso']) && strtotime($u['ultimo_acesso']) >= strtotime('-15 minutes')));
?>

<div style="padding:28px;">

  <!-- ══ CABEÇALHO ═══════════════════════════════════════════════ -->
  <div style="margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
      <div>
        <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0 0 4px;display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--brand-terra),#C0392B);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          Gestão de Utilizadores
        </h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0;">Monitorize membros, gerencie estados de conta e emita avisos de conduta cívica.</p>
      </div>
    </div>

    <!-- Estatísticas rápidas -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:20px;">
      <?php foreach ([
        ['label' => 'Total de Membros', 'value' => $totalCidadaos, 'color' => 'var(--brand-baia)',   'bg' => 'rgba(31,111,107,0.08)', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M12 7a4 4 0 1 1 0 8 4 4 0 0 1 0-8z'],
        ['label' => 'Membros Ativos',   'value' => $totalAtivos,   'color' => '#4CAF50',             'bg' => 'rgba(76,175,80,0.08)',  'icon' => 'M22 11.08V12a10 10 0 1 1-5.93-9.14 M22 4 12 14.01l-3-3'],
        ['label' => 'Suspensos',        'value' => $totalBanidos,  'color' => 'var(--brand-terra)',   'bg' => 'rgba(180,69,31,0.08)', 'icon' => 'M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636'],
        ['label' => 'Online Agora',     'value' => $totalOnline,   'color' => 'var(--brand-dendem)',  'bg' => 'rgba(217,154,30,0.08)', 'icon' => 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72'],
      ] as $stat): ?>
        <div style="background:<?= $stat['bg'] ?>;border:1px solid <?= $stat['color'] ?>33;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;">
          <div style="width:40px;height:40px;background:<?= $stat['color'] ?>22;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="<?= $stat['color'] ?>" stroke-width="2"><path d="<?= $stat['icon'] ?>"/></svg>
          </div>
          <div>
            <div style="font-size:1.5rem;font-weight:800;color:<?= $stat['color'] ?>;line-height:1;"><?= $stat['value'] ?></div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;"><?= $stat['label'] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ══ TABELA DE CIDADÃOS ═══════════════════════════════════════ -->
  <div style="background:var(--surface-card);border-radius:14px;border:1px solid var(--border-subtle);box-shadow:var(--shadow-sm);overflow:hidden;">

    <?php if (empty($cidadaos)): ?>
      <div style="text-align:center;padding:56px 24px;color:var(--text-muted);">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--surface-sunken);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p style="font-weight:700;font-size:0.95rem;color:var(--text-primary);margin:0 0 6px;">Nenhum cidadão registado</p>
        <p style="font-size:13px;margin:0;">Os membros que criarem conta aparecerão aqui.</p>
      </div>
    <?php else: ?>

      <!-- Cabeçalho da tabela -->
      <div style="display:grid;grid-template-columns:2.2fr 1.1fr 1.1fr 1.3fr 0.8fr 2.1fr;gap:0;padding:10px 20px;background:var(--surface-sunken);border-bottom:1px solid var(--border-subtle);">
        <?php foreach (['Membro', 'Localização', 'Contribuição', 'Último Acesso', 'Estado', 'Moderação'] as $header): ?>
          <span style="font-size:10.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.6px;"><?= $header ?></span>
        <?php endforeach; ?>
      </div>

      <!-- Linhas -->
      <?php foreach ($cidadaos as $u):
        $isBanned = ($u['status'] ?? 'ativo') === 'banido';
        $isOnline = !empty($u['ultimo_acesso']) && strtotime($u['ultimo_acesso']) >= strtotime('-15 minutes');
        $avatarBg = $isBanned ? '#B4451F' : (['#1F6F6B','#3F5A3C','#6B5A4A','#D99A1E'][crc32($u['email']) % 4]);
      ?>
        <div style="display:grid;grid-template-columns:2.2fr 1.1fr 1.1fr 1.3fr 0.8fr 2.1fr;gap:0;padding:14px 20px;border-bottom:1px solid var(--border-subtle);align-items:center;background:<?= $isBanned ? 'rgba(180,69,31,0.04)' : 'transparent' ?>;transition:background 0.15s;" onmouseenter="this.style.background='<?= $isBanned ? 'rgba(180,69,31,0.07)' : 'var(--surface-sunken)' ?>'" onmouseleave="this.style.background='<?= $isBanned ? 'rgba(180,69,31,0.04)' : 'transparent' ?>'">

          <!-- Membro -->
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:50%;background:<?= $avatarBg ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#FFF;flex-shrink:0;position:relative;">
              <?= strtoupper(substr($u['nome'], 0, 1)) ?>
              <?php if ($isOnline): ?>
                <div style="position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:#4CAF50;border:2px solid var(--surface-card);"></div>
              <?php endif; ?>
            </div>
            <div style="min-width:0;">
              <div style="font-weight:700;font-size:13.5px;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($u['nome']) ?></div>
              <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($u['email']) ?></div>
            </div>
          </div>

          <!-- Localização -->
          <div style="font-size:12px;color:var(--text-secondary);">
            <div style="display:flex;align-items:center;gap:4px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($u['bairro_nome'] ?? '—') ?>
            </div>
            <?php if (!empty($u['municipio_nome'])): ?>
              <div style="font-size:11px;color:var(--text-muted);margin-top:2px;"><?= e($u['municipio_nome']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Contribuição -->
          <div style="font-size:12px;">
            <div style="color:var(--text-primary);font-weight:600;"><?= (int)$u['total_reportes'] ?> reportes</div>
            <div style="color:var(--text-muted);margin-top:2px;"><?= (int)$u['total_comentarios'] ?> comentários</div>
          </div>

          <!-- Último acesso -->
          <div style="font-size:12px;">
            <?php if ($isOnline): ?>
              <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(76,175,80,0.1);border:1px solid rgba(76,175,80,0.25);color:#4CAF50;padding:3px 8px;border-radius:20px;font-weight:700;font-size:11px;">
                <span style="width:6px;height:6px;border-radius:50%;background:#4CAF50;animation:pulse 2s infinite;"></span>
                Online agora
              </span>
            <?php elseif (!empty($u['ultimo_acesso'])): ?>
              <div style="color:var(--text-secondary);"><?= dataAngolana($u['ultimo_acesso']) ?></div>
            <?php else: ?>
              <span style="color:var(--text-muted);font-style:italic;">Sem registo</span>
            <?php endif; ?>
          </div>

          <!-- Estado -->
          <div>
            <?php if ($isBanned): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(180,69,31,0.1);border:1px solid rgba(180,69,31,0.3);color:var(--brand-terra);font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:20px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                Suspenso
              </span>
            <?php else: ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(76,175,80,0.08);border:1px solid rgba(76,175,80,0.25);color:#4CAF50;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:20px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                Ativo
              </span>
            <?php endif; ?>
          </div>

          <!-- Moderação -->
          <div style="display:flex;gap:6px;justify-content:flex-end;">
            <button type="button" onclick="openWarnModal(<?= $u['id'] ?>, '<?= e(addslashes($u['nome'])) ?>')" style="display:flex;align-items:center;gap:4px;background:rgba(31,111,107,0.08);border:1px solid rgba(31,111,107,0.25);color:var(--brand-baia);padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.15s;" onmouseenter="this.style.background='var(--brand-baia)';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(31,111,107,0.08)';this.style.color='var(--brand-baia)'" title="Enviar aviso de conduta">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              Aviso
            </button>

            <form action="<?= url('admin/usuarios/' . $u['id'] . '/banir') ?>" method="POST" style="margin:0;">
              <?= csrfField() ?>
              <button type="submit" style="display:flex;align-items:center;gap:4px;background:<?= $isBanned ? 'rgba(76,175,80,0.08)' : 'rgba(180,69,31,0.08)' ?>;border:1px solid <?= $isBanned ? 'rgba(76,175,80,0.25)' : 'rgba(180,69,31,0.25)' ?>;color:<?= $isBanned ? '#4CAF50' : 'var(--brand-terra)' ?>;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.15s;" onmouseenter="this.style.filter='brightness(0.85)'" onmouseleave="this.style.filter='brightness(1)'">
                <?php if ($isBanned): ?>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Reativar
                <?php else: ?>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg> Suspender
                <?php endif; ?>
              </button>
            </form>

            <form action="<?= url('admin/usuarios/' . $u['id'] . '/eliminar') ?>" method="POST" style="margin:0;">
              <?= csrfField() ?>
              <button type="submit" style="display:flex;align-items:center;gap:4px;background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.25);color:#C0392B;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.15s;" onmouseenter="this.style.background='#C0392B';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(192,57,43,0.08)';this.style.color='#C0392B'" title="Eliminar utilizador definitivamente">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                Eliminar
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- ══ MODAL: AVISO DE BONS MODOS (REDESENHADO) ═════════════════════ -->
<div id="warnModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,10,8,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:var(--surface-card);max-width:520px;width:90%;border-radius:18px;box-shadow:0 32px 64px rgba(0,0,0,0.3);border:1px solid var(--border-subtle);overflow:hidden;animation:scaleUp 0.35s cubic-bezier(0.16,1,0.3,1);">

    <!-- Cabeçalho do modal -->
    <div style="background:linear-gradient(135deg,#1A1410,#2D221A);padding:22px 26px;display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid var(--brand-baia);">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(31,111,107,0.2);border:1px solid rgba(31,111,107,0.4);display:flex;align-items:center;justify-content:center;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <div style="font-size:10.5px;font-weight:700;color:var(--brand-dendem);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:2px;">Moderação de Conduta Cívica</div>
          <h3 style="margin:0;font-size:1.1rem;font-weight:800;color:#FFF;">Enviar Notificação de Bons Modos</h3>
        </div>
      </div>
      <button onclick="closeWarnModal()" style="background:rgba(255,255,255,0.08);border:none;color:rgba(255,255,255,0.7);width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.15s;" onmouseenter="this.style.background='rgba(255,255,255,0.15)';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.7)'">&times;</button>
    </div>

    <!-- Corpo do modal -->
    <div style="padding:24px 26px;">
      
      <!-- User target badge -->
      <div style="background:var(--surface-sunken);border:1px solid var(--border-subtle);border-radius:10px;padding:10px 14px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:12.5px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span style="color:var(--text-muted);">Destinatário:</span>
        <strong id="warnUserName" style="color:var(--text-primary);font-weight:700;"></strong>
      </div>

      <!-- Modelos Rápidos (Preset Chips) -->
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Modelos Rápidos de Orientação</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <button type="button" onclick="useWarnPreset(1)" style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            💬 Respeito Mútuo
          </button>
          <button type="button" onclick="useWarnPreset(2)" style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            📍 Precisão de Local
          </button>
          <button type="button" onclick="useWarnPreset(3)" style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            ⚠️ Linguagem Ofensiva
          </button>
        </div>
      </div>

      <form id="warnForm" action="" method="POST">
        <?= csrfField() ?>
        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Mensagem Personalizada</label>
          <textarea id="warnMsgInput" name="mensagem" class="form-textarea" rows="4" placeholder="Escreva a mensagem de conduta cívica a ser enviada ao morador..." style="width:100%;font-size:13px;line-height:1.5;resize:vertical;box-sizing:border-box;border-radius:10px;padding:12px;"></textarea>
          <p style="font-size:11px;color:var(--text-muted);margin:6px 0 0;">Esta notificação será entregue diretamente no painel do utilizador.</p>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <button type="button" onclick="closeWarnModal()" class="btn btn--ghost btn--sm">Cancelar</button>
          <button type="submit" style="display:flex;align-items:center;gap:7px;background:var(--brand-baia);color:#FFF;padding:10px 22px;border-radius:9px;border:none;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(31,111,107,0.35);">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Enviar Notificação
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
@keyframes scaleUp{from{opacity:0;transform:scale(0.92)}to{opacity:1;transform:scale(1)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.4}}
</style>
<script>
const warnPresets = {
  1: "Estimado(a) morador(a), pedimos a gentileza de manter o respeito e linguagem adequada ao interagir com outros membros da comunidade na plataforma Djumbai.",
  2: "Estimado(a) morador(a), solicitamos que verifique com atenção a localização exata e o ponto de referência ao submeter novas ocorrências no seu bairro.",
  3: "AVISO DE CONDUTA: O seu último comentário continha termos inapropriados. Recordamos que o Djumbai promove o diálogo construtivo e o respeito mútuo."
};

function useWarnPreset(type) {
  if (warnPresets[type]) {
    document.getElementById('warnMsgInput').value = warnPresets[type];
  }
}

function openWarnModal(id, nome) {
  document.getElementById('warnUserName').textContent = nome;
  document.getElementById('warnMsgInput').value = '';
  document.getElementById('warnForm').action = '<?= url('admin/usuarios/') ?>' + id + '/notificar';
  const m = document.getElementById('warnModal');
  m.style.display = 'flex';
}

function closeWarnModal() {
  document.getElementById('warnModal').style.display = 'none';
}

document.getElementById('warnModal').addEventListener('click', function(e){
  if(e.target === this) closeWarnModal();
});
</script>
