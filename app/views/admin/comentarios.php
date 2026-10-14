<?php
/**
 * View: admin/comentarios.php
 * Central de Moderação de Comentários Comunitários – Ecrã Total Oficial Djumbai
 */

$totalComentarios = count($comentarios);
$hoje = date('Y-m-d');
$totalHoje = count(array_filter($comentarios, fn($c) => !empty($c['criado_em']) && str_starts_with($c['criado_em'], $hoje)));
$totalSuspensos = count(array_filter($comentarios, fn($c) => ($c['autor_status'] ?? 'ativo') === 'banido'));
$totalVerificados = count(array_filter($comentarios, fn($c) => !empty($c['bi_frente']) && !empty($c['bi_verso'])));
?>

<div class="comentarios-viewport">

  <!-- ══ BARRA SUPERIOR (TOPBAR FULLSCREEN) ═════════════════════════ -->
  <header class="comentarios-topbar">
    <div class="comentarios-topbar__left">
      <a href="<?= url('admin') ?>" class="btn btn--ghost btn--sm" style="display:inline-flex;align-items:center;gap:6px;">
        Voltar ao Painel
      </a>
      <div class="comentarios-topbar__divider"></div>
      <div>
        <h1 class="comentarios-topbar__title">Moderação de Comentários</h1>
        <p class="comentarios-topbar__subtitle">Monitorização em tempo real das mensagens cívicas comunitárias</p>
      </div>
    </div>

    <!-- KPIs Rápidos no Topo -->
    <div class="comentarios-topbar__right">
      <div class="comentarios-kpis">
        <div class="kpi-chip">
          <span class="kpi-chip__label">Total:</span>
          <strong id="kpiTotal"><?= $totalComentarios ?></strong>
        </div>
        <div class="kpi-chip kpi-chip--novo">
          <span class="kpi-chip__dot" style="background:#4CAF50;"></span>
          <span class="kpi-chip__label">Hoje:</span>
          <strong><?= $totalHoje ?></strong>
        </div>
        <div class="kpi-chip kpi-chip--alerta">
          <span class="kpi-chip__dot" style="background:var(--brand-terra);"></span>
          <span class="kpi-chip__label">Autores Suspensos:</span>
          <strong id="kpiSuspensos"><?= $totalSuspensos ?></strong>
        </div>
      </div>
      <a href="<?= url('admin/usuarios') ?>" class="btn btn--ghost btn--sm" style="display:inline-flex;align-items:center;gap:6px;">
        Gerir Utilizadores
      </a>
    </div>
  </header>

  <!-- ══ BARRA DE FILTROS INTELIGENTE ═══════════════════════════════ -->
  <div class="comentarios-filterbar">
    <div class="comentarios-filterbar__row">

      <!-- Campo de Pesquisa em Tempo Real -->
      <div class="filter-search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="filtroTexto" placeholder="Pesquisar por texto, morador ou ocorrência..." autocomplete="off">
        <button type="button" id="btnLimparBusca" style="display:none;color:var(--text-muted);font-size:14px;cursor:pointer;">&times;</button>
      </div>

      <!-- Filtro por Estado do Autor -->
      <div class="filter-select-group">
        <label for="filtroStatusAutor">Estado do Autor:</label>
        <select id="filtroStatusAutor" class="form-select form-select--sm">
          <option value="todos">Todos os Autores</option>
          <option value="ativo">Apenas Membros Ativos</option>
          <option value="banido">Apenas Suspensos</option>
          <option value="verificado">Com BI Verificado</option>
        </select>
      </div>

      <!-- Filtro por Período -->
      <div class="filter-select-group">
        <label for="filtroPeriodo">Período:</label>
        <select id="filtroPeriodo" class="form-select form-select--sm">
          <option value="todos">Todo o Histórico</option>
          <option value="hoje">Publicados Hoje</option>
          <option value="7dias">Últimos 7 Dias</option>
        </select>
      </div>

      <!-- Ordenação -->
      <div class="filter-select-group">
        <label for="filtroOrdem">Ordem:</label>
        <select id="filtroOrdem" class="form-select form-select--sm">
          <option value="recente">Mais Recentes Primeiro</option>
          <option value="antigo">Mais Antigos Primeiro</option>
        </select>
      </div>

      <!-- Contador Dinâmico de Resultados -->
      <div class="results-badge">
        <span id="contadorExibidos"><?= $totalComentarios ?></span> de <?= $totalComentarios ?> comentários
      </div>

    </div>
  </div>

  <!-- ══ CORPO PRINCIPAL COM SCROLL SUAVE SEM BARRAS CINZENTAS ═════ -->
  <div class="comentarios-scroll-container no-scrollbar">

    <?php if (empty($comentarios)): ?>
      <div class="empty-state-box">
        <div class="empty-state-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h3>Nenhum comentário registado</h3>
        <p>Os comentários submetidos pelos cidadãos aparecerão aqui para moderação centralizada.</p>
      </div>
    <?php else: ?>

      <div class="comentarios-grid" id="comentariosGrid">
        <?php foreach ($comentarios as $c):
          $isBanned = ($c['autor_status'] ?? 'ativo') === 'banido';
          $temBi = !empty($c['bi_frente']) && !empty($c['bi_verso']);
          $avatarBg = $isBanned ? '#B4451F' : (['#1F6F6B','#3F5A3C','#6B5A4A','#D99A1E'][crc32($c['autor_email'] ?? $c['autor_nome']) % 4]);
          $criadoEmIso = date('Y-m-d', strtotime($c['criado_em']));
        ?>
          <article class="comment-card" 
            data-id="<?= (int)$c['id'] ?>"
            data-user-id="<?= (int)$c['user_id'] ?>"
            data-user-name="<?= e(addslashes($c['autor_nome'])) ?>"
            data-texto="<?= strtolower(e($c['texto'])) ?>"
            data-autor="<?= strtolower(e($c['autor_nome'])) ?>"
            data-email="<?= strtolower(e($c['autor_email'] ?? '')) ?>"
            data-problema="<?= strtolower(e($c['problema_titulo'])) ?>"
            data-status="<?= $isBanned ? 'banido' : 'ativo' ?>"
            data-verificado="<?= $temBi ? '1' : '0' ?>"
            data-data-iso="<?= $criadoEmIso ?>"
            data-timestamp="<?= strtotime($c['criado_em']) ?>"
          >
            <!-- Topo do Card -->
            <div class="comment-card__header">
              <div class="comment-card__author-info">
                <div class="comment-card__avatar" style="background:<?= $avatarBg ?>;">
                  <?= strtoupper(substr($c['autor_nome'], 0, 1)) ?>
                </div>
                <div>
                  <div class="comment-card__author-name">
                    <strong><?= e($c['autor_nome']) ?></strong>
                    <?php if ($isBanned): ?>
                      <span class="badge badge--banned">Suspenso</span>
                    <?php else: ?>
                      <span class="badge badge--active">Ativo</span>
                    <?php endif; ?>
                    <?php if ($temBi): ?>
                      <span class="badge badge--bi" title="Documento de Identidade Verificado" style="display:inline-flex;align-items:center;gap:3px;">
                        BI <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="comment-card__meta">
                    <span><?= e($c['autor_email'] ?: ($c['autor_telefone'] ?: 'Sem contacto')) ?></span>
                    <span>•</span>
                    <span><?= dataAngolana($c['criado_em']) ?></span>
                  </div>
                </div>
              </div>

              <!-- Contexto da Ocorrência -->
              <div class="comment-card__context">
                <span class="context-pill" style="display:inline-flex;align-items:center;gap:4px;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <?= e($c['bairro_nome'] ?? 'Luanda') ?>
                </span>
                <?php if (!empty($c['categoria_nome'])): ?>
                  <span class="context-pill context-pill--category">
                    <?= e($c['categoria_nome']) ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Referência da Ocorrência -->
            <div class="comment-card__topic">
              <span class="topic-label">Em resposta à ocorrência:</span>
              <a href="<?= url('problemas/' . $c['problema_id']) ?>" target="_blank" class="topic-link">
                <?= e($c['problema_titulo']) ?> ↗
              </a>
            </div>

            <!-- Balão de Texto do Comentário -->
            <div class="comment-card__body">
              <?= nl2br(e($c['texto'])) ?>
            </div>

            <!-- Barra de Ações Rápidas de Moderação -->
            <div class="comment-card__actions">
              <div class="actions-left">
                <!-- Botão Aviso de Bons Modos Imediato -->
                <button type="button" class="action-btn action-btn--warn js-btn-warn" data-user-id="<?= (int)$c['user_id'] ?>" data-user-name="<?= e($c['autor_nome']) ?>">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                  Aviso de Conduta
                </button>

                <!-- Ver Ocorrência no Portal -->
                <a href="<?= url('problemas/' . $c['problema_id']) ?>" target="_blank" class="action-btn action-btn--view">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver Ocorrência
                </a>
              </div>

              <!-- Eliminar Comentário -->
              <form action="<?= url('admin/comentarios/' . $c['id'] . '/eliminar') ?>" method="POST" style="margin:0;" class="js-delete-comment-form">
                <?= csrfField() ?>
                <button type="submit" class="action-btn action-btn--delete">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  Eliminar Comentário
                </button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- Feedback de Nenhum Resultado nos Filtros -->
      <div id="noFilterResults" style="display:none;text-align:center;padding:56px 20px;color:var(--text-muted);">
        <p style="font-weight:700;font-size:15px;color:var(--text-primary);margin-bottom:6px;">Nenhum comentário corresponde aos filtros</p>
        <p style="font-size:13px;margin:0;">Tente pesquisar com outro termo ou redefinir os seletores.</p>
        <button type="button" id="btnResetFiltros" class="btn btn--ghost btn--sm" style="margin-top:14px;">Limpar Filtros</button>
      </div>

    <?php endif; ?>

  </div>

</div>

<!-- ══ MODAL DE CONDUTA CÍVICA (NOTIFICAÇÃO DIRETA AO AUTOR) ═════════ -->
<div id="warnModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,10,8,0.75);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;">
  <div class="no-scrollbar" style="background:var(--surface-card);max-width:520px;width:100%;border-radius:18px;box-shadow:0 32px 64px rgba(0,0,0,0.35);border:1px solid var(--border-subtle);overflow:hidden;animation:scaleUp 0.3s cubic-bezier(0.16,1,0.3,1);">

    <!-- Cabeçalho do modal -->
    <div style="background:linear-gradient(135deg,#1A1410,#2D221A);padding:20px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid var(--brand-baia);">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(31,111,107,0.2);border:1px solid rgba(31,111,107,0.4);display:flex;align-items:center;justify-content:center;color:var(--brand-dendem);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <div style="font-size:10.5px;font-weight:700;color:var(--brand-dendem);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:2px;">Moderação de Conduta Cívica</div>
          <h3 style="margin:0;font-size:1.1rem;font-weight:800;color:#FFF;">Notificação de Bons Modos</h3>
        </div>
      </div>
      <button class="js-warn-close-btn" style="background:rgba(255,255,255,0.08);border:none;color:rgba(255,255,255,0.7);width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.15s;">&times;</button>
    </div>

    <!-- Corpo do modal -->
    <div style="padding:22px 24px;">
      <div style="background:var(--surface-sunken);border:1px solid var(--border-subtle);border-radius:10px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:12.5px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span style="color:var(--text-muted);">Destinatário:</span>
        <strong id="warnUserName" style="color:var(--text-primary);font-weight:700;"></strong>
      </div>

      <!-- Modelos Rápidos -->
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Modelos Rápidos de Orientação</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <button type="button" class="js-warn-preset" data-preset="1" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Respeito Mútuo
          </button>
          <button type="button" class="js-warn-preset" data-preset="2" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Linguagem Ofensiva
          </button>
          <button type="button" class="js-warn-preset" data-preset="3" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:14px;background:var(--surface-sunken);border:1px solid var(--border-subtle);color:var(--text-secondary);cursor:pointer;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg> Publicação Fora de Tópico
          </button>
        </div>
      </div>

      <form id="warnForm" action="" method="POST">
        <?= csrfField() ?>
        <div style="margin-bottom:18px;">
          <label style="display:block;font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Mensagem de Moderação</label>
          <textarea id="warnMsgInput" name="mensagem" rows="4" placeholder="Escreva a orientação de conduta cívica a ser enviada ao morador..." style="width:100%;font-size:13px;line-height:1.5;resize:vertical;box-sizing:border-box;border-radius:10px;border:1px solid var(--border-subtle);padding:12px;background:var(--surface-sunken);color:var(--text-primary);"></textarea>
          <p style="font-size:11px;color:var(--text-muted);margin:6px 0 0;">Esta notificação será exibida diretamente no painel do cidadão.</p>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <button type="button" class="js-close-warn btn btn--ghost btn--sm">Cancelar</button>
          <button type="submit" style="display:flex;align-items:center;gap:7px;background:var(--brand-baia);color:#FFF;padding:10px 22px;border-radius:9px;border:none;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(31,111,107,0.35);">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Enviar Notificação
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ══ ESTILOS VISUAIS EM ECRÃ TOTAL (FULLSCREEN WORKSPACE) ══════════ -->
<style>
.comentarios-viewport {
  display: flex;
  flex-direction: column;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: var(--surface-ground, #FBF8F2);
}

.comentarios-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 24px;
  background: var(--surface-card, #FFFDF9);
  border-bottom: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  flex-shrink: 0;
  gap: 16px;
  flex-wrap: wrap;
  z-index: 10;
}

.comentarios-topbar__left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.comentarios-topbar__divider {
  width: 1px;
  height: 24px;
  background: var(--border-subtle, rgba(43, 29, 20, 0.1));
}

.comentarios-topbar__title {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--text-primary);
  margin: 0;
  line-height: 1.2;
}

.comentarios-topbar__subtitle {
  font-size: 11.5px;
  color: var(--text-muted);
  margin: 0;
}

.comentarios-topbar__right {
  display: flex;
  align-items: center;
  gap: 14px;
}

.comentarios-kpis {
  display: flex;
  align-items: center;
  gap: 8px;
}

.kpi-chip {
  background: var(--surface-sunken);
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.kpi-chip__dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
}

.kpi-chip__label {
  color: var(--text-muted);
  font-weight: 600;
}

.comentarios-filterbar {
  background: var(--surface-card, #FFFDF9);
  border-bottom: 1px solid var(--border-subtle, rgba(43, 29, 20, 0.08));
  padding: 10px 24px;
  flex-shrink: 0;
  z-index: 9;
  box-shadow: 0 2px 8px rgba(43, 29, 20, 0.03);
}

.comentarios-filterbar__row {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
}

.filter-search-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--surface-sunken);
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  padding: 6px 12px;
  min-width: 320px;
  flex: 1;
}

.filter-search-box input {
  border: none;
  background: transparent;
  width: 100%;
  font-size: 13px;
  color: var(--text-primary);
  outline: none;
}

.filter-select-group {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--text-secondary);
  font-weight: 600;
}

.results-badge {
  font-size: 12px;
  font-weight: 700;
  color: var(--text-muted);
  margin-left: auto;
  background: var(--surface-sunken);
  padding: 5px 12px;
  border-radius: 20px;
  border: 1px solid var(--border-subtle);
}

.comentarios-scroll-container {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
  background-color: var(--surface-ground, #FBF8F2);
}

.comentarios-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(460px, 1fr));
  gap: 16px;
  max-width: 1600px;
  margin: 0 auto;
}

.comment-card {
  background: var(--surface-card);
  border: 1px solid var(--border-subtle);
  border-radius: 14px;
  padding: 18px 20px;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  transition: all 0.2s ease;
}

.comment-card:hover {
  box-shadow: var(--shadow-md);
  border-color: rgba(31,111,107,0.3);
  transform: translateY(-1px);
}

.comment-card__header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 12px;
}

.comment-card__author-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.comment-card__avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  color: #FFF;
  font-weight: 700;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.comment-card__author-name {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13.5px;
  color: var(--text-primary);
}

.comment-card__meta {
  font-size: 11px;
  color: var(--text-muted);
  display: flex;
  align-items: center;
  gap: 5px;
  margin-top: 2px;
}

.badge {
  padding: 2px 7px;
  border-radius: 10px;
  font-size: 9.5px;
  font-weight: 700;
}

.badge--active { background: rgba(76,175,80,0.12); color: #2E7D32; }
.badge--banned { background: rgba(180,69,31,0.12); color: var(--brand-terra); }
.badge--bi     { background: rgba(31,111,107,0.12); color: var(--brand-baia); }

.comment-card__context {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.context-pill {
  font-size: 10.5px;
  font-weight: 700;
  color: var(--text-secondary);
  background: var(--surface-sunken);
  border: 1px solid var(--border-subtle);
  padding: 3px 8px;
  border-radius: 6px;
}

.context-pill--category {
  color: var(--brand-baia);
}

.comment-card__topic {
  font-size: 11.5px;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
  background: var(--surface-sunken);
  padding: 6px 10px;
  border-radius: 8px;
  border-left: 3px solid var(--brand-terra);
}

.topic-label {
  color: var(--text-muted);
  font-size: 11px;
}

.topic-link {
  font-weight: 700;
  color: var(--brand-terra);
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  flex: 1;
}

.topic-link:hover {
  text-decoration: underline;
}

.comment-card__body {
  font-size: 13px;
  line-height: 1.6;
  color: var(--text-primary);
  background: var(--surface-ground);
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 14px;
  border: 1px solid var(--border-subtle);
  flex: 1;
}

.comment-card__actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 8px;
  padding-top: 10px;
  border-top: 1px solid var(--border-subtle);
}

.actions-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 11px;
  font-weight: 700;
  padding: 6px 11px;
  border-radius: 7px;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.15s ease;
  border: 1px solid transparent;
}

.action-btn--warn {
  background: rgba(31,111,107,0.08);
  border-color: rgba(31,111,107,0.25);
  color: var(--brand-baia);
}
.action-btn--warn:hover {
  background: var(--brand-baia);
  color: #FFF;
}

.action-btn--view {
  background: var(--surface-sunken);
  border-color: var(--border-subtle);
  color: var(--text-secondary);
}
.action-btn--view:hover {
  background: var(--surface-card);
  color: var(--text-primary);
}

.action-btn--delete {
  background: rgba(192,57,43,0.08);
  border-color: rgba(192,57,43,0.25);
  color: #C0392B;
}
.action-btn--delete:hover {
  background: #C0392B;
  color: #FFF;
}

.empty-state-box {
  background: var(--surface-card);
  border-radius: 16px;
  border: 1px solid var(--border-subtle);
  padding: 72px 32px;
  text-align: center;
  max-width: 500px;
  margin: 60px auto 0;
}
.empty-state-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: var(--surface-sunken);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}

.js-warn-close-btn:hover {
  background: rgba(255,255,255,0.15) !important;
  color: #FFF !important;
}
</style>

<!-- ══ SCRIPT DE FILTRAGEM DINÂMICA E MODERAÇÃO ═════════════════════ -->
<script nonce="<?= e(CSP_NONCE) ?>">
const warnPresets = {
  1: "Estimado(a) morador(a), pedimos a gentileza de manter o respeito e linguagem adequada ao interagir com outros membros da comunidade na plataforma Djumbai.",
  2: "AVISO DE CONDUTA: O seu comentário foi sinalizado pela moderação por conter linguagem imprópria ou desrespeitosa.",
  3: "AVISO DE MODERAÇÃO: Solicitamos que foque as suas mensagens exclusivamente na resolução da ocorrência comunitária em questão."
};

function useWarnPreset(type) {
  if (warnPresets[type]) {
    document.getElementById('warnMsgInput').value = warnPresets[type];
  }
}

function openWarnModal(userId, nome) {
  document.getElementById('warnUserName').textContent = nome;
  document.getElementById('warnMsgInput').value = '';
  document.getElementById('warnForm').action = '<?= url('admin/usuarios/') ?>' + userId + '/notificar';
  document.getElementById('warnModal').style.display = 'flex';
}

function closeWarnModal() {
  document.getElementById('warnModal').style.display = 'none';
}

document.getElementById('warnModal').addEventListener('click', function(e){
  if (e.target === this) closeWarnModal();
});

// Filtragem em Tempo Real no Cliente
const inputBusca       = document.getElementById('filtroTexto');
const selectStatus     = document.getElementById('filtroStatusAutor');
const selectPeriodo    = document.getElementById('filtroPeriodo');
const selectOrdem      = document.getElementById('filtroOrdem');
const contadorExibidos = document.getElementById('contadorExibidos');
const noResults        = document.getElementById('noFilterResults');
const btnLimparBusca   = document.getElementById('btnLimparBusca');
const grid             = document.getElementById('comentariosGrid');

function filtrarComentarios() {
  const query = (inputBusca.value || '').trim().toLowerCase();
  const statusFiltro = selectStatus.value;
  const periodoFiltro = selectPeriodo.value;
  const ordem = selectOrdem.value;

  if (query.length > 0) {
    btnLimparBusca.style.display = 'inline-block';
  } else {
    btnLimparBusca.style.display = 'none';
  }

  const cards = Array.from(document.querySelectorAll('.comment-card'));
  const hoje = '<?= $hoje ?>';
  const agoraTimestamp = Math.floor(Date.now() / 1000);

  let visiveis = 0;

  cards.forEach(card => {
    const texto = card.getAttribute('data-texto') || '';
    const autor = card.getAttribute('data-autor') || '';
    const email = card.getAttribute('data-email') || '';
    const problema = card.getAttribute('data-problema') || '';
    const status = card.getAttribute('data-status') || '';
    const verificado = card.getAttribute('data-verificado') === '1';
    const dataIso = card.getAttribute('data-data-iso') || '';
    const ts = parseInt(card.getAttribute('data-timestamp'), 10) || 0;

    let matchBusca = true;
    if (query) {
      matchBusca = texto.includes(query) || autor.includes(query) || email.includes(query) || problema.includes(query);
    }

    let matchStatus = true;
    if (statusFiltro === 'ativo') matchStatus = (status === 'ativo');
    else if (statusFiltro === 'banido') matchStatus = (status === 'banido');
    else if (statusFiltro === 'verificado') matchStatus = verificado;

    let matchPeriodo = true;
    if (periodoFiltro === 'hoje') {
      matchPeriodo = (dataIso === hoje);
    } else if (periodoFiltro === '7dias') {
      const diffDias = (agoraTimestamp - ts) / 86400;
      matchPeriodo = (diffDias <= 7);
    }

    if (matchBusca && matchStatus && matchPeriodo) {
      card.style.display = 'flex';
      visiveis++;
    } else {
      card.style.display = 'none';
    }
  });

  // Reordenação no DOM
  if (grid) {
    const sortedCards = cards.sort((a, b) => {
      const tsA = parseInt(a.getAttribute('data-timestamp'), 10) || 0;
      const tsB = parseInt(b.getAttribute('data-timestamp'), 10) || 0;
      return (ordem === 'recente') ? (tsB - tsA) : (tsA - tsB);
    });
    sortedCards.forEach(c => grid.appendChild(c));
  }

  if (contadorExibidos) contadorExibidos.textContent = visiveis;
  if (noResults) noResults.style.display = (visiveis === 0) ? 'block' : 'none';
}

function limparBusca() {
  inputBusca.value = '';
  filtrarComentarios();
  inputBusca.focus();
}

function resetFiltros() {
  inputBusca.value = '';
  selectStatus.value = 'todos';
  selectPeriodo.value = 'todos';
  selectOrdem.value = 'recente';
  filtrarComentarios();
}

inputBusca.addEventListener('input', filtrarComentarios);
selectStatus.addEventListener('change', filtrarComentarios);
selectPeriodo.addEventListener('change', filtrarComentarios);
selectOrdem.addEventListener('change', filtrarComentarios);

// ── EVENT BINDINGS (CSP-compliant) ───────────────────────────────
btnLimparBusca.addEventListener('click', limparBusca);
document.getElementById('btnResetFiltros').addEventListener('click', resetFiltros);
document.querySelectorAll('.js-btn-warn').forEach(b => b.addEventListener('click', function(){ openWarnModal(this.dataset.userId, this.dataset.userName); }));
document.querySelectorAll('.js-delete-comment-form').forEach(f => f.addEventListener('submit', function(e){ if(!confirm('Tem a certeza que deseja eliminar este comentário definitivamente?')) e.preventDefault(); }));
document.querySelectorAll('.js-warn-close-btn').forEach(b => b.addEventListener('click', closeWarnModal));
document.querySelectorAll('.js-warn-preset').forEach(b => b.addEventListener('click', function(){ useWarnPreset(this.dataset.preset); }));
document.querySelectorAll('.js-close-warn').forEach(b => b.addEventListener('click', closeWarnModal));
</script>
