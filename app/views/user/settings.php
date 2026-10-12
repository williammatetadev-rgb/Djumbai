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
          Atualize os seus dados pessoais de contacto e verificação cívica na plataforma Djumbai.
        </p>
      </div>
    </div>
  </div>

  <?php
    $biStatus = $perfil['bi_status'] ?? 'nao_enviado';
    $temBi = !empty($perfil['bi_frente']) && !empty($perfil['bi_verso']);
    if ($temBi && $biStatus === 'nao_enviado') {
        $biStatus = 'pendente';
    }

    $biBadge = match($biStatus) {
        'verificado'  => ['label' => 'Selo Verificado ✓', 'bg' => 'rgba(76,175,80,0.15)', 'color' => '#2E7D32', 'border' => '#4CAF50'],
        'pendente'    => ['label' => 'Em Análise pela Moderação ⏳', 'bg' => 'rgba(217,154,30,0.15)', 'color' => '#B78103', 'border' => 'var(--brand-dendem)'],
        'rejeitado'   => ['label' => 'Documento Rejeitado ✗', 'bg' => 'rgba(229,57,53,0.12)', 'color' => '#C62828', 'border' => '#E53935'],
        default       => ['label' => 'Não Enviado', 'bg' => 'rgba(107,90,74,0.12)', 'color' => '#6B5A4A', 'border' => 'var(--border-subtle)'],
    };
  ?>

  <!-- ══ CARD DE VERIFICAÇÃO DO BILHETE DE IDENTIDADE (BI) ═══════════ -->
  <div style="background: var(--surface-card); border: 1px solid <?= $biBadge['border'] ?>55; border-radius: 16px; padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; bottom: 0; width: 5px; background: <?= $biBadge['border'] ?>;"></div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; align-items: flex-start; gap: 14px;">
        <div style="width: 44px; height: 44px; border-radius: 12px; background: <?= $biBadge['bg'] ?>; display: flex; align-items: center; justify-content: center; color: <?= $biBadge['color'] ?>; flex-shrink: 0;">
          <?php if ($biStatus === 'verificado'): ?>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <?php elseif ($biStatus === 'rejeitado'): ?>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          <?php else: ?>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
          <?php endif; ?>
        </div>
        <div>
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
            <strong style="font-size: 1rem; color: var(--text-primary); font-weight: 800;">
              Verificação do Bilhete de Identidade (BI)
            </strong>
            <span style="background: <?= $biBadge['bg'] ?>; color: <?= $biBadge['color'] ?>; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 12px; border: 1px solid <?= $biBadge['border'] ?>55;">
              <?= $biBadge['label'] ?>
            </span>
          </div>
          <p style="font-size: 13px; color: var(--text-secondary); margin: 0; line-height: 1.45;">
            <?php if ($biStatus === 'verificado'): ?>
              A sua conta possui <strong>Selo Cívico Verificado</strong>. Os seus reportes têm prioridade alta de despacho nos serviços municipais.
            <?php elseif ($biStatus === 'pendente'): ?>
              Os seus documentos foram submetidos com sucesso e aguardam validação pela equipa administrativa municipal.
            <?php elseif ($biStatus === 'rejeitado'): ?>
              <span style="color: #C62828; font-weight: 600;">O seu documento foi rejeitado pela moderação.</span>
              <?php if (!empty($perfil['bi_nota'])): ?>
                <br><strong style="color: var(--text-primary);">Motivo apontado:</strong> "<?= e($perfil['bi_nota']) ?>"
              <?php endif; ?>
              <br>Por favor submeta novamente fotos nítidas ou digitalização legível do documento.
            <?php else: ?>
              Para reportar problemas comunitários ou interagir ativamente na comunidade, é necessário submeter o seu Bilhete de Identidade (frente e verso).
            <?php endif; ?>
          </p>
        </div>
      </div>

      <div>
        <?php if ($biStatus === 'verificado'): ?>
          <button type="button" onclick="openBiVerifyModal()" class="btn btn--ghost btn--sm" style="font-size: 12px;">
            Substituir Documentos
          </button>
        <?php elseif ($biStatus === 'rejeitado'): ?>
          <button type="button" onclick="openBiVerifyModal()" style="display: inline-flex; align-items: center; gap: 8px; background: #C62828; color: #FFF; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(198,40,40,0.3); transition: all 0.2s;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
            Reenviar Documento de BI
          </button>
        <?php elseif ($biStatus === 'pendente'): ?>
          <button type="button" onclick="openBiVerifyModal()" class="btn btn--ghost btn--sm" style="font-size: 12px;">
            Reenviar Novas Fotos
          </button>
        <?php else: ?>
          <button type="button" onclick="openBiVerifyModal()" style="display: inline-flex; align-items: center; gap: 8px; background: var(--brand-terra); color: #FFF; border: none; padding: 11px 22px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(180,69,31,0.3); transition: all 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Terminar a Verificação
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Card das Definições Pessoais -->
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
            Guardar Alterações Pessoais
          </button>
        </div>

      </div>

    </form>

  </div>

  <!-- ══ CARD DE SEGURANÇA & ALTERAÇÃO DE PALAVRA-PASSE ═══════════════ -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm); margin-top: 24px;">
    
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-subtle);">
      <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(217,154,30,0.12); border: 1px solid rgba(217,154,30,0.25); display: flex; align-items: center; justify-content: center; color: var(--brand-dendem);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <div>
        <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0;">
          Segurança & Palavra-passe
        </h2>
        <p style="font-size: 12.5px; color: var(--text-muted); margin: 2px 0 0;">
          Mantenha a sua conta segura atualizando regularmente a sua chave de acesso.
        </p>
      </div>
    </div>

    <?php $erroSenha = getFlash('error_senha'); ?>
    <?php if ($erroSenha): ?>
      <div style="background: rgba(229,57,53,0.1); border: 1px solid rgba(229,57,53,0.3); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #C62828; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <?= e($erroSenha) ?>
      </div>
    <?php endif; ?>

    <form action="<?= url('perfil/alterar-senha') ?>" method="POST">
      <?= csrfField() ?>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <!-- Senha Atual -->
        <div class="form-field">
          <label for="senha_atual" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Palavra-passe Atual *
          </label>
          <input
            type="password"
            id="senha_atual"
            name="senha_atual"
            class="form-input"
            required
            autocomplete="current-password"
            placeholder="A sua senha atual"
            style="font-size: 13.5px; padding: 10px 14px;"
          />
        </div>

        <!-- Nova Senha -->
        <div class="form-field">
          <label for="nova_senha" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Nova Palavra-passe *
          </label>
          <input
            type="password"
            id="nova_senha"
            name="nova_senha"
            class="form-input"
            required
            minlength="8"
            autocomplete="new-password"
            placeholder="Mínimo 8 caracteres (letras e números)"
            style="font-size: 13.5px; padding: 10px 14px;"
          />
        </div>

        <!-- Confirma Nova Senha -->
        <div class="form-field">
          <label for="confirma_senha" class="form-label" style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Confirmar Nova Palavra-passe *
          </label>
          <input
            type="password"
            id="confirma_senha"
            name="confirma_senha"
            class="form-input"
            required
            minlength="8"
            autocomplete="new-password"
            placeholder="Repita a nova palavra-passe"
            style="font-size: 13.5px; padding: 10px 14px;"
          />
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end;">
        <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; background: var(--surface-sunken); border: 1px solid var(--border-subtle); color: var(--text-primary); padding: 10px 22px; border-radius: 9px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseenter="this.style.background='var(--brand-dendem)';this.style.color='#1A1410';this.style.borderColor='var(--brand-dendem)'" onmouseleave="this.style.background='var(--surface-sunken)';this.style.color='var(--text-primary)';this.style.borderColor='var(--border-subtle)'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Atualizar Palavra-passe
        </button>
      </div>

    </form>

  </div>

</div>

<!-- ══ MODAL: TERMINAR A VERIFICAÇÃO DO BI (UPLOAD OU SCANNER) ════════ -->
<div id="biVerifyModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,10,8,0.75);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;">
  <div class="no-scrollbar" style="background:var(--surface-card);max-width:560px;width:100%;max-height:92vh;overflow-y:auto;border-radius:18px;box-shadow:0 32px 64px rgba(0,0,0,0.35);border:1px solid var(--border-subtle);animation:scaleUp 0.3s cubic-bezier(0.16,1,0.3,1);scrollbar-width:none;-ms-overflow-style:none;">

    <!-- Header do Modal -->
    <div style="background:linear-gradient(135deg,#1A1410,#2D221A);padding:20px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid var(--brand-terra);">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(180,69,31,0.2);border:1px solid rgba(180,69,31,0.4);display:flex;align-items:center;justify-content:center;color:var(--brand-terra);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
        </div>
        <div>
          <div style="font-size:10.5px;font-weight:700;color:var(--brand-dendem);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:2px;">Verificação Cívica</div>
          <h3 style="margin:0;font-size:1.15rem;font-weight:800;color:#FFF;">Terminar a Verificação de Identidade</h3>
        </div>
      </div>
      <button onclick="closeBiVerifyModal()" style="background:rgba(255,255,255,0.08);border:none;color:rgba(255,255,255,0.7);width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.15s;" onmouseenter="this.style.background='rgba(255,255,255,0.15)';this.style.color='#FFF'" onmouseleave="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.7)'">&times;</button>
    </div>

    <!-- Corpo do Modal -->
    <div style="padding:24px;">

      <!-- Seletor de Modo: Upload dos 2 Arquivos VS Scanner por Câmara -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;">
        <button type="button" id="tabModeUpload" onclick="setBiMode('upload')" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:2px solid var(--brand-terra);background:rgba(180,69,31,0.08);color:var(--brand-terra);font-weight:700;font-size:13px;cursor:pointer;transition:all 0.15s;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Upload das Fotos
        </button>
        <button type="button" id="tabModeScanner" onclick="setBiMode('scanner')" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:1px solid var(--border-subtle);background:var(--surface-sunken);color:var(--text-secondary);font-weight:700;font-size:13px;cursor:pointer;transition:all 0.15s;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          Scanner o BI
        </button>
      </div>

      <form id="biVerifyForm" action="<?= url('perfil/verificacao-bi') ?>" method="POST" enctype="multipart/form-data">
        <?= csrfField() ?>

        <!-- Hidden inputs para capturas do Scanner Base64 -->
        <input type="hidden" name="bi_frente_base64" id="biFrenteBase64" value="">
        <input type="hidden" name="bi_verso_base64" id="biVersoBase64" value="">

        <!-- ── MODO 1: UPLOAD DOS DOIS FICHEIROS (FRENTE E VERSO) ── -->
        <div id="sectionBiUpload">
          <p style="font-size:12.5px;color:var(--text-muted);margin:0 0 16px;line-height:1.4;">
            Selecione as imagens do seu Bilhete de Identidade a partir do seu dispositivo (Formatos: JPG, PNG ou PDF, até 10MB).
          </p>

          <!-- Upload Frente -->
          <div style="border:2px dashed var(--border-subtle);border-radius:12px;padding:16px;text-align:center;margin-bottom:14px;background:var(--surface-sunken);transition:border-color 0.2s;" id="cardUploadFrente">
            <label for="modalBiFrenteInput" style="cursor:pointer;display:block;">
              <div style="width:38px;height:38px;margin:0 auto 8px;border-radius:50%;background:rgba(31,111,107,0.1);color:var(--brand-baia);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              </div>
              <strong style="display:block;font-size:13px;color:var(--text-primary);margin-bottom:2px;">Frente do Bilhete de Identidade</strong>
              <span id="modalBiFrenteLabel" style="font-size:11px;color:var(--text-muted);">Clique para escolher o ficheiro da Frente</span>
            </label>
            <input type="file" id="modalBiFrenteInput" name="bi_frente" accept="image/*,application/pdf" style="display:none;" onchange="handleModalFileSelect(this, 'modalBiFrenteLabel', 'cardUploadFrente')">
          </div>

          <!-- Upload Verso -->
          <div style="border:2px dashed var(--border-subtle);border-radius:12px;padding:16px;text-align:center;margin-bottom:20px;background:var(--surface-sunken);transition:border-color 0.2s;" id="cardUploadVerso">
            <label for="modalBiVersoInput" style="cursor:pointer;display:block;">
              <div style="width:38px;height:38px;margin:0 auto 8px;border-radius:50%;background:rgba(31,111,107,0.1);color:var(--brand-baia);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              </div>
              <strong style="display:block;font-size:13px;color:var(--text-primary);margin-bottom:2px;">Verso do Bilhete de Identidade</strong>
              <span id="modalBiVersoLabel" style="font-size:11px;color:var(--text-muted);">Clique para escolher o ficheiro do Verso</span>
            </label>
            <input type="file" id="modalBiVersoInput" name="bi_verso" accept="image/*,application/pdf" style="display:none;" onchange="handleModalFileSelect(this, 'modalBiVersoLabel', 'cardUploadVerso')">
          </div>
        </div>

        <!-- ── MODO 2: SCANNER / CÂMARA DO BI ── -->
        <div id="sectionBiScanner" style="display:none;">

          <!-- Opção: Vídeo Webcam ao Vivo -->
          <div style="background:var(--surface-sunken);border:1px solid var(--border-subtle);border-radius:12px;padding:16px;margin-bottom:14px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
              <span style="font-size:12.5px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                Webcam ao Vivo (Vídeo Contínuo)
              </span>
              <button type="button" id="btnToggleLiveWebcam" onclick="toggleLiveWebcam()" style="font-size:12px;font-weight:700;color:var(--brand-baia);background:none;border:none;cursor:pointer;text-decoration:underline;">
                Ativar Webcam
              </button>
            </div>

            <!-- Ecrã de vídeo da Webcam e pré-visualização das capturas -->
            <div id="liveWebcamArea" style="display:none;margin-top:12px;">
              <div style="position:relative;background:#000;border-radius:10px;overflow:hidden;aspect-ratio:16/10;margin-bottom:12px;border:1px solid var(--border-subtle);">
                <video id="scannerVideo" autoplay playsinline style="width:100%;height:100%;object-fit:cover;"></video>
                <canvas id="scannerCanvas" style="display:none;"></canvas>
                <div style="position:absolute;inset:15px;border:2px dashed rgba(217,154,30,0.85);border-radius:8px;pointer-events:none;"></div>
              </div>
              <div style="display:flex;gap:10px;margin-bottom:14px;">
                <button type="button" onclick="captureLiveWebcam('frente')" style="flex:1;background:var(--brand-terra);color:#FFF;border:none;padding:10px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(180,69,31,0.25);">
                  📸 Capturar Frente do BI
                </button>
                <button type="button" onclick="captureLiveWebcam('verso')" style="flex:1;background:var(--brand-baia);color:#FFF;border:none;padding:10px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(31,111,107,0.25);">
                  📸 Capturar Verso do BI
                </button>
              </div>

              <!-- Miniaturas das capturas realizadas -->
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                <div style="border:1px solid var(--border-subtle);border-radius:8px;padding:8px;background:var(--surface-card);text-align:center;">
                  <span style="font-size:11px;font-weight:700;color:var(--text-secondary);display:block;margin-bottom:4px;">Frente Capturada</span>
                  <div id="previewFrenteBox" style="height:70px;background:#1A1410;border-radius:6px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:10.5px;overflow:hidden;">
                    Sem captura
                  </div>
                </div>
                <div style="border:1px solid var(--border-subtle);border-radius:8px;padding:8px;background:var(--surface-card);text-align:center;">
                  <span style="font-size:11px;font-weight:700;color:var(--text-secondary);display:block;margin-bottom:4px;">Verso Capturado</span>
                  <div id="previewVersoBox" style="height:70px;background:#1A1410;border-radius:6px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:10.5px;overflow:hidden;">
                    Sem captura
                  </div>
                </div>
              </div>
            </div>

            <div id="webcamHelpTip" style="font-size:11.5px;color:var(--text-muted);line-height:1.4;margin-top:6px;">
              💡 <em>Dica:</em> No telemóvel, os botões "Abrir Câmara" acima abrem a câmara fotográfica nativa com nitidez superior.
            </div>
          </div>
        </div>

        <!-- Botões do Rodapé do Formulário -->
        <div style="display:flex;gap:12px;justify-content:flex-end;align-items:center;padding-top:16px;border-top:1px solid var(--border-subtle);">
          <button type="button" onclick="closeBiVerifyModal()" class="btn btn--ghost btn--sm">Cancelar</button>
          <button type="submit" style="display:inline-flex;align-items:center;gap:8px;background:var(--brand-terra);color:#FFF;border:none;padding:11px 24px;border-radius:9px;font-size:13.5px;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(180,69,31,0.3);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Submeter Documentos
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
let currentScannerStream = null;

function openBiVerifyModal() {
  document.getElementById('biVerifyModal').style.display = 'flex';
}

function closeBiVerifyModal() {
  stopScannerCamera();
  document.getElementById('biVerifyModal').style.display = 'none';
}

document.getElementById('biVerifyModal').addEventListener('click', function(e) {
  if (e.target === this) closeBiVerifyModal();
});

function setBiMode(mode) {
  const btnUpload  = document.getElementById('tabModeUpload');
  const btnScanner = document.getElementById('tabModeScanner');
  const secUpload  = document.getElementById('sectionBiUpload');
  const secScanner = document.getElementById('sectionBiScanner');

  if (mode === 'upload') {
    btnUpload.style.border = '2px solid var(--brand-terra)';
    btnUpload.style.background = 'rgba(180,69,31,0.08)';
    btnUpload.style.color = 'var(--brand-terra)';
    btnScanner.style.border = '1px solid var(--border-subtle)';
    btnScanner.style.background = 'var(--surface-sunken)';
    btnScanner.style.color = 'var(--text-secondary)';
    secUpload.style.display = 'block';
    secScanner.style.display = 'none';
    stopLiveWebcam();
  } else {
    btnScanner.style.border = '2px solid var(--brand-terra)';
    btnScanner.style.background = 'rgba(180,69,31,0.08)';
    btnScanner.style.color = 'var(--brand-terra)';
    btnUpload.style.border = '1px solid var(--border-subtle)';
    btnUpload.style.background = 'var(--surface-sunken)';
    btnUpload.style.color = 'var(--text-secondary)';
    secUpload.style.display = 'none';
    secScanner.style.display = 'block';
  }
}

function handleModalFileSelect(input, labelId, cardId) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    document.getElementById(labelId).textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
    document.getElementById(cardId).style.borderColor = 'var(--brand-terra)';
    document.getElementById(cardId).style.background = 'rgba(180,69,31,0.04)';
  }
}

// ── CAPTURA DIRETA / CÂMARA NATIVA (FUNCIONA EM TODOS OS DISPOSITIVOS) ──
function handleNativeCameraCapture(input, side) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
      const dataUrl = e.target.result;
      if (side === 'frente') {
        document.getElementById('biFrenteBase64').value = dataUrl;
        document.getElementById('previewFrenteBox').innerHTML = `<img src="${dataUrl}" style="width:100%;height:100%;object-fit:cover;">`;
      } else {
        document.getElementById('biVersoBase64').value = dataUrl;
        document.getElementById('previewVersoBox').innerHTML = `<img src="${dataUrl}" style="width:100%;height:100%;object-fit:cover;">`;
      }
    };
    reader.readAsDataURL(file);
  }
}

// ── WEBCAM EM TEMPO REAL OPCIONAL (PARA QUEM TEM PERMISSÃO HABILITADA) ──
async function toggleLiveWebcam() {
  const area = document.getElementById('liveWebcamArea');
  const btn = document.getElementById('btnToggleLiveWebcam');
  if (area.style.display === 'block') {
    stopLiveWebcam();
  } else {
    try {
      const video = document.getElementById('scannerVideo');
      let stream = null;
      try {
        stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }
        });
      } catch (e1) {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
      }

      currentScannerStream = stream;
      video.srcObject = stream;
      area.style.display = 'block';
      btn.textContent = 'Desligar Webcam';
      btn.style.color = 'var(--brand-terra)';
    } catch (err) {
      alert('Não foi possível aceder à webcam ao vivo. Utilize os botões "Abrir Câmara" acima para tirar a foto diretamente.');
    }
  }
}

function stopLiveWebcam() {
  if (currentScannerStream) {
    currentScannerStream.getTracks().forEach(track => track.stop());
    currentScannerStream = null;
  }
  const area = document.getElementById('liveWebcamArea');
  const btn = document.getElementById('btnToggleLiveWebcam');
  if (area) area.style.display = 'none';
  if (btn) {
    btn.textContent = 'Ativar Webcam';
    btn.style.color = 'var(--brand-baia)';
  }
}

function captureLiveWebcam(side) {
  const video = document.getElementById('scannerVideo');
  const canvas = document.getElementById('scannerCanvas');
  if (!video || !video.videoWidth) {
    alert('Aguarde até o vídeo carregar.');
    return;
  }

  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

  if (side === 'frente') {
    document.getElementById('biFrenteBase64').value = dataUrl;
    document.getElementById('previewFrenteBox').innerHTML = `<img src="${dataUrl}" style="width:100%;height:100%;object-fit:cover;">`;
  } else {
    document.getElementById('biVersoBase64').value = dataUrl;
    document.getElementById('previewVersoBox').innerHTML = `<img src="${dataUrl}" style="width:100%;height:100%;object-fit:cover;">`;
  }
}

function closeBiVerifyModal() {
  stopLiveWebcam();
  document.getElementById('biVerifyModal').style.display = 'none';
}
</script>

<style>
.no-scrollbar::-webkit-scrollbar {
  display: none !important;
  width: 0 !important;
  height: 0 !important;
}
</style>
