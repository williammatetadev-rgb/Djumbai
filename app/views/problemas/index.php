<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: problemas/index.php (Pesquisa & Filtros Avançados) ║
     ╚══════════════════════════════════════════════════════════╝ -->

<?php
$hasActiveFilters = !empty($filtros['q']) 
  || !empty($filtros['categoria']) 
  || !empty($filtros['estado']) 
  || !empty($filtros['municipio']) 
  || (!empty($filtros['ordem']) && $filtros['ordem'] !== 'populares');
?>

<!-- ══ HERO BANNER DA PÁGINA DE EXPLORAR ════════════════════════ -->
<section style="background: linear-gradient(135deg, #1A1410 0%, #2D221A 60%, #3A2D20 100%); padding: 36px 0 32px; border-bottom: 3px solid var(--brand-dendem); position: relative; overflow: hidden; margin-bottom: 32px;">
  
  <div style="position: absolute; inset: 0; opacity: 0.04; background-image: repeating-linear-gradient(60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px),repeating-linear-gradient(-60deg,#D99A1E 0px,#D99A1E 2px,transparent 2px,transparent 30px); pointer-events: none;"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
      
      <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(217,154,30,0.15); border: 1px solid rgba(217,154,30,0.3); padding: 3px 10px; border-radius: 20px; margin-bottom: 10px;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
          <span style="font-size: 10.5px; font-weight: 700; color: var(--brand-dendem); text-transform: uppercase; letter-spacing: 0.6px;">Plataforma Cívica</span>
        </div>

        <h1 style="font-family: var(--font-display); font-size: 1.85rem; font-weight: 800; color: #FFF; margin: 0 0 6px; line-height: 1.15;">
          Explorar Ocorrências Comunitárias
        </h1>

        <p style="font-size: 13.5px; color: rgba(255,251,245,0.65); margin: 0; max-width: 540px;">
          <?= number_format($total) ?> ocorrência<?= $total !== 1 ? 's' : '' ?> encontrada<?= $total !== 1 ? 's' : '' ?> no sistema.
        </p>
      </div>

      <div>
        <a href="<?= url('reportar') ?>" style="display: inline-flex; align-items: center; gap: 8px; background: var(--brand-terra); color: #FFF; padding: 11px 22px; border-radius: 10px; text-decoration: none; font-size: 13.5px; font-weight: 700; box-shadow: 0 4px 14px rgba(180,69,31,0.4); transition: transform 0.15s;" onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Reportar Ocorrência
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ══ CONTEÚDO PRINCIPAL ════════════════════════════════════════ -->
<div class="container" style="padding-bottom: 48px;">

  <!-- ── BARRA DE PESQUISA & FILTROS AVANÇADOS ───────────────────────────── -->
  <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 20px; margin-bottom: 28px; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 18px;">
    
    <!-- Linha 1: Campo de Busca Textual Instantânea -->
    <form action="<?= url('problemas') ?>" method="GET" style="margin:0; display:flex; gap:10px; width:100%; flex-wrap:wrap;">
      <?php if (!empty($filtros['categoria'])): ?>
        <input type="hidden" name="categoria" value="<?= (int)$filtros['categoria'] ?>">
      <?php endif; ?>
      <?php if (!empty($filtros['estado'])): ?>
        <input type="hidden" name="estado" value="<?= (int)$filtros['estado'] ?>">
      <?php endif; ?>
      <?php if (!empty($filtros['municipio'])): ?>
        <input type="hidden" name="municipio" value="<?= (int)$filtros['municipio'] ?>">
      <?php endif; ?>
      <?php if (!empty($filtros['ordem'])): ?>
        <input type="hidden" name="ordem" value="<?= e($filtros['ordem']) ?>">
      <?php endif; ?>

      <div style="position:relative; flex:1; min-width:260px;">
        <div style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); display:flex; align-items:center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <input type="text" name="q" value="<?= e($filtros['q'] ?? '') ?>" placeholder="Pesquisar por título, bairro, município ou palavra-chave (ex: esgoto, lixo, água)..." style="width:100%; padding:12px 14px 12px 42px; border-radius:10px; border:1px solid var(--border-subtle); background:var(--surface-sunken); color:var(--text-primary); font-size:13.5px; outline:none; box-sizing:border-box; transition:border-color 0.15s;" onfocus="this.style.borderColor='var(--brand-baia)'" onblur="this.style.borderColor='var(--border-subtle)'">
      </div>

      <button type="submit" style="display:inline-flex; align-items:center; gap:8px; background:var(--brand-baia); color:#FFF; border:none; padding:0 22px; height:44px; border-radius:10px; font-size:13.5px; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(31,111,107,0.3); transition:all 0.15s;" onmouseenter="this.style.filter='brightness(1.1)'" onmouseleave="this.style.filter='none'">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Pesquisar
      </button>

      <?php if (!empty($filtros['q'])): ?>
        <?php
        $cleanSearchGet = $_GET;
        unset($cleanSearchGet['q'], $cleanSearchGet['pagina']);
        $cleanSearchUrl = url('problemas' . (!empty($cleanSearchGet) ? '?' . http_build_query($cleanSearchGet) : ''));
        ?>
        <a href="<?= $cleanSearchUrl ?>" style="display:inline-flex; align-items:center; justify-content:center; padding:0 14px; height:44px; border-radius:10px; border:1px solid var(--border-subtle); background:var(--surface-sunken); color:var(--text-secondary); text-decoration:none; font-size:12px; font-weight:600;" title="Limpar palavra-chave">
          &times; Limpar busca
        </a>
      <?php endif; ?>
    </form>

    <!-- Linha 2: Categorias (Pills) -->
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; padding-top: 4px;">
      <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; flex-shrink: 0; margin-right: 4px;">Categoria:</span>
      
      <?php
      $queryBase = $_GET;
      unset($queryBase['categoria'], $queryBase['pagina']);
      $allCatUrl = url('problemas' . (!empty($queryBase) ? '?' . http_build_query($queryBase) : ''));
      ?>
      <a href="<?= $allCatUrl ?>"
         style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.15s; <?= empty($filtros['categoria']) ? 'background: var(--brand-terra); color: #FFF;' : 'background: var(--surface-sunken); color: var(--text-secondary); border: 1px solid var(--border-subtle);' ?>">
        Todas
      </a>

      <?php foreach ($categorias as $cat): ?>
        <?php
        $isActive = (int)($filtros['categoria'] ?? 0) === (int)$cat['id'];
        $catParams = $_GET;
        $catParams['categoria'] = $cat['id'];
        unset($catParams['pagina']);
        $catUrl = url('problemas?' . http_build_query($catParams));
        ?>
        <a href="<?= $catUrl ?>"
           style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.15s; <?= $isActive ? 'background: var(--brand-terra); color: #FFF;' : 'background: var(--surface-sunken); color: var(--text-secondary); border: 1px solid var(--border-subtle);' ?>">
          <?= e($cat['nome']) ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Linha 3: Filtros Dropdown (Município, Estado, Ordenação) -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); padding-top: 14px; flex-wrap: wrap; gap: 14px;">
      
      <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
        
        <!-- Filtro Município -->
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Município:</span>
          <select onchange="applyFilter('municipio', this.value)" class="form-select" style="padding: 6px 30px 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; border: 1px solid var(--border-subtle); background-color: var(--surface-card); color: var(--text-primary); cursor: pointer;">
            <option value="0" <?= empty($filtros['municipio']) ? 'selected' : '' ?>>Todos os Municípios</option>
            <?php foreach ($municipios as $mun): ?>
              <option value="<?= $mun['id'] ?>" <?= ((int)($filtros['municipio'] ?? 0) === (int)$mun['id']) ? 'selected' : '' ?>><?= e($mun['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Filtro Estado -->
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Estado:</span>
          <select onchange="applyFilter('estado', this.value)" class="form-select" style="padding: 6px 30px 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; border: 1px solid var(--border-subtle); background-color: var(--surface-card); color: var(--text-primary); cursor: pointer;">
            <option value="0" <?= empty($filtros['estado']) ? 'selected' : '' ?>>Todos os Estados</option>
            <option value="1" <?= ((int)($filtros['estado'] ?? 0) === 1) ? 'selected' : '' ?>>Pendentes</option>
            <option value="2" <?= ((int)($filtros['estado'] ?? 0) === 2) ? 'selected' : '' ?>>Em Análise</option>
            <option value="3" <?= ((int)($filtros['estado'] ?? 0) === 3) ? 'selected' : '' ?>>Resolvidos</option>
            <option value="4" <?= ((int)($filtros['estado'] ?? 0) === 4) ? 'selected' : '' ?>>Rejeitados</option>
          </select>
        </div>

        <!-- Ordenação -->
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Ordenar por:</span>
          <select onchange="applyFilter('ordem', this.value)" class="form-select" style="padding: 6px 30px 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; border: 1px solid var(--border-subtle); background-color: var(--surface-card); color: var(--text-primary); cursor: pointer;">
            <option value="populares" <?= (($filtros['ordem'] ?? '') === 'populares') ? 'selected' : '' ?>>Mais Apoiados (Populares)</option>
            <option value="recentes" <?= (($filtros['ordem'] ?? '') === 'recentes') ? 'selected' : '' ?>>Mais Recentes</option>
            <option value="antigos" <?= (($filtros['ordem'] ?? '') === 'antigos') ? 'selected' : '' ?>>Mais Antigos</option>
          </select>
        </div>

      </div>

      <?php if ($hasActiveFilters): ?>
        <a href="<?= url('problemas') ?>" style="font-size: 11.5px; color: var(--brand-terra); text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          Limpar Todos os Filtros
        </a>
      <?php endif; ?>

    </div>

  </div>

  <!-- ── LISTA DE OCORRÊNCIAS ────────────────────────────────── -->
  <?php if (empty($problemas)): ?>
    <div style="background: var(--surface-card); border-radius: 16px; border: 1px dashed var(--border-subtle); padding: 64px 24px; text-align: center;">
      <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--surface-sunken); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
      <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0 0 6px;">
        <?= !empty($filtros['q']) ? 'Nenhuma ocorrência encontrada para "' . e($filtros['q']) . '"' : 'Nenhuma ocorrência encontrada' ?>
      </h3>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 20px; max-width: 420px; margin-inline: auto;">
        Tente pesquisar com termos mais genéricos, alterar o município ou limpar os filtros aplicados.
      </p>
      <?php if ($hasActiveFilters): ?>
        <a href="<?= url('problemas') ?>" class="btn btn--secondary btn--sm" style="margin-right:8px;">Limpar Filtros</a>
      <?php endif; ?>
      <a href="<?= url('reportar') ?>" class="btn btn--primary btn--sm">Reportar Novo Problema</a>
    </div>
  <?php else: ?>

    <div style="display: flex; flex-direction: column; gap: 14px;">
      <?php foreach ($problemas as $prob): ?>
        <?php $corEstado = $prob['estado_cor'] ?? '#6B5A4A'; ?>
        
        <article style="background: var(--surface-card); border-radius: 14px; border: 1px solid var(--border-subtle); padding: 22px 24px; box-shadow: var(--shadow-sm); transition: all 0.2s; position: relative;" onmouseenter="this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='rgba(180,69,31,0.3)';" onmouseleave="this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--border-subtle)';">
          
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
            
            <!-- Conteúdo da Ocorrência -->
            <div style="flex: 1; min-width: 280px;">
              
              <!-- Badges de Estado e Categoria -->
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                <span style="background: <?= e($corEstado) ?>18; color: <?= e($corEstado) ?>; border: 1px solid <?= e($corEstado) ?>44; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                  <span style="width: 6px; height: 6px; border-radius: 50%; background: <?= e($corEstado) ?>;"></span>
                  <?= e($prob['estado_nome']) ?>
                </span>

                <span style="background: var(--surface-sunken); color: var(--text-secondary); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; border: 1px solid var(--border-subtle);">
                  <?= e($prob['categoria_nome']) ?>
                </span>
              </div>

              <!-- Título com Link -->
              <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-primary); margin: 0 0 10px; line-height: 1.3;">
                <a href="<?= url('problemas/' . $prob['id']) ?>" style="color: inherit; text-decoration: none;" onmouseenter="this.style.color='var(--brand-terra)'" onmouseleave="this.style.color='inherit'">
                  <?= e($prob['titulo']) ?>
                </a>
              </h2>

              <!-- Meta Informação (Bairro, Data, Autor) -->
              <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; color: var(--text-muted); flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 4px; color: var(--text-secondary); font-weight: 600;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <?= e($prob['bairro_nome']) ?><?= !empty($prob['municipio_nome']) ? ', ' . e($prob['municipio_nome']) : '' ?>
                </span>

                <span>·</span>

                <span style="display: inline-flex; align-items: center; gap: 4px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  <?= timeAgo($prob['criado_em']) ?>
                </span>

                <?php if (!empty($prob['autor_nome'])): ?>
                  <span>·</span>
                  <span>por <strong><?= e($prob['autor_nome']) ?></strong></span>
                <?php endif; ?>
              </div>

            </div>

            <!-- Botão de Votação/Apoio & Ver Detalhes -->
            <div style="display: flex; align-items: center; gap: 12px; flex-shrink: 0;">
              
              <!-- Botão Apoiar -->
              <button class="widget-vote-btn js-vote-btn" data-id="<?= $prob['id'] ?>" title="Apoiar este problema" <?= !isLoggedIn() ? 'onclick="if(typeof openAuthPromptModal===\'function\'){openAuthPromptModal(\'confirmar\');} return false;"' : '' ?> style="display: inline-flex; align-items: center; gap: 6px; background: var(--surface-sunken); border: 1px solid var(--border-subtle); padding: 8px 14px; border-radius: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700; color: var(--brand-baia); transition: all 0.15s;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 2-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                <span class="js-vote-count"><?= number_format($prob['total_confirmacoes']) ?></span>
              </button>

              <!-- Botão Ver Detalhes -->
              <a href="<?= url('problemas/' . $prob['id']) ?>" style="display: inline-flex; align-items: center; gap: 6px; background: var(--surface-sunken); border: 1px solid var(--border-subtle); color: var(--text-secondary); padding: 8px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 700; text-decoration: none; transition: all 0.15s;" onmouseenter="this.style.background='var(--brand-terra)'; this.style.color='#FFF'; this.style.borderColor='var(--brand-terra)';" onmouseleave="this.style.background='var(--surface-sunken)'; this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-subtle)';">
                Ver Detalhes
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
              </a>

            </div>

          </div>

        </article>
      <?php endforeach; ?>
    </div>

    <!-- Paginação Preservando Filtros Ativos -->
    <?php $totalPaginas = ceil($total / $porPagina); ?>
    <?php if ($totalPaginas > 1): ?>
      <div style="display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 36px;">
        <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
          <?php
          $pageParams = $_GET;
          $pageParams['pagina'] = $p;
          $pageUrl = url('problemas?' . http_build_query($pageParams));
          ?>
          <a href="<?= $pageUrl ?>"
             style="min-width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.15s; <?= $p === $pagina ? 'background: var(--brand-terra); color: #FFF;' : 'background: var(--surface-card); color: var(--text-secondary); border: 1px solid var(--border-subtle);' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</div>

<script>
function applyFilter(key, value) {
  const url = new URL(window.location.href);
  if (value && value !== '0' && value !== 'populares') {
    url.searchParams.set(key, value);
  } else {
    url.searchParams.delete(key);
  }
  url.searchParams.delete('pagina');
  window.location.href = url.toString();
}
</script>
