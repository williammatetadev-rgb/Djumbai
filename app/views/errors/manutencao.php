<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e(siteSetting('nome_site', 'Djumbai')) ?> – Em Manutenção</title>
  <link rel="stylesheet" href="<?= url('css/style.css') ?>" />
</head>
<body style="background:var(--surface-dark);color:var(--text-on-dark);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;margin:0;font-family:var(--font-sans,sans-serif);">

  <div style="max-width:540px;width:100%;background:linear-gradient(135deg,#1C1510 0%,#2D221A 100%);border:1.5px solid var(--brand-dendem);border-radius:20px;padding:40px 32px;text-align:center;box-shadow:0 32px 64px rgba(0,0,0,0.5);position:relative;overflow:hidden;">

    <!-- Detalhe decorativo no topo -->
    <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--brand-terra),var(--brand-dendem),var(--brand-capim));"></div>

    <!-- Ícone de Manutenção -->
    <div style="width:68px;height:68px;border-radius:20px;background:rgba(217,154,30,0.15);border:1.5px solid var(--brand-dendem);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 8px 24px rgba(217,154,30,0.25);">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </div>

    <!-- Título & Identidade -->
    <span style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--brand-dendem);letter-spacing:1px;text-transform:uppercase;display:block;margin-bottom:6px;">
      <?= e(siteSetting('nome_site', 'Djumbai')) ?>
    </span>
    <h1 style="font-family:var(--font-display);font-size:1.65rem;font-weight:800;color:#FFF;margin:0 0 14px;line-height:1.25;">
      Plataforma em Manutenção
    </h1>

    <p style="font-size:14px;color:rgba(255,251,245,0.75);line-height:1.6;margin:0 0 24px;">
      Estamos a realizar actualizações técnicas e melhorias para garantir um serviço cívico mais rápido, seguro e eficiente para todos os cidadãos de Angola.
    </p>

    <!-- Cartão de Contactos -->
    <div style="background:rgba(255,251,245,0.06);border:1px solid rgba(255,251,245,0.1);border-radius:12px;padding:16px 20px;margin-bottom:28px;text-align:left;">
      <span style="font-size:11px;font-weight:700;color:var(--brand-dendem);text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:8px;">Canais de Contacto</span>
      <div style="font-size:12.5px;color:rgba(255,251,245,0.9);display:flex;flex-direction:column;gap:6px;">
        <div><strong>E-mail:</strong> <?= e(siteSetting('email_suporte', 'suporte@djumbai.ao')) ?></div>
        <div><strong>Linha de Apoio:</strong> <?= e(siteSetting('telefone_emergencia', '+244 923 000 000')) ?></div>
      </div>
    </div>

    <!-- Botões -->
    <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
      <a href="<?= url('login') ?>" class="btn btn--primary" style="padding:10px 24px;font-size:13px;">
        Acesso de Administrador
      </a>
    </div>

  </div>

</body>
</html>
