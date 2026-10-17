<?php
// Componente reutilizável — modal de confirmação de logout
?>

<!-- ═══ MODAL DE CONFIRMAÇÃO DE LOGOUT ═══════════════════════════ -->
<div id="djumbaiLogoutModal" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:#fff;margin:auto;padding:28px 24px;width:90%;max-width:380px;border-radius:14px;text-align:center;animation:lm_fade 0.3s cubic-bezier(0.16,1,0.3,1);position:relative;box-shadow:0 20px 40px rgba(0,0,0,0.25);border:1px solid #eee;">

    <div style="width:52px;height:52px;border-radius:50%;background:rgba(180,69,31,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>

    <h2 style="font-size:1.2rem;font-weight:800;margin:0 0 8px;color:#1A1410;">Terminar sessão?</h2>
    <p style="font-size:13.5px;color:#6B5A4A;margin:0 0 24px;line-height:1.5;">
      Tem a certeza que pretende sair da sua conta no Djumbai?
    </p>

    <div style="display:flex;gap:10px;">
      <button type="button" id="logoutCancelBtn" style="flex:1;background:#f4f0ec;color:#4A3728;border:1px solid #ddd;padding:10px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
        Não, ficar
      </button>
      <button type="button" id="logoutConfirmBtn" style="flex:1;background:#B4451F;color:#fff;border:none;padding:10px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
        Sim, sair
      </button>
    </div>

  </div>
</div>

<style>
@keyframes lm_fade { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
</style>

<!-- Formulário oculto de logout (POST com CSRF para prevenir CSRF logout) -->
<form id="djumbaiLogoutForm" method="post" action="<?= url('logout') ?>" style="display:none;">
  <?= csrfField() ?>
</form>

<script nonce="<?= e(CSP_NONCE) ?>">
function executarLogout() {
  var f = document.getElementById('djumbaiLogoutForm');
  if (f) f.submit();
}

function openDjumbaiLogoutModal() {
  var m = document.getElementById('djumbaiLogoutModal');
  if (m) m.style.display = 'flex';
}

function closeDjumbaiLogoutModal() {
  var m = document.getElementById('djumbaiLogoutModal');
  if (m) m.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
  var cancelBtn = document.getElementById('logoutCancelBtn');
  if (cancelBtn) cancelBtn.addEventListener('click', closeDjumbaiLogoutModal);
  var confirmBtn = document.getElementById('logoutConfirmBtn');
  if (confirmBtn) confirmBtn.addEventListener('click', executarLogout);

  var logoutUrl = '<?= url('logout') ?>';

  // 1. Intercetar links para logout
  document.querySelectorAll('a[href="' + logoutUrl + '"], a[href*="/logout"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      if (link.closest('#djumbaiLogoutModal')) return;
      e.preventDefault();
      openDjumbaiLogoutModal();
    });
  });

  // 2. Intercetar formulários de logout
  document.querySelectorAll('form[action="' + logoutUrl + '"], form[action*="/logout"]').forEach(function (form) {
    if (form.id === 'djumbaiLogoutForm') return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      openDjumbaiLogoutModal();
    });
  });

  // 3. Intercetar botões com gatilho explícito
  document.querySelectorAll('.js-logout-trigger, [data-action="logout"]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      openDjumbaiLogoutModal();
    });
  });

  // Fechar ao clicar no fundo escuro
  var modalEl = document.getElementById('djumbaiLogoutModal');
  if (modalEl) {
    modalEl.addEventListener('click', function (e) {
      if (e.target === this) closeDjumbaiLogoutModal();
    });
  }
});
</script>
