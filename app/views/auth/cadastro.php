<div class="auth-split">

  <!-- Painel Esquerdo (Marca & Cultura) -->
  <div class="auth-brand-panel">
    <div class="auth-brand-panel__samakaka">
      <svg viewBox="0 0 720 16" width="100%" height="16" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <g fill="#D99A1E" opacity=".8">
          <polygon points="0,0 15,16 30,0"/><polygon points="30,0 45,16 60,0"/>
          <polygon points="60,0 75,16 90,0"/><polygon points="90,0 105,16 120,0"/>
          <polygon points="120,0 135,16 150,0"/><polygon points="150,0 165,16 180,0"/>
          <polygon points="180,0 195,16 210,0"/><polygon points="210,0 225,16 240,0"/>
        </g>
      </svg>
    </div>

    <div class="auth-brand-panel__content">
      <div class="pulse-pill" style="margin-bottom: 24px; background: rgba(255,251,245,0.1); border-color: rgba(255,251,245,0.15); color: #FFF;">
        <span class="pulse-pill__dot"></span>
        <span>Registo de Morador</span>
      </div>

      <h2 class="auth-brand-panel__title">Dá voz ao teu bairro em poucos segundos.</h2>
      <p class="auth-brand-panel__desc">
        Ao criar a tua conta gratuita, podes reportar buracos nas estradas, cortes de água, lixo acumulado e iluminação avariada no teu bairro.
      </p>

      <div style="display: flex; flex-direction: column; gap: var(--space-4); margin-bottom: var(--space-8);">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,251,245,0.1); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem); flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div>
            <strong style="display: block; font-size: var(--font-sm); color: #FFF;">Transparência Total</strong>
            <span style="font-size: var(--font-xs); color: var(--text-on-dark-muted);">Acompanhe o estado das ocorrências da tua zona em tempo real.</span>
          </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: flex-start;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,251,245,0.1); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem); flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div>
            <strong style="display: block; font-size: var(--font-sm); color: #FFF;">Força Comunitária</strong>
            <span style="font-size: var(--font-xs); color: var(--text-on-dark-muted);">Vota nas ocorrências dos vizinhos para acelerar o atendimento.</span>
          </div>
        </div>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; padding-top: var(--space-6); border-top: 1px solid rgba(255, 251, 245, 0.1); font-size: var(--font-xs); color: var(--text-on-dark-muted);">
      <span><?= icon('lock', 12, '', 'var(--brand-dendem)') ?> Dados Protegidos</span>
      <span>Djumbai · Angola</span>
    </div>
  </div>

  <!-- Painel Direito (Formulário Cadastro) -->
  <div class="auth-form-panel">
    <div class="auth-form-card auth-form-card--wide">

      <div class="auth-form-header">
        <h1>Criar Conta Grátis</h1>
        <p>Preencha os seus dados para participar activamente da plataforma</p>
      </div>

      <form class="auth-form" action="<?= url('cadastro') ?>" method="POST" novalidate>
        <?= csrfField() ?>

        <!-- Nome Completo -->
        <div class="form-field">
          <label for="regNome" class="form-label">Nome Completo</label>
          <div class="form-input-wrap">
            <span class="form-input-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input
              type="text"
              id="regNome"
              name="nome"
              class="form-input <?= !empty($errors['nome']) ? 'is-invalid' : '' ?>"
              placeholder="Ex: Maria Manuel Kiluanje"
              value="<?= e($old['nome'] ?? '') ?>"
              required
              autocomplete="name"
            />
          </div>
          <span class="form-error-msg" id="nomeError" aria-live="polite">
            <?= e($errors['nome'] ?? '') ?>
          </span>
        </div>

        <!-- Email & Telefone -->
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4);">
          <div class="form-field">
            <label for="regEmail" class="form-label">Correio electrónico</label>
            <div class="form-input-wrap">
              <span class="form-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <input
                type="email"
                id="regEmail"
                name="email"
                class="form-input <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                placeholder="nome@djumbai.ao"
                value="<?= e($old['email'] ?? '') ?>"
                required
                autocomplete="email"
              />
            </div>
            <span class="form-error-msg" id="emailError" aria-live="polite">
              <?= e($errors['email'] ?? '') ?>
            </span>
          </div>

          <div class="form-field">
            <label for="regTelefone" class="form-label">Telefone (Angola)</label>
            <div class="form-input-wrap">
              <span class="form-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <input
                type="tel"
                id="regTelefone"
                name="telefone"
                class="form-input"
                placeholder="+244 923 000 000"
                value="<?= e($old['telefone'] ?? '') ?>"
                autocomplete="tel"
              />
            </div>
            <span class="form-error-msg" id="telefoneError" aria-live="polite"></span>
          </div>
        </div>

        <!-- Localização em Cascata de Angola -->
        <div style="font-size: var(--font-xs); font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 0.8px; padding-top: 8px; border-top: 1px dashed var(--border-subtle); display: flex; align-items: center; gap: 6px;">
          <?= icon('map-pin', 14, '', 'var(--brand-terra)') ?> Localização em Angola
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: var(--space-3);">
          <div class="form-field">
            <label for="regProvincia" class="form-label">Província</label>
            <select id="regProvincia" name="provincia_id" class="form-select" required>
              <?php foreach ($provincias as $p): ?>
                <option value="<?= e($p['id']) ?>" <?= (int)$p['id'] === 1 ? 'selected' : '' ?>>
                  <?= e($p['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-field">
            <label for="regMunicipio" class="form-label">Município</label>
            <select id="regMunicipio" name="municipio_id" class="form-select" required>
              <option value="1" selected>Luanda (Sede)</option>
              <option value="2">Belas</option>
              <option value="3">Cacuaco</option>
              <option value="4">Cazenga</option>
              <option value="5">Kilamba Kiaxi</option>
              <option value="6">Viana</option>
            </select>
          </div>

          <div class="form-field">
            <label for="regBairro" class="form-label">Bairro</label>
            <select id="regBairro" name="bairro_id" class="form-select" required>
              <option value="1" selected>Alvalade</option>
              <option value="2">Ingombota</option>
              <option value="3">Maianga</option>
              <option value="4">Rangel</option>
              <option value="5">Sambizanga</option>
              <option value="6">Samba</option>
            </select>
          </div>
        </div>
        <span class="form-error-msg" id="bairroError" aria-live="polite">
          <?= e($errors['bairro'] ?? '') ?>
        </span>

        <!-- Senha & Confirmação com Medidor de Força -->
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4);">
          <div class="form-field">
            <label for="regPassword" class="form-label">Palavra-passe</label>
            <div class="form-input-wrap">
              <span class="form-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input
                type="password"
                id="regPassword"
                name="senha"
                class="form-input <?= !empty($errors['senha']) ? 'is-invalid' : '' ?>"
                placeholder="Mínimo 6 caracteres"
                required
                autocomplete="new-password"
              />
              <button type="button" id="toggleRegPasswordBtn" style="position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;" aria-label="Mostrar senha">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <!-- Medidor de Força -->
            <div class="strength-meter" aria-hidden="true">
              <div class="strength-bar"></div>
              <div class="strength-bar"></div>
              <div class="strength-bar"></div>
            </div>
            <span class="form-error-msg" id="regPasswordError" aria-live="polite">
              <?= e($errors['senha'] ?? '') ?>
            </span>
          </div>

          <div class="form-field">
            <label for="regPasswordConfirm" class="form-label">Confirmar Palavra-passe</label>
            <div class="form-input-wrap">
              <span class="form-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input
                type="password"
                id="regPasswordConfirm"
                name="senha_confirm"
                class="form-input <?= !empty($errors['senha_confirm']) ? 'is-invalid' : '' ?>"
                placeholder="Repita a senha"
                required
                autocomplete="new-password"
              />
            </div>
            <span class="form-error-msg" id="regPasswordConfirmError" aria-live="polite">
              <?= e($errors['senha_confirm'] ?? '') ?>
            </span>
          </div>
        </div>

        <!-- Botão Submeter -->
        <button type="submit" class="btn btn--primary btn--full btn--lg" style="margin-top: var(--space-2);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
          Criar a Minha Conta no Djumbai
        </button>

      </form>

      <div style="text-align: center; padding-top: var(--space-4); border-top: 1px solid var(--border-subtle); font-size: var(--font-sm); color: var(--text-secondary);">
        Já possui conta criada? <a href="<?= url('login') ?>" style="font-weight: 700; color: var(--brand-terra);">Fazer Login</a>
      </div>

    </div>
  </div>

</div>
