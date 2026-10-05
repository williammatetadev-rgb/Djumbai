<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: user/settings.php (Definições do Cidadão)         ║
     ╚══════════════════════════════════════════════════════════╝ -->

<div class="container" style="padding-block: var(--space-8); max-width: 760px;">

  <!-- Breadcrumb -->
  <nav aria-label="Navegação de localização" style="margin-bottom: var(--space-6);">
    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted);">
      <a href="<?= url('perfil') ?>" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Meu Painel
      </a>
      <span style="color: var(--border-subtle);">/</span>
      <span style="color: var(--text-primary); font-weight: 600;">Definições da Conta</span>
    </div>
  </nav>

  <!-- Cabeçalho -->
  <div style="margin-bottom: 28px;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
      <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(31,111,107,0.12); border: 1px solid rgba(31,111,107,0.25); display: flex; align-items: center; justify-content: center; color: var(--brand-baia);">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      </div>
      <div>
        <h1 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
          Definições de Perfil
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 2px 0 0;">
          Atualize os seus dados pessoais de contacto na plataforma Djumbai.
        </p>
      </div>
    </div>
  </div>

  <!-- Card das Definições -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm);">

    <form action="<?= url('perfil/definicoes') ?>" method="POST">
      <?= csrfField() ?>

      <div style="display: flex; flex-direction: column; gap: 20px;">

        <!-- Nome Completo -->
        <div class="form-field">
          <label for="nome" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Nome Completo *
          </label>
          <input
            type="text"
            id="nome"
            name="nome"
            class="form-input <?= !empty($errors['nome']) ? 'is-invalid' : '' ?>"
            value="<?= e($perfil['nome'] ?? '') ?>"
            required
            style="font-size: 13.5px; padding: 10px 14px;"
          />
          <?php if (!empty($errors['nome'])): ?>
            <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['nome']) ?></span>
          <?php endif; ?>
        </div>

        <!-- E-mail -->
        <div class="form-field">
          <label for="email" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Endereço de E-mail *
          </label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-input <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
            value="<?= e($perfil['email'] ?? '') ?>"
            required
            style="font-size: 13.5px; padding: 10px 14px;"
          />
          <?php if (!empty($errors['email'])): ?>
            <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['email']) ?></span>
          <?php endif; ?>
        </div>

        <!-- Número de Telefone -->
        <div class="form-field">
          <label for="telefone" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Número de Telefone
          </label>
          <input
            type="text"
            id="telefone"
            name="telefone"
            class="form-input"
            placeholder="Ex: +244 923 000 000"
            value="<?= e($perfil['telefone'] ?? '') ?>"
            style="font-size: 13.5px; padding: 10px 14px;"
          />
        </div>

        <!-- Informação de Bairro (Readonly info) -->
        <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 14px 16px; font-size: 12.5px; color: var(--text-secondary); display: flex; align-items: center; gap: 10px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <div>
            Localização Registada: <strong style="color: var(--text-primary);"><?= e($perfil['bairro'] ?? 'Luanda') ?>, <?= e($perfil['municipio'] ?? 'Sede') ?></strong>
          </div>
        </div>

        <!-- Botões de Ação -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center; padding-top: 12px; border-top: 1px solid var(--border-subtle);">
          <a href="<?= url('perfil') ?>" class="btn btn--ghost btn--sm">Cancelar</a>
          <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; background: var(--brand-terra); color: #FFF; border: none; padding: 11px 24px; border-radius: 9px; font-size: 13.5px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(180,69,31,0.3); transition: transform 0.15s;" onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Guardar Alterações
          </button>
        </div>

      </div>

    </form>

  </div>

</div>
