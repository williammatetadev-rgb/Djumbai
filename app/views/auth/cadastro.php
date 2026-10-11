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
        <span>Registo de Cidadão</span>
      </div>

      <h2 class="auth-brand-panel__title">Dá voz ao teu bairro em três passos simples.</h2>
      <p class="auth-brand-panel__desc">
        Ao criar a tua conta verificada com Bilhete de Identidade, podes reportar buracos nas estradas, cortes de água, lixo e iluminação pública com total legitimidade civil.
      </p>

      <div style="display: flex; flex-direction: column; gap: var(--space-4); margin-bottom: var(--space-8);">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,251,245,0.1); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem); flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div>
            <strong style="display: block; font-size: var(--font-sm); color: #FFF;">Identificação Cívica Segura</strong>
            <span style="font-size: var(--font-xs); color: var(--text-on-dark-muted);">Verificação de cidadania autêntica para evitar denúncias falsas.</span>
          </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: flex-start;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,251,245,0.1); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem); flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div>
            <strong style="display: block; font-size: var(--font-sm); color: #FFF;">Acesso por E-mail ou Telefone</strong>
            <span style="font-size: var(--font-xs); color: var(--text-on-dark-muted);">Flexibilidade total: aceda mesmo sem correio eletrónico.</span>
          </div>
        </div>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; padding-top: var(--space-6); border-top: 1px solid rgba(255, 251, 245, 0.1); font-size: var(--font-xs); color: var(--text-on-dark-muted);">
      <span><?= icon('lock', 12, '', 'var(--brand-dendem)') ?> Dados Criptografados</span>
      <span>Djumbai · Angola</span>
    </div>
  </div>

  <!-- Painel Direito (Multi-Step Form) -->
  <div class="auth-form-panel">
    <div class="auth-form-card auth-form-card--wide" style="max-width: 520px;">

      <!-- Header Central com Marca Djumbai -->
      <div style="text-align: center; margin-bottom: 20px;">
        <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px;">
          <div style="width: 32px; height: 32px; background: var(--brand-terra); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <span style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 800; color: var(--brand-terra); letter-spacing: -0.5px;">djumbai</span>
        </div>

        <!-- Indicador de Fatores / Passos (Step Indicator) -->
        <div class="steps-progress" style="display: flex; align-items: center; justify-content: center; gap: 18px; margin-bottom: 14px;">
          
          <div class="step-item step-item--active" id="stepIndicator1" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
            <div class="step-circle" style="width: 34px; height: 34px; border-radius: 50%; background: var(--brand-terra); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; transition: all 0.3s;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span style="font-size: 11px; font-weight: 700; color: var(--text-primary);">1. Info Básica</span>
          </div>

          <div style="width: 36px; height: 2px; background: var(--border-subtle); transition: background 0.3s;" id="stepLine1"></div>

          <div class="step-item" id="stepIndicator2" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
            <div class="step-circle" style="width: 34px; height: 34px; border-radius: 50%; background: var(--surface-sunken); color: var(--text-muted); border: 1.5px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; transition: all 0.3s;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
            </div>
            <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">2. Documentos</span>
          </div>

          <div style="width: 36px; height: 2px; background: var(--border-subtle); transition: background 0.3s;" id="stepLine2"></div>

          <div class="step-item" id="stepIndicator3" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
            <div class="step-circle" style="width: 34px; height: 34px; border-radius: 50%; background: var(--surface-sunken); color: var(--text-muted); border: 1.5px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; transition: all 0.3s;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">3. Localização</span>
          </div>

        </div>

        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 4px;">Criar Conta</h1>
        <p style="font-size: var(--font-xs); color: var(--text-muted); margin: 0;" id="stepSubtitle">Passo 1: Identificação pessoal e contato</p>
      </div>

      <form class="auth-form" id="cadastroForm" action="<?= url('cadastro') ?>" method="POST" enctype="multipart/form-data" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="tipo_contato" id="inputTipoContato" value="<?= e($old['tipoContato'] ?? 'email') ?>">

        <!-- ═════════════════════════════════════════════════════════
             FATOR 1: NOME, ESCOLHA ENTRE E-MAIL OU TELEFONE
             ═════════════════════════════════════════════════════════ -->
        <div class="wizard-step" id="stepSection1">

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

          <!-- Selector: Como deseja criar conta? (E-mail ou Telefone) -->
          <div class="form-field" style="margin-top: 14px;">
            <label class="form-label" style="margin-bottom: 6px;">Como deseja criar conta?</label>
            <div class="contact-mode-toggle" style="display: grid; grid-template-columns: 1fr 1fr; background: var(--surface-sunken); padding: 4px; border-radius: 10px; border: 1px solid var(--border-subtle); gap: 4px;">
              <button type="button" id="btnModeEmail" class="btn-mode-toggle btn-mode-toggle--active" style="padding: 8px 12px; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; background: var(--brand-terra); color: #FFF;">
                <?= icon('mail', 14, '', '#FFF') ?> E-mail
              </button>
              <button type="button" id="btnModeTelefone" class="btn-mode-toggle" style="padding: 8px 12px; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--text-secondary);">
                <?= icon('phone', 14, '', 'currentColor') ?> Telefone
              </button>
            </div>
          </div>

          <!-- Campo de E-mail (Visível quando selecionado E-mail) -->
          <div class="form-field" id="wrapEmailField" style="margin-top: 14px;">
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
                autocomplete="email"
              />
            </div>
            <span class="form-error-msg" id="emailError" aria-live="polite">
              <?= e($errors['email'] ?? '') ?>
            </span>
          </div>

          <!-- Campo de Telefone (Visível quando selecionado Telefone) -->
          <div class="form-field" id="wrapTelefoneField" style="margin-top: 14px; display: none;">
            <label for="regTelefone" class="form-label">Número de Telefone (Angola)</label>
            <div class="form-input-wrap">
              <span class="form-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <input
                type="tel"
                id="regTelefone"
                name="telefone"
                class="form-input <?= !empty($errors['telefone']) ? 'is-invalid' : '' ?>"
                placeholder="+244 923 000 000"
                value="<?= e($old['telefone'] ?? '') ?>"
                autocomplete="tel"
              />
            </div>
            <span class="form-error-msg" id="telefoneError" aria-live="polite">
              <?= e($errors['telefone'] ?? '') ?>
            </span>
          </div>

          <!-- Botão Continuar Passo 1 -->
          <button type="button" class="btn btn--primary btn--full btn--lg" id="btnGoToStep2" style="margin-top: 24px;">
            Continuar para Documentos &rarr;
          </button>

        </div>

        <!-- ═════════════════════════════════════════════════════════
             FATOR 2: DIGITALIZAÇÃO / SCANNER DO BILHETE DE IDENTIDADE
             ═════════════════════════════════════════════════════════ -->
        <div class="wizard-step" id="stepSection2" style="display: none;">

          <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.4;">
            Por favor, digitalize ou carregue a fotografia da frente e do verso do seu <strong>Bilhete de Identidade (BI de Angola)</strong>.
          </p>

          <!-- Upload BI Frente -->
          <div class="bi-upload-card" style="border: 2px dashed var(--brand-terra); border-radius: 12px; padding: 18px; text-align: center; background: rgba(180,69,31,0.03); margin-bottom: 16px; position: relative;">
            <label for="biFrenteInput" style="cursor: pointer; display: block;">
              <div style="width: 44px; height: 44px; margin: 0 auto 8px; border-radius: 50%; background: rgba(180,69,31,0.1); color: var(--brand-terra); display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              </div>
              <strong style="display: block; font-size: 13.5px; color: var(--text-primary); margin-bottom: 2px;">
                Por favor, digitalize a Frente do seu Bilhete de Identidade
              </strong>
              <span style="font-size: 11.5px; color: var(--text-muted);" id="biFrenteLabel">
                Clique para abrir a câmara ou escolher arquivo (JPG, PNG, PDF)
              </span>
            </label>
            <input type="file" id="biFrenteInput" name="bi_frente" accept="image/*,application/pdf" capture="environment" style="display: none;">
            <div id="biFrentePreview" style="display: none; margin-top: 10px; font-size: 12px; color: var(--brand-terra); font-weight: 700;">
              ✓ Frente selecionada com sucesso
            </div>
            <span class="form-error-msg" id="biFrenteError" aria-live="polite">
              <?= e($errors['bi_frente'] ?? '') ?>
            </span>
          </div>

          <!-- Upload BI Verso -->
          <div class="bi-upload-card" style="border: 2px dashed var(--brand-terra); border-radius: 12px; padding: 18px; text-align: center; background: rgba(180,69,31,0.03); margin-bottom: 20px; position: relative;">
            <label for="biVersoInput" style="cursor: pointer; display: block;">
              <div style="width: 44px; height: 44px; margin: 0 auto 8px; border-radius: 50%; background: rgba(180,69,31,0.1); color: var(--brand-terra); display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
              </div>
              <strong style="display: block; font-size: 13.5px; color: var(--text-primary); margin-bottom: 2px;">
                Por favor, digitalize o Verso do seu Bilhete de Identidade
              </strong>
              <span style="font-size: 11.5px; color: var(--text-muted);" id="biVersoLabel">
                Clique para abrir a câmara ou escolher arquivo (JPG, PNG, PDF)
              </span>
            </label>
            <input type="file" id="biVersoInput" name="bi_verso" accept="image/*,application/pdf" capture="environment" style="display: none;">
            <div id="biVersoPreview" style="display: none; margin-top: 10px; font-size: 12px; color: var(--brand-terra); font-weight: 700;">
              ✓ Verso selecionado com sucesso
            </div>
            <span class="form-error-msg" id="biVersoError" aria-live="polite">
              <?= e($errors['bi_verso'] ?? '') ?>
            </span>
          </div>

          <!-- Botões de Navegação Passo 2 -->
          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn--secondary btn--lg" id="btnBackToStep1" style="flex: 1;">
              &larr; Voltar
            </button>
            <button type="button" class="btn btn--primary btn--lg" id="btnGoToStep3" style="flex: 2;">
              Submeter BI e Continuar &rarr;
            </button>
          </div>

        </div>

        <!-- ═════════════════════════════════════════════════════════
             FATOR 3: LOCALIZAÇÃO EM ANGOLA & PALAVRA-PASSE
             ═════════════════════════════════════════════════════════ -->
        <div class="wizard-step" id="stepSection3" style="display: none;">

          <div style="font-size: var(--font-xs); font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
            <span style="display: flex; align-items: center; gap: 6px;">
              <?= icon('map-pin', 14, '', 'var(--brand-terra)') ?> Localização de Residência
            </span>
            <?php if (!empty($municipioAtivoId) && !empty($municipioAtivoNome)): ?>
              <span style="background: rgba(217,154,30,0.15); color: var(--brand-dendem); font-size: 11px; padding: 2px 8px; border-radius: 999px; text-transform: none; font-weight: 600;">
                Lançamento exclusivo: <?= e($municipioAtivoNome) ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Província -->
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

          <!-- Município -->
          <div class="form-field">
            <label for="regMunicipio" class="form-label">Município</label>
            <select id="regMunicipio" name="municipio_id" class="form-select" required>
              <!-- Preenchido dinamicamente via JS -->
            </select>
          </div>

          <!-- Bairro -->
          <div class="form-field">
            <label for="regBairro" class="form-label">Bairro / Distrito Urbano</label>
            <select id="regBairro" name="bairro_id" class="form-select" required>
              <!-- Preenchido dinamicamente via JS -->
            </select>
          </div>

          <!-- Alerta de Restrição Territorial Dinâmica -->
          <div id="territoryRestrictionNotice" style="display: none; background: #FFF4E5; border: 1px solid #F5C6CB; border-left: 4px solid var(--brand-terra); padding: 10px 14px; border-radius: var(--radius-sm); margin-top: 4px; font-size: var(--font-xs); color: #721C24;">
            <div style="display: flex; gap: 8px; align-items: flex-start;">
              <span style="color: var(--brand-terra); font-weight: bold; font-size: 14px;">⚠️</span>
              <div>
                <strong style="display: block; font-weight: 700; margin-bottom: 2px;">Localização não disponível</strong>
                <span id="territoryRestrictionMsg">Esta opção de localização não está disponível de momento. O sistema está disponível apenas para a Administração de Cacuaco.</span>
              </div>
            </div>
          </div>

          <span class="form-error-msg" id="bairroError" aria-live="polite">
            <?= e($errors['bairro'] ?? '') ?>
          </span>

          <!-- Senha & Confirmação com Medidor de Força -->
          <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4); margin-top: 14px;">
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
                  placeholder="Repita a palavra-passe"
                  required
                  autocomplete="new-password"
                />
              </div>
              <span class="form-error-msg" id="regPasswordConfirmError" aria-live="polite">
                <?= e($errors['senha_confirm'] ?? '') ?>
              </span>
            </div>
          </div>

          <!-- Botões de Navegação Passo 3 -->
          <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="button" class="btn btn--secondary btn--lg" id="btnBackToStep2" style="flex: 1;">
              &larr; Voltar
            </button>
            <button type="submit" class="btn btn--primary btn--lg" id="btnSubmitCadastro" style="flex: 2;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Concluir Cadastro
            </button>
          </div>

        </div>

      </form>

      <!-- Barra de Progresso no Rodapé -->
      <div style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--text-muted); margin-bottom: 6px;">
          <span id="progressText">Passo 1 de 3</span>
          <strong id="progressPercent" style="color: var(--brand-terra);">33%</strong>
        </div>
        <div style="height: 6px; background: var(--surface-sunken); border-radius: 999px; overflow: hidden; border: 1px solid var(--border-subtle);">
          <div id="progressBarFill" style="height: 100%; width: 33%; background: linear-gradient(90deg, var(--brand-terra), var(--brand-dendem)); transition: width 0.3s ease;"></div>
        </div>
      </div>

      <div style="text-align: center; padding-top: var(--space-4); margin-top: 14px; border-top: 1px solid var(--border-subtle); font-size: var(--font-sm); color: var(--text-secondary);">
        Já tenho conta? <a href="<?= url('login') ?>" style="font-weight: 700; color: var(--brand-terra);">Iniciar Sessão</a>
      </div>

    </div>
  </div>

</div>

<script>
(function() {
  const municipiosData     = <?= json_encode($municipios ?? []) ?>;
  const bairrosData        = <?= json_encode($bairros ?? []) ?>;
  const municipioAtivoId   = <?= (int) ($municipioAtivoId ?? 0) ?>;
  const municipioAtivoNome = <?= json_encode($municipioAtivoNome ?? '') ?>;

  // Elementos do Wizard
  const step1 = document.getElementById('stepSection1');
  const step2 = document.getElementById('stepSection2');
  const step3 = document.getElementById('stepSection3');

  const ind1 = document.getElementById('stepIndicator1');
  const ind2 = document.getElementById('stepIndicator2');
  const ind3 = document.getElementById('stepIndicator3');

  const line1 = document.getElementById('stepLine1');
  const line2 = document.getElementById('stepLine2');

  const progressPercent = document.getElementById('progressPercent');
  const progressText    = document.getElementById('progressText');
  const progressBarFill = document.getElementById('progressBarFill');
  const stepSubtitle    = document.getElementById('stepSubtitle');

  // Alternador E-mail / Telefone
  const btnModeEmail    = document.getElementById('btnModeEmail');
  const btnModeTelefone = document.getElementById('btnModeTelefone');
  const wrapEmail       = document.getElementById('wrapEmailField');
  const wrapTelefone    = document.getElementById('wrapTelefoneField');
  const inputTipoContato= document.getElementById('inputTipoContato');
  const regEmail        = document.getElementById('regEmail');
  const regTelefone     = document.getElementById('regTelefone');
  const regNome         = document.getElementById('regNome');

  function setContactMode(mode) {
    inputTipoContato.value = mode;
    if (mode === 'telefone') {
      btnModeTelefone.style.background = 'var(--brand-terra)';
      btnModeTelefone.style.color = '#FFF';
      btnModeEmail.style.background = 'transparent';
      btnModeEmail.style.color = 'var(--text-secondary)';
      wrapTelefone.style.display = 'block';
      wrapEmail.style.display = 'none';
      regTelefone.focus();
    } else {
      btnModeEmail.style.background = 'var(--brand-terra)';
      btnModeEmail.style.color = '#FFF';
      btnModeTelefone.style.background = 'transparent';
      btnModeTelefone.style.color = 'var(--text-secondary)';
      wrapEmail.style.display = 'block';
      wrapTelefone.style.display = 'none';
      regEmail.focus();
    }
  }

  btnModeEmail.addEventListener('click', () => setContactMode('email'));
  btnModeTelefone.addEventListener('click', () => setContactMode('telefone'));

  // Se veio erro de telefone anterior, seleciona o modo telefone
  if (inputTipoContato.value === 'telefone') {
    setContactMode('telefone');
  }

  // Previews de Scanner BI
  const biFrenteInput   = document.getElementById('biFrenteInput');
  const biVersoInput    = document.getElementById('biVersoInput');
  const biFrentePreview = document.getElementById('biFrentePreview');
  const biVersoPreview  = document.getElementById('biVersoPreview');
  const biFrenteLabel   = document.getElementById('biFrenteLabel');
  const biVersoLabel    = document.getElementById('biVersoLabel');

  biFrenteInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      biFrenteLabel.textContent = this.files[0].name;
      biFrentePreview.style.display = 'block';
    }
  });

  biVersoInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      biVersoLabel.textContent = this.files[0].name;
      biVersoPreview.style.display = 'block';
    }
  });

  // Navegação entre os 3 Fatores / Passos
  function goToStep(stepNumber) {
    step1.style.display = 'none';
    step2.style.display = 'none';
    step3.style.display = 'none';

    // Reseta indicadores
    [ind1, ind2, ind3].forEach(el => {
      const circle = el.querySelector('.step-circle');
      circle.style.background = 'var(--surface-sunken)';
      circle.style.color = 'var(--text-muted)';
      circle.style.border = '1.5px solid var(--border-subtle)';
      el.querySelector('span').style.color = 'var(--text-muted)';
    });

    line1.style.background = 'var(--border-subtle)';
    line2.style.background = 'var(--border-subtle)';

    if (stepNumber === 1) {
      step1.style.display = 'block';
      setActiveIndicator(ind1);
      stepSubtitle.textContent = 'Passo 1: Identificação pessoal e contato';
      progressPercent.textContent = '33%';
      progressText.textContent = 'Passo 1 de 3';
      progressBarFill.style.width = '33%';
    } else if (stepNumber === 2) {
      step2.style.display = 'block';
      setDoneIndicator(ind1);
      setActiveIndicator(ind2);
      line1.style.background = 'var(--brand-terra)';
      stepSubtitle.textContent = 'Passo 2: Digitalização do Bilhete de Identidade';
      progressPercent.textContent = '66%';
      progressText.textContent = 'Passo 2 de 3';
      progressBarFill.style.width = '66%';
    } else if (stepNumber === 3) {
      step3.style.display = 'block';
      setDoneIndicator(ind1);
      setDoneIndicator(ind2);
      setActiveIndicator(ind3);
      line1.style.background = 'var(--brand-terra)';
      line2.style.background = 'var(--brand-terra)';
      stepSubtitle.textContent = 'Passo 3: Localização de residência e palavra-passe';
      progressPercent.textContent = '100%';
      progressText.textContent = 'Passo 3 de 3';
      progressBarFill.style.width = '100%';
    }
  }

  function setActiveIndicator(ind) {
    const circle = ind.querySelector('.step-circle');
    circle.style.background = 'var(--brand-terra)';
    circle.style.color = '#FFF';
    circle.style.border = 'none';
    ind.querySelector('span').style.color = 'var(--text-primary)';
  }

  function setDoneIndicator(ind) {
    const circle = ind.querySelector('.step-circle');
    circle.style.background = 'var(--brand-dendem)';
    circle.style.color = '#FFF';
    circle.style.border = 'none';
    ind.querySelector('span').style.color = 'var(--text-primary)';
  }

  // Elementos de erro inline
  const nomeError     = document.getElementById('nomeError');
  const emailError    = document.getElementById('emailError');
  const telefoneError = document.getElementById('telefoneError');
  const biFrenteError = document.getElementById('biFrenteError');
  const biVersoError  = document.getElementById('biVersoError');
  const regPassword   = document.getElementById('regPassword');
  const regPasswordConfirm = document.getElementById('regPasswordConfirm');
  const regPasswordError   = document.getElementById('regPasswordError');
  const regPasswordConfirmError = document.getElementById('regPasswordConfirmError');

  function clearErrors() {
    [nomeError, emailError, telefoneError, biFrenteError, biVersoError, regPasswordError, regPasswordConfirmError].forEach(el => {
      if (el) el.textContent = '';
    });
    [regNome, regEmail, regTelefone, regPassword, regPasswordConfirm].forEach(el => {
      if (el) el.classList.remove('is-invalid');
    });
  }

  // Validação dinâmica ao digitar
  regNome.addEventListener('input', function() {
    if (this.value.trim().length >= 2) {
      nomeError.textContent = '';
      this.classList.remove('is-invalid');
    }
  });

  regEmail.addEventListener('input', function() {
    if (this.value.trim().includes('@')) {
      emailError.textContent = '';
      this.classList.remove('is-invalid');
    }
  });

  regTelefone.addEventListener('input', function() {
    const raw = this.value.replace(/\D/g, '');
    if (raw.length >= 9) {
      telefoneError.textContent = '';
      this.classList.remove('is-invalid');
    }
  });

  // Validação do Passo 1 antes de avançar
  document.getElementById('btnGoToStep2').addEventListener('click', function() {
    clearErrors();
    const nomeVal = regNome.value.trim();
    let hasError = false;

    if (!nomeVal) {
      nomeError.textContent = 'Por favor, preencha o seu Nome Completo.';
      regNome.classList.add('is-invalid');
      regNome.focus();
      hasError = true;
    } else if (nomeVal.length < 2) {
      nomeError.textContent = 'O nome deve ter pelo menos 2 caracteres.';
      regNome.classList.add('is-invalid');
      regNome.focus();
      hasError = true;
    } else if (/\d/.test(nomeVal)) {
      nomeError.textContent = 'O nome não deve conter números.';
      regNome.classList.add('is-invalid');
      regNome.focus();
      hasError = true;
    }

    if (inputTipoContato.value === 'telefone') {
      const telClean = regTelefone.value.replace(/\D/g, '');
      if (!telClean) {
        telefoneError.textContent = 'Por favor, informe o seu número de telefone de Angola.';
        regTelefone.classList.add('is-invalid');
        if (!hasError) regTelefone.focus();
        hasError = true;
      } else if (telClean.length < 9) {
        telefoneError.textContent = 'Insira um número de telefone válido (mínimo 9 dígitos).';
        regTelefone.classList.add('is-invalid');
        if (!hasError) regTelefone.focus();
        hasError = true;
      }
    } else {
      const emailVal = regEmail.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailVal) {
        emailError.textContent = 'Por favor, informe o seu correio electrónico.';
        regEmail.classList.add('is-invalid');
        if (!hasError) regEmail.focus();
        hasError = true;
      } else if (!emailRegex.test(emailVal)) {
        emailError.textContent = 'Insira um endereço de e-mail válido (ex: nome@djumbai.ao).';
        regEmail.classList.add('is-invalid');
        if (!hasError) regEmail.focus();
        hasError = true;
      }
    }

    if (!hasError) {
      goToStep(2);
    }
  });

  document.getElementById('btnBackToStep1').addEventListener('click', () => goToStep(1));

  // Validação do Passo 2 (Documentos / Scanner BI)
  document.getElementById('btnGoToStep3').addEventListener('click', function() {
    clearErrors();
    goToStep(3);
  });

  document.getElementById('btnBackToStep2').addEventListener('click', () => goToStep(2));

  // Validação ao Submeter Formulário (Passo 3)
  document.getElementById('cadastroForm').addEventListener('submit', function(e) {
    clearErrors();
    let hasError = false;

    // Checar passo 1 se por acaso foi adulterado
    const nomeVal = regNome.value.trim();
    if (!nomeVal) {
      goToStep(1);
      nomeError.textContent = 'Por favor, preencha o seu Nome Completo.';
      regNome.classList.add('is-invalid');
      regNome.focus();
      e.preventDefault();
      return;
    }

    // Checar localização
    const selBaiVal = selBai.value;
    if (!selBaiVal) {
      bairroError.textContent = 'Por favor, seleccione o seu bairro.';
      selBai.classList.add('is-invalid');
      hasError = true;
    }

    // Checar senha
    const passVal = regPassword.value;
    const confirmVal = regPasswordConfirm.value;

    if (!passVal) {
      regPasswordError.textContent = 'A palavra-passe é obrigatória.';
      regPassword.classList.add('is-invalid');
      hasError = true;
    } else if (passVal.length < 6) {
      regPasswordError.textContent = 'A palavra-passe deve conter pelo menos 6 caracteres.';
      regPassword.classList.add('is-invalid');
      hasError = true;
    }

    if (passVal !== confirmVal) {
      regPasswordConfirmError.textContent = 'As palavras-passe inseridas não coincidem.';
      regPasswordConfirm.classList.add('is-invalid');
      hasError = true;
    }

    if (hasError) {
      e.preventDefault();
    }
  });

  // Geolocation cascading
  const selProv = document.getElementById('regProvincia');
  const selMun  = document.getElementById('regMunicipio');
  const selBai  = document.getElementById('regBairro');
  const noticeBox = document.getElementById('territoryRestrictionNotice');
  const noticeMsg = document.getElementById('territoryRestrictionMsg');
  const bairroError = document.getElementById('bairroError');
  const submitBtn = document.getElementById('btnSubmitCadastro');

  function updateBairros() {
    const selectedMunId = parseInt(selMun.value, 10) || 0;
    selBai.innerHTML = '';

    const filteredBairros = bairrosData.filter(b => parseInt(b.municipio_id, 10) === selectedMunId);

    if (filteredBairros.length > 0) {
      filteredBairros.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.id;
        opt.textContent = b.nome;
        selBai.appendChild(opt);
      });
    } else {
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'Nenhum bairro encontrado';
      selBai.appendChild(opt);
    }

    validateTerritory();
  }

  function updateMunicipios() {
    const selectedProvId = parseInt(selProv.value, 10) || 0;
    selMun.innerHTML = '';

    const filteredMun = municipiosData.filter(m => parseInt(m.provincia_id, 10) === selectedProvId);

    if (filteredMun.length > 0) {
      filteredMun.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.nome;
        if (municipioAtivoId > 0 && parseInt(m.id, 10) === municipioAtivoId) {
          opt.selected = true;
        }
        selMun.appendChild(opt);
      });
    } else {
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'Nenhum município cadastrado';
      selMun.appendChild(opt);
    }

    updateBairros();
  }

  function validateTerritory() {
    const currentMunId = parseInt(selMun.value, 10) || 0;
    const currentMunText = selMun.options[selMun.selectedIndex]?.text || 'seleccionada';

    if (municipioAtivoId > 0 && currentMunId !== municipioAtivoId) {
      noticeMsg.textContent = `A localização de "${currentMunText}" não está disponível de momento. O sistema Djumbai está em fase de lançamento exclusivo para a Administração Municipal de ${municipioAtivoNome}.`;
      noticeBox.style.display = 'block';
      if (bairroError) bairroError.textContent = `Selecione um bairro do município de ${municipioAtivoNome}.`;
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
      }
    } else {
      noticeBox.style.display = 'none';
      if (bairroError && !bairroError.dataset.serverError) {
        bairroError.textContent = '';
      }
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor = 'pointer';
      }
    }
  }

  selProv.addEventListener('change', updateMunicipios);
  selMun.addEventListener('change', updateBairros);
  selBai.addEventListener('change', validateTerritory);

  // Inicialização
  updateMunicipios();

  // Se houver erros do servidor em campos específicos de passos posteriores, navega para o passo correto
  <?php if (!empty($errors['bi_frente']) || !empty($errors['bi_verso'])): ?>
    goToStep(2);
  <?php elseif (!empty($errors['bairro']) || !empty($errors['senha']) || !empty($errors['senha_confirm'])): ?>
    goToStep(3);
  <?php else: ?>
    goToStep(1);
  <?php endif; ?>

})();
</script>
