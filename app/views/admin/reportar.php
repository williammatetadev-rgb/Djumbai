<?php
/**
 * View: admin/reportar.php
 * Central de Registo Oficial de Ocorrências – Painel de Administração Djumbai
 * Mesma arquitetura executiva de ecrã e identidade do Mapa e Comentários.
 */
?>

<div style="display: flex; flex-direction: column; min-height: 100vh;">

  <!-- ══ BARRA SUPERIOR EXECUTIVA (TOPBAR) ════════════════════════════ -->
  <header style="background: var(--surface-card); border-bottom: 1px solid var(--border-subtle); padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; box-shadow: var(--shadow-sm); position: sticky; top: 0; z-index: 100;">
    <div style="display: flex; align-items: center; gap: 14px;">
      <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Voltar às Ocorrências
      </a>
      <div style="height: 24px; width: 1px; background: var(--border-subtle);"></div>
      <div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <h1 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0; line-height: 1.2;">
            Registo Oficial de Ocorrência
          </h1>
          <span style="background: rgba(180,69,31,0.12); color: var(--brand-terra); border: 1px solid rgba(180,69,31,0.3); font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">
            Admin / Despacho
          </span>
        </div>
        <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0;">
          Inserção direta de reporte pelo Centro de Gestão Municipal
        </p>
      </div>
    </div>

    <div style="display: flex; gap: 10px; align-items: center;">
      <a href="<?= url('admin/mapa') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
        Ver no Mapa
      </a>
      <a href="<?= url('admin') ?>" class="btn btn--ghost btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
        Painel Geral
      </a>
    </div>
  </header>

  <!-- ══ CORPO PRINCIPAL COM FORMULÁRIO EXECUTIVO ══════════════════════ -->
  <div style="flex: 1; padding: 32px 24px; display: flex; justify-content: center; align-items: flex-start;">
    <div style="max-width: 860px; width: 100%;">

      <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm);">
        
        <form action="<?= url('admin/reportar') ?>" method="POST" enctype="multipart/form-data" novalidate>
          <?= csrfField() ?>

          <div style="display: flex; flex-direction: column; gap: 24px;">

            <!-- 1. Título & Categoria -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
              
              <div class="form-field">
                <label for="titulo" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
                  Título da Ocorrência *
                </label>
                <input
                  type="text"
                  id="titulo"
                  name="titulo"
                  class="form-input <?= !empty($errors['titulo']) ? 'is-invalid' : '' ?>"
                  placeholder="Ex: Rotura na conduta principal de abastecimento"
                  value="<?= e($old['titulo'] ?? '') ?>"
                  required
                  style="font-size: 13.5px; padding: 10px 14px;"
                />
                <?php if (!empty($errors['titulo'])): ?>
                  <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['titulo']) ?></span>
                <?php endif; ?>
              </div>

              <div class="form-field">
                <label for="categoria_id" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
                  Categoria Técnica *
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
                  <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['categoria']) ?></span>
                <?php endif; ?>
              </div>

            </div>

            <!-- 2. Localização Geográfica -->
            <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 20px;">
              <div style="font-size: 11px; font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Localização Territorial da Ocorrência
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
                  <label for="regBairro" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Bairro / Localidade *</label>
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
                <label for="referencia_local" class="form-label" style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Ponto de Referência ou Arruamento</label>
                <input
                  type="text"
                  id="referencia_local"
                  name="referencia_local"
                  class="form-input"
                  placeholder="Ex: Próximo à Administração Municipal / Marco Geodésico"
                  value="<?= e($old['referenciaLocal'] ?? '') ?>"
                  style="font-size: 13px; padding: 8px 12px;"
                />
              </div>
            </div>

            <!-- 3. Descrição Técnica -->
            <div class="form-field">
              <label for="descricao" class="form-label" style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
                Descrição Detalhada do Problema *
              </label>
              <textarea
                id="descricao"
                name="descricao"
                rows="5"
                class="form-input <?= !empty($errors['descricao']) ? 'is-invalid' : '' ?>"
                placeholder="Insira detalhes da ocorrência, gravidade, extensão do dano e informações técnicas pertinentes..."
                style="resize: vertical; font-size: 13.5px; line-height: 1.6; padding: 12px;"
                required
              ><?= e($old['descricao'] ?? '') ?></textarea>
              <?php if (!empty($errors['descricao'])): ?>
                <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 4px; display: block;"><?= e($errors['descricao']) ?></span>
              <?php endif; ?>
            </div>

            <!-- 4. Fotografia / Evidência -->
            <div class="form-field" style="background: var(--surface-sunken); border: 1px dashed var(--border-subtle); border-radius: 12px; padding: 20px; text-align: center;">
              <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(31,111,107,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              </div>

              <label for="foto" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; cursor: pointer;">
                Anexar Fotografia de Registo Técnico
              </label>
              
              <p style="font-size: 11.5px; color: var(--text-muted); margin: 0 0 14px;">
                Formatos suportados: JPG, PNG ou WebP (máximo 5 MB).
              </p>

              <input
                type="file"
                id="foto"
                name="foto"
                accept="image/jpeg,image/png,image/webp"
                style="font-size: 12px; color: var(--text-secondary);"
              />
              <?php if (!empty($errors['foto'])): ?>
                <span style="color: var(--brand-terra); font-size: 11px; font-weight: 600; margin-top: 6px; display: block;"><?= e($errors['foto']) ?></span>
              <?php endif; ?>
            </div>

            <!-- 5. Rodapé de Ações -->
            <div style="display: flex; gap: 12px; align-items: center; justify-content: flex-end; padding-top: 14px; border-top: 1px solid var(--border-subtle);">
              <a href="<?= url('admin/problemas') ?>" class="btn btn--ghost btn--sm">Cancelar</a>
              <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; background: var(--brand-terra); color: #FFF; border: none; padding: 11px 26px; border-radius: 9px; font-size: 13.5px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(180,69,31,0.3); transition: filter 0.15s;" onmouseenter="this.style.filter='brightness(1.08)'" onmouseleave="this.style.filter='none'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Registar e Publicar no Painel
              </button>
            </div>

          </div>

        </form>

      </div>

    </div>
  </div>

</div>
