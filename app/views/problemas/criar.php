<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: problemas/criar.php (Redesenho Profissional)      ║
     ╚══════════════════════════════════════════════════════════╝ -->

<div class="container" style="padding-block: var(--space-8); max-width: 820px;">

  <!-- Breadcrumb -->
  <nav aria-label="Navegação de localização" style="margin-bottom: var(--space-6);">
    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted);">
      <a href="<?= (isLoggedIn() && !isAdmin()) ? url('perfil') : url() ?>" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Início
      </a>
      <span style="color: var(--border-subtle);">/</span>
      <a href="<?= url('problemas') ?>" style="color: var(--text-muted); text-decoration: none;">Ocorrências</a>
      <span style="color: var(--border-subtle);">/</span>
      <span style="color: var(--text-primary); font-weight: 600;">Reportar Ocorrência</span>
    </div>
  </nav>

  <!-- Cabeçalho -->
  <div style="margin-bottom: 28px;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
      <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, var(--brand-terra), #8B2500); display: flex; align-items: center; justify-content: center; color: #FFF; box-shadow: 0 4px 12px rgba(180,69,31,0.3);">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </div>
      <div>
        <h1 style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0; line-height: 1.2;">
          Reportar uma Ocorrência Comunitária
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 3px 0 0;">
          Documente as dificuldades do seu bairro para mobilizar vizinhos e informar os gestores municipais.
        </p>
      </div>
    </div>
  </div>

  <!-- Card do Formulário -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm);">

    <form action="<?= url('reportar') ?>" method="POST" enctype="multipart/form-data" novalidate>
      <?= csrfField() ?>

      <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- 1. Título & Categoria -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; @media(max-width:640px){grid-template-columns:1fr;}">
          
          <div class="form-field">
            <label for="titulo" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
              Título Resumido *
            </label>
            <input
              type="text"
              id="titulo"
              name="titulo"
              class="form-input <?= !empty($errors['titulo']) ? 'is-invalid' : '' ?>"
              placeholder="Ex: Falha na iluminação pública da rua principal"
              value="<?= e($old['titulo'] ?? '') ?>"
              required
              style="font-size: 13.5px; padding: 10px 14px;"
            />
            <?php if (!empty($errors['titulo'])): ?>
              <span class="form-error-msg" style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['titulo']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-field">
            <label for="categoria_id" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
              Categoria *
            </label>
            <select id="categoria_id" name="categoria_id" class="form-select <?= !empty($errors['categoria']) ? 'is-invalid' : '' ?>" required style="font-size: 13.5px; padding: 10px 14px;">
              <option value="">Selecione a categoria...</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= e($cat['id']) ?>" <?= ((int)($old['categoria_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                  <?= e($cat['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['categoria'])): ?>
              <span class="form-error-msg" style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['categoria']) ?></span>
            <?php endif; ?>
          </div>

        </div>

        <!-- 2. Localização -->
        <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 20px;">
          <div style="font-size: 11px; font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Localização da Ocorrência
          </div>

          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 14px;">
            <div class="form-field">
              <label for="regProvincia" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Província</label>
              <select id="regProvincia" name="provincia_id" class="form-select" required style="font-size: 13px; padding: 8px 12px;">
                <?php foreach ($provincias as $p): ?>
                  <option value="<?= e($p['id']) ?>" <?= (int)$p['id'] === 1 ? 'selected' : '' ?>>
                    <?= e($p['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-field">
              <label for="regMunicipio" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Município</label>
              <select id="regMunicipio" name="municipio_id" class="form-select" required style="font-size: 13px; padding: 8px 12px;">
                <option value="1" selected>Luanda (Sede)</option>
                <option value="2">Belas</option>
                <option value="3">Cacuaco</option>
                <option value="4">Cazenga</option>
                <option value="5">Kilamba Kiaxi</option>
                <option value="6">Viana</option>
              </select>
            </div>

            <div class="form-field">
              <label for="regBairro" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Bairro / Local *</label>
              <select id="regBairro" name="bairro_id" class="form-select" required style="font-size: 13px; padding: 8px 12px;">
                <option value="1" selected>Alvalade</option>
                <option value="2">Ingombota</option>
                <option value="3">Maianga</option>
                <option value="4">Rangel</option>
                <option value="5">Sambizanga</option>
                <option value="6">Samba</option>
              </select>
            </div>
          </div>

          <div class="form-field">
            <label for="referencia_local" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Ponto de Referência ou Rua (Opcional)</label>
            <input
              type="text"
              id="referencia_local"
              name="referencia_local"
              class="form-input"
              placeholder="Ex: Em frente ao Mercado da Samba, próximo à paragem do candongueiro"
              value="<?= e($old['referenciaLocal'] ?? '') ?>"
              style="font-size: 13px; padding: 8px 12px;"
            />
          </div>
        </div>

        <!-- 3. Descrição Detalhada -->
        <div class="form-field">
          <label for="descricao" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
            Descrição Detalhada do Problema *
          </label>
          <textarea
            id="descricao"
            name="descricao"
            rows="5"
            class="form-input <?= !empty($errors['descricao']) ? 'is-invalid' : '' ?>"
            placeholder="Descreva detalhadamente o que está a acontecer, desde quando ocorre e o impacto para os moradores..."
            style="resize: vertical; font-size: 13.5px; line-height: 1.6; padding: 12px;"
            required
          ><?= e($old['descricao'] ?? '') ?></textarea>
          <?php if (!empty($errors['descricao'])): ?>
            <span class="form-error-msg" style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['descricao']) ?></span>
          <?php endif; ?>
        </div>

        <!-- 4. Fotografia -->
        <div class="form-field" style="background: var(--surface-sunken); border: 1px dashed var(--border-subtle); border-radius: 12px; padding: 20px; text-align: center;">
          <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(31,111,107,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </div>

          <label for="foto" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; cursor: pointer;">
            Adicionar Fotografia de Evidência
          </label>
          
          <p style="font-size: 11.5px; color: var(--text-muted); margin: 0 0 14px;">
            Formatos aceites: JPG, PNG ou WebP (tamanho máximo: 5 MB).
          </p>

          <input
            type="file"
            id="foto"
            name="foto"
            accept="image/jpeg,image/png,image/webp"
            style="font-size: 12px; color: var(--text-secondary);"
          />
          <?php if (!empty($errors['foto'])): ?>
            <span class="form-error-msg" style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 6px; display: block;"><?= e($errors['foto']) ?></span>
          <?php endif; ?>
        </div>

        <!-- 5. Botões de Ação -->
        <div style="display: flex; gap: 12px; align-items: center; justify-content: flex-end; padding-top: 12px; border-top: 1px solid var(--border-subtle);">
          <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--sm">Cancelar</a>
          <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; background: var(--brand-terra); color: #FFF; border: none; padding: 11px 24px; border-radius: 9px; font-size: 13.5px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(180,69,31,0.3); transition: transform 0.15s;" onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Submeter Ocorrência
          </button>
        </div>

      </div>

    </form>

  </div>

</div>
