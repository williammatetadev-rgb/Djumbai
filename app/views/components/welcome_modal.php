<?php
if (!isset($_SESSION['welcome_modal'])) {
    return;
}
$welcomeData = $_SESSION['welcome_modal'];
unset($_SESSION['welcome_modal']);

$nome     = $welcomeData['nome'] ?? 'Utilizador';
$tipo     = $welcomeData['tipo'] ?? 'cidadao';
$isAdmin  = $tipo === 'admin';
$primeiro = explode(' ', trim($nome))[0];
?>

<!-- ══ POP-UP DE BOAS-VINDAS (10 SEGUNDOS SEM BOTÃO DE CONFIRMAÇÃO) ══ -->
<div id="djumbaiWelcomePop" class="dj-pop-toast dj-pop-toast--<?= $isAdmin ? 'warning' : 'success' ?>" style="display:none;position:fixed;top:24px;right:24px;z-index:999999;width:calc(100% - 48px);max-width:380px;">
  
  <div class="dj-pop-toast__body">
    <!-- Ícone -->
    <div class="dj-pop-toast__icon" style="width:36px;height:36px;border-radius:10px;background:<?= $isAdmin ? 'linear-gradient(135deg,#B4451F,#D99A1E)' : 'linear-gradient(135deg,#1F6F6B,#3F5A3C)' ?>;">
      <?php if ($isAdmin): ?>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <?php else: ?>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      <?php endif; ?>
    </div>

    <!-- Conteúdo do Pop-up -->
    <div class="dj-pop-toast__content">
      <h5 class="dj-pop-toast__title" style="font-size:13.5px;">
        Bem-vindo(a), <?= e($primeiro) ?>!
      </h5>
      <p class="dj-pop-toast__msg">
        <?= $isAdmin
          ? 'Painel de administração ativo. Gerencie ocorrências e modere a comunidade.'
          : 'Estamos felizes em tê-lo(a) aqui. Reporte problemas do seu bairro e acompanhe resoluções.' ?>
      </p>
    </div>

    <button type="button" class="dj-pop-toast__close" onclick="closeDjumbaiWelcomePop()" aria-label="Fechar">&times;</button>
  </div>

  <!-- Barra de contagem regressiva de 10 segundos -->
  <div class="dj-pop-toast__progress">
    <div class="dj-pop-toast__bar"></div>
  </div>

</div>

<script>
(function() {
  const pop = document.getElementById('djumbaiWelcomePop');
  if (pop) {
    pop.style.display = 'block';
    // Fechar automaticamente após 10 segundos
    setTimeout(function() {
      closeDjumbaiWelcomePop();
    }, 10000);
  }
})();

function closeDjumbaiWelcomePop() {
  const pop = document.getElementById('djumbaiWelcomePop');
  if (pop) {
    pop.classList.add('dj-pop-toast--hiding');
    setTimeout(function() {
      if (pop && pop.parentElement) pop.remove();
    }, 350);
  }
}
</script>
