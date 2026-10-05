<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: auth/login.php                                    ║
     ╚══════════════════════════════════════════════════════════╝ -->

<div class="auth-split">

  <!-- Painel Esquerdo (Marca & Identidade Angolana) -->
  <div class="auth-brand-panel">
    <div class="auth-brand-panel__samakaka">
      <svg viewBox="0 0 720 16" width="100%" height="16" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <g fill="#D99A1E" opacity=".8">
          <polygon points="0,0 15,16 30,0"/><polygon points="30,0 45,16 60,0"/>
          <polygon points="60,0 75,16 90,0"/><polygon points="90,0 105,16 120,0"/>
          <polygon points="120,0 135,16 150,0"/><polygon points="150,0 165,16 180,0"/>
          <polygon points="180,0 195,16 210,0"/><polygon points="210,0 225,16 240,0"/>
          <polygon points="240,0 255,16 270,0"/><polygon points="270,0 285,16 300,0"/>
          <polygon points="300,0 315,16 330,0"/><polygon points="330,0 345,16 360,0"/>
        </g>
      </svg>
    </div>

    <div class="auth-brand-panel__content">
      <div class="pulse-pill" style="margin-bottom: 24px; background: rgba(255,251,245,0.1); border-color: rgba(255,251,245,0.15); color: #FFF;">
        <span class="pulse-pill__dot"></span>
        <span>Aceder à Comunidade</span>
      </div>

      <h2 class="auth-brand-panel__title">Juntos pela melhoria contínua dos nossos bairros.</h2>
      <p class="auth-brand-panel__desc">
        Aceda à sua conta para confirmar ocorrências relatadas pelos seus vizinhos, adicionar novidades e acompanhar a evolução de cada problema.
      </p>

      <div style="background: rgba(255,251,245,0.06); border-left: 3px solid var(--brand-dendem); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; padding: var(--space-4) var(--space-5); margin-bottom: var(--space-8);">
        <p style="font-size: var(--font-sm); font-style: italic; color: rgba(255,251,245,0.85); line-height: 1.6;">
          “Quando os moradores se unem e acompanham os problemas de forma organizada, o resultado é comunitário e duradouro.”
        </p>
        <span style="display: block; font-size: var(--font-xs); font-weight: 700; color: var(--brand-dendem); margin-top: 6px;">— Moradores de Angola em acção</span>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; padding-top: var(--space-6); border-top: 1px solid rgba(255, 251, 245, 0.1); font-size: var(--font-xs); color: var(--text-on-dark-muted);">
      <span><?= icon('lock', 12, '', 'var(--brand-dendem)') ?> Conexão Segura</span>
      <span>Djumbai · Angola</span>
    </div>
  </div>

  <!-- Painel Direito (Formulário) -->
  <div class="auth-form-panel">
    <div class="auth-form-card">

      <div class="auth-form-header">
        <h1>Entrar na Conta</h1>
        <p>Insira os seus dados de acesso para continuar</p>
      </div>



      <form class="auth-form" action="<?= url('login') ?>" method="POST" novalidate>
        <?= csrfField() ?>

        <!-- Email -->
        <div class="form-field">
          <label for="loginEmail" class="form-label">Correio electrónico</label>
          <div class="form-input-wrap">
            <span class="form-input-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <input
              type="email"
              id="loginEmail"
              name="email"
              class="form-input <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
              placeholder="exemplo@djumbai.ao"
              value="<?= e($old['email'] ?? '') ?>"
              required
              autocomplete="email"
            />
          </div>
          <span class="form-error-msg" id="emailError" aria-live="polite">
            <?= e($errors['email'] ?? '') ?>
          </span>
        </div>

        <!-- Palavra-passe -->
        <div class="form-field">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <label for="loginPassword" class="form-label">Palavra-passe</label>
            <a href="#" style="font-size: var(--font-xs); font-weight: 600; color: var(--brand-terra);">Esqueceu a palavra-passe?</a>
          </div>
          <div class="form-input-wrap">
            <span class="form-input-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input
              type="password"
              id="loginPassword"
              name="senha"
              class="form-input <?= !empty($errors['senha']) ? 'is-invalid' : '' ?>"
              placeholder="••••••••"
              required
              autocomplete="current-password"
            />
            <button type="button" id="togglePasswordBtn" style="position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;" aria-label="Mostrar senha">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <span class="form-error-msg" id="passwordError" aria-live="polite">
            <?= e($errors['senha'] ?? '') ?>
          </span>
        </div>

        <!-- Botão Entrar -->
        <button type="submit" class="btn btn--primary btn--full btn--lg" style="margin-top: var(--space-2);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          Entrar na Conta
        </button>

      </form>

      <div style="text-align: center; padding-top: var(--space-4); border-top: 1px solid var(--border-subtle); font-size: var(--font-sm); color: var(--text-secondary);">
        Ainda não tem conta no Djumbai? <a href="<?= url('cadastro') ?>" style="font-weight: 700; color: var(--brand-terra);">Criar Conta Grátis</a>
      </div>

    </div>
  </div>

</div>
