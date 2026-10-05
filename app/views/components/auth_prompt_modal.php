<?php
// Componente reutilizável — Modal de aviso para visitante (entrar ou participar)
?>

<!-- ═══ MODAL DE AUTENTICAÇÃO OBRIGATÓRIA PARA VISITANTES ════════ -->
<div id="djumbaiAuthPromptModal" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100%;height:100%;background:rgba(15,10,8,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:var(--surface-card);width:90%;max-width:440px;border-radius:18px;box-shadow:0 32px 64px rgba(0,0,0,0.35);border:1px solid var(--border-subtle);overflow:hidden;animation:apm_scale 0.35s cubic-bezier(0.16,1,0.3,1);position:relative;">

    <!-- Cabeçalho do modal -->
    <div style="background:linear-gradient(135deg,#1A1410 0%,#2D221A 100%);padding:22px 24px;position:relative;border-bottom:3px solid var(--brand-dendem);">
      <button onclick="closeAuthPromptModal()" style="position:absolute;top:16px;right:16px;width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.08);border:none;color:rgba(255,255,255,0.7);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;">&times;</button>

      <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--brand-terra),var(--brand-dendem));display:flex;align-items:center;justify-content:center;margin-bottom:12px;box-shadow:0 4px 12px rgba(180,69,31,0.3);">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>

      <h2 style="font-family:var(--font-display);font-size:1.35rem;font-weight:800;color:#FFF;margin:0 0 4px;">
        Desejas entrar ou participar?
      </h2>
      <p style="font-size:12.5px;color:rgba(255,251,245,0.65);margin:0;line-height:1.4;" id="authPromptModalSub">
        Para confirmar ou comentar em ocorrências comunitárias, é necessário fazer parte da comunidade Djumbai.
      </p>
    </div>

    <!-- Corpo com botões de ação -->
    <div style="padding:24px;">
      
      <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
        
        <!-- Botão Entrar (Login) -->
        <a href="<?= url('login') ?>" style="display:flex;align-items:center;justify-content:center;gap:8px;background:var(--brand-terra);color:#FFF;padding:12px 20px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:700;box-shadow:0 4px 12px rgba(180,69,31,0.3);transition:transform 0.15s;" onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          Entrar (Fazer Login)
        </a>

        <!-- Botão Participar (Cadastro) -->
        <a href="<?= url('cadastro') ?>" style="display:flex;align-items:center;justify-content:center;gap:8px;background:var(--surface-sunken);color:var(--text-primary);border:1.5px solid var(--brand-dendem);padding:12px 20px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:700;transition:transform 0.15s;" onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          Participar (Criar Conta Grátis)
        </a>

      </div>

      <div style="text-align:center;">
        <button onclick="closeAuthPromptModal()" style="background:none;border:none;color:var(--text-muted);font-size:12px;font-weight:600;cursor:pointer;padding:4px 8px;">
          Continuar apenas como visitante
        </button>
      </div>

    </div>

  </div>
</div>

<style>
@keyframes apm_scale { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
</style>
<script>
function openAuthPromptModal(reason) {
  const modal = document.getElementById('djumbaiAuthPromptModal');
  const sub = document.getElementById('authPromptModalSub');
  if (sub && reason === 'confirmar') {
    sub.textContent = 'Para apoiar e validar ocorrencias no seu bairro, precisa de ter uma conta no Djumbai.';
  } else if (sub && reason === 'comentar') {
    sub.textContent = 'Para publicar comentários ou participar na discussão comunitária, precisa de entrar na sua conta.';
  }
  if (modal) modal.style.display = 'flex';
}

function closeAuthPromptModal() {
  const modal = document.getElementById('djumbaiAuthPromptModal');
  if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  const m = document.getElementById('djumbaiAuthPromptModal');
  if (m) {
    m.addEventListener('click', function(e) {
      if (e.target === this) closeAuthPromptModal();
    });
  }
});
</script>
