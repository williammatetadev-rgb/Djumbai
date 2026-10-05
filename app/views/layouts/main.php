<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?= e(siteSetting('nome_site', 'Djumbai')) ?> – <?= e(siteSetting('slogan', 'Plataforma Cívica Digital para Reporte e Monitorização de Dificuldades Comunitárias em Angola.')) ?>" />
  <meta name="csrf-token" content="<?= e(csrfToken()) ?>" />
  <title><?= e(($titulo ?? siteSetting('nome_site', 'Djumbai')) . ' – ' . siteSetting('nome_site', 'Djumbai')) ?></title>

  <script>window.IS_LOGGED_IN = <?= isLoggedIn() ? 'true' : 'false' ?>;</script>
  <!-- Estilos Djumbai (offline, sem CDN) -->
  <link rel="stylesheet" href="<?= url('css/style.css') ?>" />
</head>
<body>
<header class="navbar" role="banner">
  <div class="container navbar__inner">

    <a href="<?= (isLoggedIn() && !isAdmin()) ? url('perfil') : url() ?>" class="navbar__brand" aria-label="<?= e(siteSetting('nome_site', 'Djumbai')) ?> — Início">
      <div class="navbar__logo-icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div class="navbar__brand-text">
        <span class="navbar__wordmark"><?= strtolower(e(siteSetting('nome_site', 'djumbai'))) ?></span>
        <span class="navbar__tagline"><?= e(siteSetting('slogan', 'Plataforma Cívica · Angola')) ?></span>
      </div>
    </a>

    <nav class="navbar__nav" aria-label="Navegação Principal">
      <?php if (isLoggedIn() && !isAdmin()): ?>
        <!-- Cidadão autenticado: sem links públicos na barra de navegação -->
      <?php else: ?>
        <a href="<?= url() ?>"               class="navbar__link <?= isCurrentPage('/') ? 'navbar__link--active' : '' ?>">Início</a>
        <a href="<?= url('problemas') ?>"    class="navbar__link <?= isCurrentPage('/problemas') ? 'navbar__link--active' : '' ?>">Explorar Reportes</a>
        <a href="<?= url('como-funciona') ?>" class="navbar__link <?= isCurrentPage('/como-funciona') ? 'navbar__link--active' : '' ?>">Como Funciona</a>
      <?php endif; ?>
    </nav>

    <div class="navbar__actions">
      <?php if (isLoggedIn()): ?>
        <?php if (isAdmin()): ?>
          <a href="<?= url('admin') ?>" class="btn btn--secondary btn--sm">Painel Admin</a>
        <?php else: ?>
          <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--sm">Explorar Reportes</a>
        <?php endif; ?>
        <a href="<?= url('logout') ?>" class="btn btn--ghost btn--sm">Sair</a>
      <?php else: ?>
        <a href="<?= url('login') ?>"    class="btn btn--ghost btn--sm">Entrar</a>
        <a href="<?= url('cadastro') ?>" class="btn btn--primary btn--sm">Criar Conta</a>
      <?php endif; ?>

      <button class="navbar__toggle" id="navToggle" aria-label="Abrir menu" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer" role="navigation" aria-label="Menu Mobile">
  <?php if (isLoggedIn() && !isAdmin()): ?>
    <a href="<?= url('perfil') ?>"    class="navbar__link">Meu Painel</a>
    <a href="<?= url('problemas') ?>" class="navbar__link">Explorar Reportes</a>
    <div style="padding-top: var(--space-4); display: flex; flex-direction: column; gap: 8px;">
      <a href="<?= url('reportar') ?>" class="btn btn--primary btn--full">Reportar Problema</a>
      <a href="<?= url('logout') ?>"   class="btn btn--ghost btn--full">Terminar Sessão</a>
    </div>
  <?php else: ?>
    <a href="<?= url() ?>"            class="navbar__link">Início</a>
    <a href="<?= url('problemas') ?>" class="navbar__link">Explorar Reportes</a>
    <a href="<?= url('como-funciona') ?>" class="navbar__link">Como Funciona</a>
    <div style="padding-top: var(--space-4); display: flex; flex-direction: column; gap: 8px;">
      <?php if (isLoggedIn() && isAdmin()): ?>
        <a href="<?= url('admin') ?>"  class="btn btn--secondary btn--full">Painel Admin</a>
        <a href="<?= url('logout') ?>" class="btn btn--ghost btn--full">Terminar Sessão</a>
      <?php else: ?>
        <a href="<?= url('login') ?>"    class="btn btn--ghost btn--full">Entrar</a>
        <a href="<?= url('cadastro') ?>" class="btn btn--primary btn--full">Criar Conta Grátis</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
<!-- Pop-ups Globais de Notificação (10s sem confirmação) -->
<?php require APP_PATH . '/views/components/flash_toast.php'; ?>
<main id="main-content" role="main">
  <?= $content ?>
</main>
<?php if (empty($hideFooter)): ?>

  <?php if (!empty($isUserPanel) || (isLoggedIn() && !isAdmin())): ?>
  <!-- ── Rodapé do Painel do Cidadão (minimalista) ── -->
  <footer style="background:var(--surface-dark);border-top:1px solid rgba(255,251,245,0.07);padding:16px 0;" role="contentinfo">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
      <div style="display:flex;align-items:center;gap:8px;">
        <div style="width:22px;height:22px;background:var(--brand-terra);border-radius:5px;display:flex;align-items:center;justify-content:center;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <span style="font-family:var(--font-display);font-size:0.9rem;font-weight:700;color:var(--brand-terra);">djumbai</span>
        <span style="font-size:11px;color:rgba(255,251,245,0.25);">· Plataforma Cívica de Angola</span>
      </div>
      <p style="font-size:11px;color:rgba(255,251,245,0.2);margin:0;">© <?= date('Y') ?> Djumbai · Todos os direitos reservados.</p>
    </div>
  </footer>

  <?php else: ?>
  <!-- ── Rodapé Público (completo) ── -->
  <footer style="background:var(--surface-dark);color:var(--text-on-dark);padding-block:var(--space-12) var(--space-6);border-top:1px solid rgba(255,251,245,0.08);" role="contentinfo">
    <div class="container" style="display:flex;flex-wrap:wrap;gap:var(--space-8);justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-8);">
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <div style="width:30px;height:30px;background:var(--brand-terra);border-radius:6px;display:flex;align-items:center;justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <span style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--brand-terra);"><?= strtolower(e(siteSetting('nome_site', 'djumbai'))) ?></span>
        </div>
        <p style="font-size:var(--font-sm);color:var(--text-on-dark-muted);max-width:340px;line-height:1.6;"><?= e(siteSetting('slogan', 'Plataforma cívica digital para reporte e acompanhamento transparente de dificuldades comunitárias em Angola.')) ?></p>
      </div>
      <nav aria-label="Rodapé">
        <strong style="display:block;font-size:var(--font-xs);text-transform:uppercase;letter-spacing:1px;color:var(--brand-dendem);margin-bottom:10px;">Plataforma</strong>
        <ul style="display:flex;flex-direction:column;gap:6px;font-size:var(--font-sm);color:var(--text-on-dark-muted);">
          <li><a href="<?= url() ?>"            style="color:inherit">Início</a></li>
          <li><a href="<?= url('problemas') ?>" style="color:inherit">Ver Reportes</a></li>
          <li><a href="<?= url('como-funciona') ?>" style="color:inherit">Como Funciona</a></li>
          <li><a href="<?= url('reportar') ?>"  style="color:inherit">Reportar Problema</a></li>
        </ul>
      </nav>
      <nav aria-label="Conta">
        <strong style="display:block;font-size:var(--font-xs);text-transform:uppercase;letter-spacing:1px;color:var(--brand-dendem);margin-bottom:10px;">Conta</strong>
        <ul style="display:flex;flex-direction:column;gap:6px;font-size:var(--font-sm);color:var(--text-on-dark-muted);">
          <li><a href="<?= url('login') ?>"    style="color:inherit">Entrar</a></li>
          <li><a href="<?= url('cadastro') ?>" style="color:inherit">Criar Conta Grátis</a></li>
        </ul>
      </nav>
    </div>
    <div class="container" style="padding-top:var(--space-5);border-top:1px solid rgba(255,251,245,0.07);display:flex;justify-content:space-between;align-items:center;font-size:var(--font-xs);color:var(--text-on-dark-muted);">
      <p>© <?= date('Y') ?> <?= e(siteSetting('nome_site', 'Djumbai')) ?> · Angola. Todos os direitos reservados.</p>
      <p>Plataforma Cívica Blindada e Protegida.</p>
    </div>
  </footer>
  <?php endif; ?>

<?php endif; ?>


<!-- Componente Modal de Boas-Vindas -->
<?php require APP_PATH . '/views/components/welcome_modal.php'; ?>

<!-- Componente Modal de Confirmação de Logout -->
<?php require APP_PATH . '/views/components/logout_modal.php'; ?>

<!-- Componente Modal de Autenticação Obrigatória para Visitantes -->
<?php require APP_PATH . '/views/components/auth_prompt_modal.php'; ?>

<!-- Scripts -->
<script src="<?= url('js/script.js') ?>"></script>
</body>
</html>
