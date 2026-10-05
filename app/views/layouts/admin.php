<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?= e(csrfToken()) ?>" />
  <title><?= e($titulo ?? 'Painel Admin – Djumbai') ?></title>
  <link rel="stylesheet" href="<?= url('css/style.css') ?>" />
  <link rel="stylesheet" href="<?= url('css/admin.css') ?>" />
</head>
<body class="admin-body">
<aside class="admin-sidebar" id="adminSidebar">
  <div class="admin-sidebar__brand">
    <div class="admin-sidebar__logo">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>
    <div>
      <span style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 700; color: #FFF; line-height: 1; display: block;"><?= strtolower(e(siteSetting('nome_site', 'djumbai'))) ?></span>
      <span style="font-size: 9.5px; font-weight: 700; color: var(--brand-dendem); letter-spacing: 0.5px; text-transform: uppercase;">Centro de Despacho</span>
    </div>
  </div>

  <nav class="admin-sidebar__nav">
    <span class="admin-sidebar__section-title">Monitorização</span>
    <a href="<?= url('admin') ?>" class="admin-nav-item <?= isCurrentPage('/admin') ? 'is-active' : '' ?>">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
      </div>
    </a>

    <a href="<?= url('admin/problemas') ?>" class="admin-nav-item <?= isCurrentPage('/admin/problemas') ? 'is-active' : '' ?>">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        <span>Ocorrências</span>
      </div>
    </a>

    <span class="admin-sidebar__section-title">Moderação & Comunidade</span>
    <a href="<?= url('admin/usuarios') ?>" class="admin-nav-item <?= isCurrentPage('/admin/usuarios') ? 'is-active' : '' ?>">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Utilizadores</span>
      </div>
    </a>

    <a href="<?= url('admin/comentarios') ?>" class="admin-nav-item <?= isCurrentPage('/admin/comentarios') ? 'is-active' : '' ?>">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>Comentários</span>
      </div>
    </a>

    <span class="admin-sidebar__section-title">Configuração</span>
    <a href="<?= url('admin/definicoes') ?>" class="admin-nav-item <?= isCurrentPage('/admin/definicoes') ? 'is-active' : '' ?>">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        <span>Definições</span>
      </div>
    </a>

    <span class="admin-sidebar__section-title">Atalhos</span>
    <a href="<?= url() ?>" target="_blank" class="admin-nav-item">
      <div class="admin-nav-item__left">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        <span>Ver Portal Público</span>
      </div>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    </a>
  </nav>

  <div class="admin-sidebar__footer">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div>
        <strong style="display: block; font-size: var(--font-xs); color: #FFF;"><?= e($_SESSION['user_nome'] ?? 'Administrador') ?></strong>
        <span style="font-size: 10px; color: var(--brand-dendem);">Gestor Municipal</span>
      </div>
      <a href="<?= url('logout') ?>" class="btn btn--ghost-dark btn--sm" title="Terminar Sessão" style="padding: 6px 10px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </a>
    </div>
  </div>
</aside>

<!-- ╔══════════════════════════════════════════════════════════╗
     ║  ADMIN MAIN CANVAS                                       ║
     ╚══════════════════════════════════════════════════════════╝ -->
<div class="admin-canvas">

  <!-- Topbar -->
  <header class="admin-topbar">
    <div class="admin-topbar__left">
      <button class="admin-topbar__toggle" id="adminSidebarToggle" aria-label="Abrir Menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>

      <?php if (siteSetting('modo_manutencao')): ?>
        <div class="pulse-pill" style="padding: 4px 12px; font-size: 11px; background: rgba(180,69,31,0.15); border-color: rgba(180,69,31,0.4); color: var(--brand-terra);">
          <span class="pulse-pill__dot" style="background: var(--brand-terra);"></span>
          <span>MODO DE MANUTENÇÃO ATIVO · PÚBLICO BLOQUEADO</span>
        </div>
      <?php else: ?>
        <div class="pulse-pill" style="padding: 4px 12px; font-size: 11px;">
          <span class="pulse-pill__dot"></span>
          <span>SISTEMA ATIVO · LUANDA</span>
        </div>
      <?php endif; ?>
    </div>

    <div class="admin-topbar__right">
      <span style="font-size: var(--font-xs); color: var(--text-muted); display: none; @media(min-width:640px){display:inline}">
        <?= dataAngolana(date('Y-m-d')) ?>
      </span>

      <div class="admin-user-pill">
        <div class="admin-avatar">
          <?= strtoupper(substr($_SESSION['user_nome'] ?? 'A', 0, 1)) ?>
        </div>
        <div style="font-size: var(--font-xs); line-height: 1.2;">
          <strong style="color: var(--text-primary); display: block;"><?= e($_SESSION['user_nome'] ?? 'Admin') ?></strong>
          <span style="color: var(--brand-terra); font-weight: 600;">Administrador</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Pop-ups Globais de Notificação (10s sem confirmação) -->
  <?php require APP_PATH . '/views/components/flash_toast.php'; ?>

  <!-- Conteúdo Injetado -->
  <main class="admin-content">
    <?= $content ?>
  </main>

</div>

<!-- Componente Modal de Boas-Vindas -->
<?php require APP_PATH . '/views/components/welcome_modal.php'; ?>

<!-- Componente Modal de Confirmação de Logout -->
<?php require APP_PATH . '/views/components/logout_modal.php'; ?>

<script src="<?= url('js/script.js') ?>"></script>
<script>
  // Controle de abertura da sidebar no mobile
  const adminSidebar = document.getElementById('adminSidebar');
  const adminToggle = document.getElementById('adminSidebarToggle');
  if (adminSidebar && adminToggle) {
    adminToggle.addEventListener('click', () => {
      adminSidebar.classList.toggle('is-open');
    });
  }
</script>
</body>
</html>
