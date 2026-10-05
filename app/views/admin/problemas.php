<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: admin/problemas.php (Gestão e Despacho)           ║
     ╚══════════════════════════════════════════════════════════╝ -->

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
  <div>
    <span style="font-size: var(--font-xs); font-weight: 700; color: var(--brand-terra); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px;">
      Administração & Triage
    </span>
    <h1 style="font-size: var(--font-2xl); font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1.1;">
      Gestão de Ocorrências
    </h1>
    <p style="color: var(--text-secondary); font-size: var(--font-sm); margin-top: 4px;">
      Altere os estados de resolução, anexe notas de despacho para as brigadas e mantenha os munícipes informados.
    </p>
  </div>

  <div class="pulse-pill" style="padding: 4px 12px; font-size: 11px;">
    <span><?= number_format($total) ?> Total Filtrado</span>
  </div>
</div>

<!-- ── BARRA DE FILTROS AVANÇADA ───────────────────────────── -->
<div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-5); margin-bottom: var(--space-6); box-shadow: var(--shadow-raised); display: flex; flex-wrap: wrap; gap: var(--space-4); align-items: center; justify-content: space-between;">
  
  <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
    <span style="font-size: var(--font-xs); font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Estado:</span>
    <a href="<?= url('admin/problemas?categoria=' . ($filtros['categoria'] ?? 0) . '&estado=0') ?>" 
       class="filter-pill <?= empty($filtros['estado']) ? 'is-active' : '' ?>">
      Todos
    </a>
    <?php foreach ($estados ?? [] as $est): ?>
      <a href="<?= url('admin/problemas?categoria=' . ($filtros['categoria'] ?? 0) . '&estado=' . $est['id']) ?>" 
         class="filter-pill <?= ((int)($filtros['estado'] ?? 0) === (int)$est['id']) ? 'is-active' : '' ?>">
        <?= e($est['nome']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <div style="display: flex; gap: 10px; align-items: center;">
    <span style="font-size: var(--font-xs); font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Categoria:</span>
    <select onchange="location = this.value;" class="form-select" style="padding: 6px 30px 6px 12px; font-size: var(--font-xs); width: auto;">
      <option value="<?= url('admin/problemas?categoria=0&estado=' . ($filtros['estado'] ?? 0)) ?>" <?= empty($filtros['categoria']) ? 'selected' : '' ?>>Todas as Categorias</option>
      <?php foreach ($categorias as $cat): ?>
        <option value="<?= url('admin/problemas?categoria=' . $cat['id'] . '&estado=' . ($filtros['estado'] ?? 0)) ?>" <?= ((int)($filtros['categoria'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
          <?= e($cat['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

</div>

<!-- ── TABELA DE GESTÃO E DESPACHO ─────────────────────────── -->
<div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-6); box-shadow: var(--shadow-raised);">

  <?php if (empty($problemas)): ?>
    <div style="text-align: center; padding: var(--space-12) var(--space-4);">
      <div style="margin-bottom: 12px; display: flex; justify-content: center;"><?= icon('clipboard', 40, '', 'var(--brand-terra)') ?></div>
      <h3 style="font-size: var(--font-md); font-weight: 700;">Nenhuma ocorrência encontrada</h3>
      <p style="font-size: var(--font-xs); color: var(--text-secondary); margin-top: 4px;">Tente alterar os filtros para visualizar outros reportes.</p>
    </div>
  <?php else: ?>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: var(--font-sm);">
        <thead>
          <tr style="border-bottom: 2px solid var(--border-subtle); color: var(--text-muted); font-size: var(--font-xs); text-transform: uppercase;">
            <th style="padding: 12px 10px;">Ocorrência</th>
            <th style="padding: 12px 10px;">Localização</th>
            <th style="padding: 12px 10px;">Categoria</th>
            <th style="padding: 12px 10px;">Apoio</th>
            <th style="padding: 12px 10px;">Despacho de Estado & Nota</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($problemas as $p): ?>
            <tr style="border-bottom: 1px solid var(--border-subtle); transition: background 0.15s ease;">
              <td style="padding: 16px 10px; vertical-align: top; max-width: 280px;">
                <a href="<?= url('problemas/' . $p['id']) ?>" target="_blank" style="font-weight: 700; color: var(--text-primary); text-decoration: none;">
                  <?= e($p['titulo']) ?>
                </a>
                <span style="display: block; font-size: 11px; color: var(--text-muted); margin-top: 3px;">
                  Por <?= e($p['autor_nome']) ?> • <?= timeAgo($p['criado_em']) ?>
                </span>
                <?php if (!empty($p['foto'])): ?>
                  <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: var(--brand-baia); background: var(--brand-baia-subtle); padding: 2px 6px; border-radius: 4px; margin-top: 4px;">
                    <?= icon('camera', 12, '', 'var(--brand-baia)') ?> Tem Fotografia
                  </span>
                <?php endif; ?>
              </td>

              <td style="padding: 16px 10px; vertical-align: top; color: var(--text-secondary); font-size: var(--font-xs);">
                <strong><?= e($p['bairro_nome']) ?></strong>
                <span style="display: block; color: var(--text-muted);"><?= e($p['municipio_nome']) ?></span>
              </td>

              <td style="padding: 16px 10px; vertical-align: top;">
                <span class="badge" style="background: var(--surface-inset); color: var(--text-secondary); font-size: 11px;">
                  <?= e($p['categoria_nome']) ?>
                </span>
              </td>

              <td style="padding: 16px 10px; vertical-align: top;">
                <span class="badge badge--analysis" style="font-size: 11px;">
                  <?= icon('vote', 12) ?> <?= number_format($p['total_confirmacoes']) ?>
                </span>
              </td>

              <!-- Formulário de Despacho e Auditoria -->
              <td style="padding: 16px 10px; vertical-align: top; min-width: 260px;">
                <form action="<?= url('admin/problemas/' . $p['id'] . '/estado') ?>" method="POST" style="display: flex; flex-direction: column; gap: 6px;">
                  <?= csrfField() ?>
                  
                  <div style="display: flex; gap: 6px; align-items: center;">
                    <select name="estado_id" class="form-select" style="padding: 5px 28px 5px 10px; font-size: var(--font-xs); flex: 1;">
                      <?php foreach ($estados as $est): ?>
                        <option value="<?= $est['id'] ?>" <?= (int)$est['id'] === (int)$p['estado_id'] ? 'selected' : '' ?>>
                          <?= e($est['nome']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn--primary btn--sm" style="padding: 5px 12px; font-size: var(--font-xs); white-space: nowrap;">
                      Atualizar
                    </button>
                  </div>

                  <input 
                    type="text" 
                    name="nota" 
                    class="form-input" 
                    placeholder="Nota de despacho (ex: Brigada nº 4 enviada)..."
                    style="padding: 5px 8px; font-size: 11px;"
                  />
                </form>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <?php 
      $numPaginas = (int) ($totalPaginas ?? (!empty($porPagina) ? ceil($total / $porPagina) : 1));
    ?>
    <?php if ($numPaginas > 1): ?>
      <div style="display: flex; justify-content: center; gap: 8px; margin-top: var(--space-8);">
        <?php for ($p = 1; $p <= $numPaginas; $p++): ?>
          <a href="<?= url('admin/problemas?categoria=' . ($categoriaId ?? $filtros['categoria'] ?? 0) . '&estado=' . ($estadoId ?? $filtros['estado'] ?? 0) . '&pagina=' . $p) ?>"
             class="btn <?= $p === $pagina ? 'btn--primary' : 'btn--ghost' ?> btn--sm">
            <?= $p ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</div>
