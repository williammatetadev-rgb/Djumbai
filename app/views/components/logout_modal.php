<?php
// Componente reutilizável — modal de confirmação de logout
?>

<!-- ═══ MODAL DE CONFIRMAÇÃO DE LOGOUT ═══════════════════════════ -->
<div id="djumbaiLogoutModal" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);">
  <div style="background:#fff;margin:15% auto;padding:28px 24px;width:90%;max-width:380px;border-radius:14px;text-align:center;animation:lm_fade 0.3s cubic-bezier(0.16,1,0.3,1);position:relative;box-shadow:0 20px 40px rgba(0,0,0,0.25);border:1px solid #eee;">

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
      <button type="button" onclick="closeDjumbaiLogoutModal()" style="flex:1;background:#f4f0ec;color:#4A3728;border:1px solid #ddd;padding:10px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
        Não, ficar
      </button>
      <button type="button" onclick="executarLogout()" style="flex:1;background:#B4451F;color:#fff;border:none;padding:10px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
        Sim, sair
      </button>
    </div>

  </div>
</div>

<style>
@keyframes lm_fade { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
</style>
<script>
function executarLogout() {
  window.location.href = '<?= url('logout') ?>';
}

function closeDjumbaiLogoutModal() {
  document.getElementById('djumbaiLogoutModal').style.display = 'none';
}

// Interceptar links de logout que não estejam dentro do próprio modal
document.addEventListener('DOMContentLoaded', function () {
  var logoutUrl = '<?= url('logout') ?>';
  document.querySelectorAll('a[href="' + logoutUrl + '"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      // Ignorar se o link estiver dentro do modal de logout
      if (link.closest('#djumbaiLogoutModal')) return;
      e.preventDefault();
      document.getElementById('djumbaiLogoutModal').style.display = 'block';
    });
  });
});

// Fechar ao clicar no fundo escuro
document.getElementById('djumbaiLogoutModal').addEventListener('click', function (e) {
  if (e.target === this) closeDjumbaiLogoutModal();
});
</script>
