<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?= e(csrfToken()) ?>" />
  <title><?= e($titulo ?? 'Moderação de Comentários – Admin Djumbai') ?></title>
  <link rel="stylesheet" href="<?= url('css/style.css') ?>" />
  <link rel="stylesheet" href="<?= url('css/admin.css') ?>" />
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: var(--surface-ground, #FBF8F2);
      font-family: var(--font-body, system-ui, -apple-system, sans-serif);
    }
    .admin-comentarios-fullscreen-app {
      display: flex;
      flex-direction: column;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
    }
  </style>
</head>
<body>
  <div class="admin-comentarios-fullscreen-app">
    <?= $content ?>
  </div>

  <!-- Pop-ups Globais de Notificação -->
  <?php require APP_PATH . '/views/components/flash_toast.php'; ?>

  <script src="<?= url('js/script.js') ?>"></script>
</body>
</html>
