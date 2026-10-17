<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: admin/usuarios.php — GESTÃO CÍVICA & MODERAÇÃO    ║
     ╚══════════════════════════════════════════════════════════╝ -->

<?php
// Contagens calculadas (métricas globais fornecidas pelo controller para consistência entre abas)
$totalExibidos    = count($cidadaos);
$totalAtivos      = count(array_filter($cidadaos, fn($u) => ($u['status'] ?? 'ativo') === 'ativo'));
$totalBanidos     = $totalSuspensos ?? count(array_filter($cidadaos, fn($u) => ($u['status'] ?? '') === 'banido'));
$totalVerificados = $totalVerificadosBi ?? count(array_filter($cidadaos, fn($u) => ($u['bi_status'] ?? '') === 'verificado'));
$totalOnline      = count(array_filter($cidadaos, fn($u) => !empty($u['ultimo_acesso']) && strtotime($u['ultimo_acesso']) >= strtotime('-15 minutes')));

$listaParaExibir = ($aba === 'equipa') ? $equipa : $cidadaos;
?>

<style>
  .user-tab-nav {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid var(--border-subtle);
    margin-bottom: var(--space-6);
  }
  .user-tab-item {
    padding: 10px 18px;
    font-size: var(--font-sm);
    font-weight: 700;
    color: var(--text-secondary);
    text-decoration: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .user-tab-item:hover {
    color: var(--text-primary);
  }
  .user-tab-item.is-active {
    color: var(--brand-terra);
    border-bottom-color: var(--brand-terra);
  }

  /* Badge de BI Estilizados */
  .badge-bi--verificado {
    background: rgba(74, 222, 128, 0.15);
    color: #166534;
    border: 1px solid rgba(74, 222, 128, 0.5);
    padding: 2px 7px;
    border-radius: var(--radius-full);
    font-size: 10px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .badge-bi--pendente {
    background: rgba(217, 154, 30, 0.15);
    color: #8C6211;
    border: 1px solid rgba(217, 154, 30, 0.4);
    padding: 2px 7px;
    border-radius: var(--radius-full);
    font-size: 10px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .badge-bi--rejeitado {
    background: rgba(180, 69, 31, 0.12);
    color: var(--brand-terra);
    border: 1px solid rgba(180, 69, 31, 0.3);
    padding: 2px 7px;
    border-radius: var(--radius-full);
    font-size: 10px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .badge-bi--nao-enviado {
    background: var(--surface-sunken);
    color: var(--text-muted);
    border: 1px solid var(--border-subtle);
    padding: 2px 7px;
    border-radius: var(--radius-full);
    font-size: 10px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
  }

  .user-table-row {
    display: grid;
    grid-template-columns: 2.2fr 1.1fr 1.1fr 1.1fr 0.8fr 2fr;
    gap: 0;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border-subtle);
    align-items: center;
    cursor: pointer;
    transition: background 0.15s ease;
  }
  .user-table-row:hover {
    background: var(--surface-sunken);
  }

  /* Modais Gerais */
  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(15, 10, 8, 0.7);
    backdrop-filter: blur(5px);
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-overlay.is-active {
    display: flex;
  }
  .modal-box {
    background: var(--surface-card);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
    box-shadow: var(--shadow-modal);
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalScale 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes modalScale {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
</style>

<div style="padding: 28px;">

  <!-- ══ 1. CABEÇALHO & ESTATÍSTICAS RÁPIDAS ════════════════════════════ -->
  <div style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
      <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; display: flex; align-items: center; gap: 10px;">
          <div style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--brand-terra), #C0392B); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #FFF;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          Gestão de Utilizadores &amp; Verificação Cívica
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
          Aprovação de Bilhetes de Identidade (BI), reputação comunitária e gestão da equipa municipal.
        </p>
      </div>

      <?php if ($aba === 'equipa'): ?>
        <button type="button" class="js-btn-novo-admin btn btn--primary btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span>Novo Administrador</span>
        </button>
      <?php endif; ?>
    </div>

    <!-- Cards de Métricas Cívicas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-top: 20px;">
      <div style="background: rgba(31,111,107,0.08); border: 1px solid rgba(31,111,107,0.25); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; background: rgba(31,111,107,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--brand-baia);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div>
          <div style="font-size: 1.45rem; font-weight: 800; color: var(--brand-baia); line-height: 1;"><?= $totalCidadaos ?></div>
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">Cidadãos Registados</div>
        </div>
      </div>

      <div style="background: rgba(46,125,50,0.08); border: 1px solid rgba(46,125,50,0.25); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; background: rgba(46,125,50,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2E7D32;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
          <div style="font-size: 1.45rem; font-weight: 800; color: #2E7D32; line-height: 1;"><?= $totalVerificados ?></div>
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">Verificados</div>
        </div>
      </div>

      <div style="background: rgba(217,154,30,0.08); border: 1px solid rgba(217,154,30,0.25); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; background: rgba(217,154,30,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8C6211;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <div style="font-size: 1.45rem; font-weight: 800; color: #8C6211; line-height: 1;"><?= $totalPendentesBi ?></div>
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">Aguardam Validação de BI</div>
        </div>
      </div>

      <div style="background: rgba(180,69,31,0.08); border: 1px solid rgba(180,69,31,0.25); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; background: rgba(180,69,31,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--brand-terra);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        </div>
        <div>
          <div style="font-size: 1.45rem; font-weight: 800; color: var(--brand-terra); line-height: 1;"><?= $totalBanidos ?></div>
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">Contas Suspensas</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ 2. NAVEGAÇÃO POR ABAS ══════════════════════════════════════════ -->
  <div class="user-tab-nav">
    <a href="<?= url('admin/usuarios?aba=cidadaos') ?>" class="user-tab-item <?= $aba === 'cidadaos' ? 'is-active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      <span>Cidadãos Registados</span>
      <span class="badge" style="background: var(--surface-inset); font-size: 10px;"><?= $totalCidadaos ?></span>
    </a>

    <a href="<?= url('admin/usuarios?aba=verificacoes') ?>" class="user-tab-item <?= $aba === 'verificacoes' ? 'is-active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/></svg>
      <span>Fila de Verificação de BI</span>
      <?php if ($totalPendentesBi > 0): ?>
        <span class="badge" style="background: var(--brand-dendem); color: #FFF; font-weight: 800; font-size: 10px;">
          <?= $totalPendentesBi ?> Pendente(s)
        </span>
      <?php else: ?>
        <span class="badge" style="background: var(--surface-inset); font-size: 10px;">0</span>
      <?php endif; ?>
    </a>

    <a href="<?= url('admin/usuarios?aba=equipa') ?>" class="user-tab-item <?= $aba === 'equipa' ? 'is-active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span>Equipa Administrativa</span>
      <span class="badge" style="background: var(--surface-inset); font-size: 10px;"><?= $totalEquipa ?></span>
    </a>
  </div>

  <!-- ══ 3. FILTROS & BUSCA EM TEMPO REAL ═══════════════════════════════ -->
  <?php if ($aba !== 'equipa'): ?>
    <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px 18px; margin-bottom: 20px;">
      <form method="GET" action="<?= url('admin/usuarios') ?>" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <input type="hidden" name="aba" value="<?= e($aba) ?>" />

        <!-- Busca por texto -->
        <div style="flex: 2; min-width: 220px;">
          <input 
            type="text" 
            id="realTimeSearch"
            name="q" 
            value="<?= e($filtros['q']) ?>" 
            placeholder="Pesquisar..." 
            class="form-input" 
            style="font-size: 12.5px; padding: 8px 12px;"
          />
        </div>

        <!-- Filtro Status -->
        <div style="flex: 1; min-width: 140px;">
          <select name="status" class="form-select" style="font-size: 12px; padding: 7px 10px;">
            <option value="">Estado: Todos</option>
            <option value="ativo" <?= $filtros['status'] === 'ativo' ? 'selected' : '' ?>>Ativos</option>
            <option value="banido" <?= $filtros['status'] === 'banido' ? 'selected' : '' ?>>Suspensos</option>
          </select>
        </div>

        <!-- Filtro BI -->
        <?php if ($aba === 'cidadaos'): ?>
          <div style="flex: 1.2; min-width: 160px;">
            <select name="bi" class="form-select" style="font-size: 12px; padding: 7px 10px;">
              <option value="">Documento: Todos</option>
              <option value="verificado" <?= $filtros['bi'] === 'verificado' ? 'selected' : '' ?>>BI Verificado</option>
              <option value="pendente" <?= $filtros['bi'] === 'pendente' ? 'selected' : '' ?>>Aguardando Validação</option>
              <option value="rejeitado" <?= $filtros['bi'] === 'rejeitado' ? 'selected' : '' ?>>BI Rejeitado</option>
              <option value="nao_enviado" <?= $filtros['bi'] === 'nao_enviado' ? 'selected' : '' ?>>Sem Documento</option>
            </select>
          </div>
        <?php endif; ?>

        <!-- Filtro Município -->
        <div style="flex: 1.2; min-width: 150px;">
          <select name="municipio" class="form-select" style="font-size: 12px; padding: 7px 10px;">
            <option value="0">Município: Todos</option>
            <?php foreach ($municipios as $mun): ?>
              <option value="<?= (int)$mun['id'] ?>" <?= $filtros['municipio'] === (int)$mun['id'] ? 'selected' : '' ?>>
                <?= e($mun['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn btn--primary btn--sm" style="font-size: 12px; padding: 7px 14px;">
          Filtrar
        </button>

        <?php if (!empty($filtros['q']) || !empty($filtros['status']) || (!empty($filtros['bi']) && $aba !== 'verificacoes') || !empty($filtros['municipio'])): ?>
          <a href="<?= url('admin/usuarios?aba=' . urlencode($aba)) ?>" class="btn btn--ghost btn--sm" style="font-size: 12px;">
            Limpar
          </a>
        <?php endif; ?>
      </form>
    </div>
  <?php endif; ?>

  <!-- ══ 4. BANNER DE PROTEÇÃO DO SUPER ADMIN (NA ABA EQUIPA) ══════════ -->
  <?php if ($aba === 'equipa'): ?>
    <div style="background: rgba(31,111,107,0.08); border: 1px solid rgba(31,111,107,0.25); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(31,111,107,0.2); display: flex; align-items: center; justify-content: center; color: var(--brand-baia); flex-shrink: 0;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <strong style="color: var(--text-primary); font-size: 13.5px; display: block; margin-bottom: 2px;">Conta Mestre &amp; Super Administrador Protegida</strong>
          <p style="margin: 0; font-size: 12px; color: var(--text-secondary); line-height: 1.4;">
            A conta do Administrador Principal (<strong>admin@djumbai.ao</strong>) possui autoridade global e está protegida fora desta tabela operacional. A gestão das credenciais mestras é realizada nas <a href="<?= url('admin/definicoes') ?>" style="color: var(--brand-terra); font-weight: 700; text-decoration: none;">Definições da Plataforma</a>.
          </p>
        </div>
      </div>
      <button type="button" class="js-btn-novo-admin btn btn--primary btn--sm" style="display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>+ Novo Administrador</span>
      </button>
    </div>
  <?php endif; ?>

  <!-- ══ 5. TABELA DE UTILIZADORES / EQUIPA ═══════════════════════════════ -->
  <div style="background: var(--surface-card); border-radius: 14px; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-sm); overflow: hidden;">

    <?php if (empty($listaParaExibir)): ?>
      <div style="text-align: center; padding: 60px 24px; color: var(--text-muted);">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--surface-sunken); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <?php if ($aba === 'equipa'): ?>
          <p style="font-weight: 700; font-size: 1rem; color: var(--text-primary); margin: 0 0 6px;">Nenhum administrador adicional registado</p>
          <p style="font-size: 13px; margin: 0 0 16px;">O sistema está a ser gerido exclusivamente pela conta raiz do Super Administrador.</p>
          <button type="button" class="js-btn-novo-admin btn btn--primary btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Adicionar Novo Administrador</span>
          </button>
        <?php else: ?>
          <p style="font-weight: 700; font-size: 1rem; color: var(--text-primary); margin: 0 0 6px;">Nenhum registo encontrado</p>
          <p style="font-size: 13px; margin: 0;">Não foram encontrados utilizadores para os filtros selecionados.</p>
        <?php endif; ?>
      </div>
    <?php else: ?>

      <!-- Cabeçalho das Colunas -->
      <div style="display: grid; grid-template-columns: 2.2fr 1.1fr 1.1fr 1.1fr 0.8fr 2fr; gap: 0; padding: 12px 20px; background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle);">
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Utilizador / Documento</span>
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Localização</span>
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Contribuição</span>
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Último Acesso</span>
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Estado</span>
        <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; text-align: right;">Ações de Moderação</span>
      </div>

      <!-- Linhas dos Utilizadores -->
      <?php foreach ($listaParaExibir as $u):
        $isBanned = ($u['status'] ?? 'ativo') === 'banido';
        $isOnline = !empty($u['ultimo_acesso']) && strtotime($u['ultimo_acesso']) >= strtotime('-15 minutes');
        $avatarBg = $isBanned ? '#B4451F' : (['#1F6F6B','#3F5A3C','#6B5A4A','#D99A1E'][crc32($u['email'] ?? $u['nome']) % 4]);
        $biStatus = $u['bi_status'] ?? 'nao_enviado';
        $isVerified = ($biStatus === 'verificado');
      ?>
        <div class="user-table-row js-user-row" 
             data-id="<?= (int)$u['id'] ?>" 
             data-search="<?= strtolower(e($u['nome'] . ' ' . ($u['nome_completo_bi'] ?? '') . ' ' . $u['email'] . ' ' . $u['telefone'] . ' ' . ($u['bi_numero'] ?? '') . ' ' . ($u['bairro_nome'] ?? '') . ' ' . ($u['municipio_nome'] ?? ''))) ?>"
             title="Clique no utilizador para ver a sua ficha cívica completa">

          <!-- Coluna 1: Membro / BI -->
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 38px; height: 38px; border-radius: 50%; background: <?= $avatarBg ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: #FFF; flex-shrink: 0; position: relative; <?= $isVerified ? 'box-shadow: 0 0 0 2.5px #4ADE80;' : '' ?>">
              <?= strtoupper(substr($u['nome'], 0, 1)) ?>
              <?php if ($isOnline): ?>
                <div style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; background: #4CAF50; border: 2px solid var(--surface-card);"></div>
              <?php endif; ?>
            </div>

            <div style="min-width: 0;">
              <div style="font-weight: 700; font-size: 13.5px; color: var(--text-primary); display: flex; align-items: center; gap: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <span><?= e($u['nome']) ?></span>

                <!-- Badge de Verificação de BI -->
                <?php if ($biStatus === 'verificado'): ?>
                  <span class="badge-bi--verificado" title="Identidade verificada oficialmente">
                    BI <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  </span>
                <?php elseif ($biStatus === 'pendente'): ?>
                  <span class="badge-bi--pendente" title="Aguardando validação dos documentos">
                    BI Pendente <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                  </span>
                <?php elseif ($biStatus === 'rejeitado'): ?>
                  <span class="badge-bi--rejeitado" title="Documento recusado pela moderação">
                    BI Recusado <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </span>
                <?php else: ?>
                  <span class="badge-bi--nao-enviado">Sem BI</span>
                <?php endif; ?>
              </div>

              <div style="font-size: 11px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">
                <?= e($u['email'] ?: ($u['telefone'] ?: 'Sem contacto')) ?>
              </div>
            </div>
          </div>

          <!-- Coluna 2: Localização -->
          <div style="font-size: 12px; color: var(--text-secondary);">
            <div style="display: flex; align-items: center; gap: 4px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <strong><?= e($u['bairro_nome'] ?? '—') ?></strong>
            </div>
            <?php if (!empty($u['municipio_nome'])): ?>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;"><?= e($u['municipio_nome']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Coluna 3: Contribuição -->
          <div style="font-size: 12px;">
            <div style="color: var(--text-primary); font-weight: 600;"><?= (int)$u['total_reportes'] ?> reporte(s)</div>
            <div style="color: var(--text-muted); margin-top: 2px;"><?= (int)$u['total_comentarios'] ?> comentário(s)</div>
          </div>

          <!-- Coluna 4: Último Acesso -->
          <div style="font-size: 12px;">
            <?php if ($isOnline): ?>
              <span style="display: inline-flex; align-items: center; gap: 5px; color: #4CAF50; font-weight: 700; font-size: 11px;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: #4CAF50;"></span>
                Online agora
              </span>
            <?php elseif (!empty($u['ultimo_acesso'])): ?>
              <div style="color: var(--text-secondary);"><?= timeAgo($u['ultimo_acesso']) ?></div>
            <?php else: ?>
              <span style="color: var(--text-muted); font-style: italic;">Sem registo</span>
            <?php endif; ?>
          </div>

          <!-- Coluna 5: Estado da Conta -->
          <div>
            <?php if ($isBanned): ?>
              <span style="display: inline-flex; align-items: center; gap: 4px; background: rgba(180,69,31,0.1); color: var(--brand-terra); font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 20px;">
                Suspenso
              </span>
            <?php else: ?>
              <span style="display: inline-flex; align-items: center; gap: 4px; background: rgba(76,175,80,0.1); color: #2E7D32; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 20px;">
                Ativo
              </span>
            <?php endif; ?>
          </div>

          <!-- Coluna 6: Ações de Moderação -->
          <div class="js-row-actions" style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">

            <!-- Se BI pendente, atalhos rápidos de decisão -->
            <?php if ($biStatus === 'pendente'): ?>
              <form action="<?= url('admin/usuarios/' . $u['id'] . '/verificar-bi') ?>" method="POST" style="margin: 0;">
                <?= csrfField() ?>
                <input type="hidden" name="aba" value="<?= e($aba) ?>" />
                <input type="hidden" name="acao" value="aprovar" />
                <button type="submit" class="btn btn--primary btn--sm" style="display:inline-flex;align-items:center;gap:3px;font-size: 10.5px; padding: 4px 8px; background: #2E7D32;" title="Aprovar BI imediatamente">
                  Aprovar <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
              </form>

              <button type="button" class="js-btn-rejeitar-bi btn btn--ghost btn--sm" style="display:inline-flex;align-items:center;gap:3px;font-size: 10.5px; padding: 4px 8px; color: var(--brand-terra);" title="Rejeitar BI com motivo" data-id="<?= (int)$u['id'] ?>" data-nome="<?= e($u['nome']) ?>">
                Recusar <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            <?php endif; ?>

            <?php if (($u['tipo'] ?? 'cidadao') === 'cidadao'): ?>
              <!-- Botão Ver Ficha -->
              <button type="button" class="js-btn-ficha btn btn--ghost btn--sm" style="font-size: 11px; padding: 5px 8px; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 4px;" title="Ver Ficha / Dossiê do Cidadão" data-id="<?= (int)$u['id'] ?>">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Ficha</span>
              </button>

              <!-- Botão Aviso -->
              <button type="button" class="js-btn-warn btn btn--ghost btn--sm" style="font-size: 11px; padding: 5px 8px;" title="Enviar aviso de boas maneiras" data-id="<?= (int)$u['id'] ?>" data-nome="<?= e($u['nome']) ?>">
                Aviso
              </button>

              <!-- Botão Suspender / Reativar -->
              <form action="<?= url('admin/usuarios/' . $u['id'] . '/banir') ?>" method="POST" style="margin: 0;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn--ghost btn--sm" style="font-size: 11px; padding: 5px 8px; color: <?= $isBanned ? '#4CAF50' : 'var(--brand-terra)' ?>;">
                  <?= $isBanned ? 'Reativar' : 'Suspender' ?>
                </button>
              </form>
            <?php else: ?>
              <!-- Botão Ver Ficha para Membro da Equipa -->
              <button type="button" class="js-btn-ficha btn btn--ghost btn--sm" style="font-size: 11px; padding: 5px 8px; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 4px;" title="Ver Ficha do Administrador" data-id="<?= (int)$u['id'] ?>">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Ficha</span>
              </button>
            <?php endif; ?>

          </div>

        </div>
      <?php endforeach; ?>

    <?php endif; ?>

  </div>

</div>

<!-- ══ 5. MODAL: FICHA DO CIDADÃO & DOSSIÊ CÍVICO DETALHADO ═══════════ -->
<div class="modal-overlay" id="dossieModal">
  <div class="modal-box">
    
    <!-- Cabeçalho -->
    <div style="background: linear-gradient(135deg, #1A1410, #2D221A); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid var(--brand-terra);">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(180,69,31,0.2); display: flex; align-items: center; justify-content: center; color: var(--brand-terra);">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <span style="font-size: 10.5px; font-weight: 700; color: var(--brand-dendem); text-transform: uppercase; letter-spacing: 0.6px; display: block;">Dossiê Cívico do Munícipe</span>
          <h2 id="dosNome" style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #FFF;">—</h2>
        </div>
      </div>
      <button class="js-close-dossie" style="background: transparent; border: none; color: #FFF; font-size: 22px; cursor: pointer;">&times;</button>
    </div>

    <!-- Conteúdo -->
    <div style="padding: 24px;">

      <!-- Banner de Reputação & Selo Cívico -->
      <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
          <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Score de Confiabilidade Cívica</span>
          <div style="display: flex; align-items: baseline; gap: 8px;">
            <span id="dosScore" style="font-size: 1.8rem; font-weight: 800; color: var(--brand-baia); line-height: 1;">80</span>
            <span style="font-size: 12px; color: var(--text-muted);">/ 100 pontos</span>
          </div>
        </div>

        <div id="dosSeloBadge">
          <!-- Injetado dinamicamente via JS -->
        </div>
      </div>

      <!-- Grid de Contactos e Residência -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
        <div style="background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px;">
          <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">E-mail</span>
          <strong id="dosEmail" style="font-size: 12.5px; color: var(--text-primary); word-break: break-all;">—</strong>
        </div>
        <div style="background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px;">
          <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Telefone</span>
          <strong id="dosTelefone" style="font-size: 12.5px; color: var(--text-primary);">—</strong>
        </div>
        <div style="background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px;">
          <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Bairro / Município</span>
          <strong id="dosLocal" style="font-size: 12.5px; color: var(--text-primary);">—</strong>
        </div>
        <div style="background: var(--surface-elevated); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px;">
          <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Membro Desde</span>
          <strong id="dosCriadoEm" style="font-size: 12.5px; color: var(--text-primary);">—</strong>
        </div>
      </div>

      <!-- Dados de Identificação Civil (Sem fotos do BI) -->
      <div style="margin-bottom: 22px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
          <h3 style="font-size: 12px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.6px; margin: 0; display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
            Identificação Civil do Munícipe (BI Angola)
          </h3>
          <span id="dosBiStatusLabel" style="font-size: 11px; font-weight: 700;"></span>
        </div>

        <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 18px 20px; box-shadow: var(--shadow-sm);">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px;">
            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Nome Completo</span>
              <strong id="dosNomeCompletoBi" style="font-size: 13.5px; color: var(--text-primary); line-height: 1.3;">—</strong>
            </div>

            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Número do BI</span>
              <strong id="dosBiNumero" style="font-size: 13.5px; color: var(--text-primary);">—</strong>
            </div>

            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Sexo</span>
              <strong id="dosSexo" style="font-size: 13.5px; color: var(--text-primary);">—</strong>
            </div>

            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Idade</span>
              <strong id="dosIdade" style="font-size: 13.5px; color: var(--text-primary);">—</strong>
            </div>

            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Estado Civil</span>
              <strong id="dosEstadoCivil" style="font-size: 13.5px; color: var(--text-primary);">—</strong>
            </div>

            <div>
              <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Nacionalidade</span>
              <strong id="dosNacionalidade" style="font-size: 13.5px; color: #2E7D32; display: inline-flex; align-items: center; gap: 4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Angolano
              </strong>
            </div>
          </div>
        </div>

        <!-- Digitalização / Fotos do Bilhete de Identidade -->
        <div id="dosBiDocumentosBox" style="margin-top: 14px; display: none;">
          <span style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Documentos Digitalizados (BI Oficial)</span>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div id="dosBiFrenteContainer"></div>
            <div id="dosBiVersoContainer"></div>
          </div>
        </div>

        <!-- Se houver nota de rejeição anterior -->
        <div id="dosBiNotaBox" style="display: none; margin-top: 10px; padding: 10px 14px; background: rgba(180,69,31,0.08); border-radius: 8px; font-size: 12px; color: var(--brand-terra);"></div>

        <!-- Ações de Validação de BI no Modal -->
        <div id="dosBiAcoes" style="display: none; gap: 10px; margin-top: 14px;">
          <form id="formAprovarBiModal" method="POST" action="" style="flex: 1; margin: 0;">
            <?= csrfField() ?>
            <input type="hidden" name="aba" value="<?= e($aba) ?>" />
            <input type="hidden" name="acao" value="aprovar" />
            <button type="submit" class="btn btn--primary btn--sm" style="width: 100%; background: #2E7D32; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
              Aprovar Documento (Emitir Selo) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
          </form>

          <button type="button" id="btnRejeitarBiTrigger" class="btn btn--ghost btn--sm" style="flex: 1; color: var(--brand-terra); display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
            Recusar Documento com Motivo <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
      </div>

      <!-- Ocorrências Recentes do Cidadão -->
      <div>
        <h3 style="font-size: 12px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.6px; margin: 0 0 10px;">
          Últimas Ocorrências Submetidas
        </h3>
        <div id="dosOcorrenciasList" style="display: flex; flex-direction: column; gap: 8px;">
          <!-- Injetado dinamicamente via JS -->
        </div>
      </div>

    </div>

    <!-- Rodapé -->
    <div style="padding: 16px 24px; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end;">
      <button type="button" class="js-close-dossie btn btn--ghost btn--sm">
        Fechar Ficha
      </button>
    </div>

  </div>
</div>

<!-- ══ 6. MODAL: REJEITAR BI COM MOTIVO ════════════════════════════════ -->
<div class="modal-overlay" id="rejeitarBiModal">
  <div class="modal-box" style="max-width: 480px;">
    <div style="padding: 18px 24px; background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
      <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: var(--text-primary);">Motivo da Rejeição do BI</h3>
      <button class="js-close-rejeitar-bi" style="background: transparent; border: none; font-size: 20px; cursor: pointer;">&times;</button>
    </div>

    <form id="formRejeitarBi" method="POST" action="">
      <?= csrfField() ?>
      <input type="hidden" name="aba" value="<?= e($aba) ?>" />
      <input type="hidden" name="acao" value="rejeitar" />

      <div style="padding: 20px 24px;">
        <p style="font-size: 12.5px; color: var(--text-secondary); margin: 0 0 14px;">
          Selecione um motivo pré-definido ou descreva o problema na digitalização:
        </p>

        <!-- Presets -->
        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px;">
          <button type="button" class="js-preset-motivo" data-motivo="Imagem desfocada ou ilegível." style="font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;">
            Desfocado / Ilegível
          </button>
          <button type="button" class="js-preset-motivo" data-motivo="Documento cortado ou incompleto." style="font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;">
            Cortado / Incompleto
          </button>
          <button type="button" class="js-preset-motivo" data-motivo="Nome ou dados não correspondem ao perfil." style="font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;">
            Dados Divergentes
          </button>
        </div>

        <textarea id="inputMotivoRejeicao" name="motivo" rows="3" class="form-input" placeholder="Descreva o motivo da recusa..." required style="width: 100%;"></textarea>
      </div>

      <div style="padding: 14px 24px; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="js-close-rejeitar-bi btn btn--ghost btn--sm">Cancelar</button>
        <button type="submit" class="btn btn--primary btn--sm" style="background: var(--brand-terra);">
          Confirmar Rejeição
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ══ 7. MODAL: NOVO ADMINISTRADOR (EQUIPA) ═══════════════════════════ -->
<div class="modal-overlay" id="novoAdminModal">
  <div class="modal-box" style="max-width: 500px;">
    <div style="padding: 18px 24px; background: linear-gradient(135deg, #1A1410, #2D221A); border-bottom: 3px solid var(--brand-baia); display: flex; justify-content: space-between; align-items: center; color: #FFF;">
      <h3 style="margin: 0; font-size: 14px; font-weight: 800;">Adicionar Administrador à Equipa</h3>
      <button class="js-close-novo-admin" style="background: transparent; border: none; font-size: 20px; color: #FFF; cursor: pointer;">&times;</button>
    </div>

    <form method="POST" action="<?= url('admin/usuarios/novo-admin') ?>">
      <?= csrfField() ?>
      <div style="padding: 22px 24px; display: flex; flex-direction: column; gap: 14px;">
        <div>
          <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Nome Completo *</label>
          <input type="text" name="nome" class="form-input" placeholder="Ex: Eng. Manuel Silva" required />
        </div>

        <div>
          <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">E-mail Institucional *</label>
          <input type="email" name="email" class="form-input" placeholder="admin@djumbai.ao" required />
        </div>

        <div>
          <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Telefone (Opcional)</label>
          <input type="text" name="telefone" class="form-input" placeholder="+244 923 000 000" />
        </div>

        <div>
          <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Palavra-Passe Provisória *</label>
          <input type="password" name="senha" class="form-input" placeholder="Mínimo 6 caracteres" required minlength="6" />
        </div>
      </div>

      <div style="padding: 16px 24px; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="js-close-novo-admin btn btn--ghost btn--sm">Cancelar</button>
        <button type="submit" class="btn btn--primary btn--sm">Registar Administrador</button>
      </div>
    </form>
  </div>
</div>

<!-- ══ 8. MODAL: AVISO DE BONS MODOS ═══════════════════════════════════ -->
<div class="modal-overlay" id="warnModal">
  <div class="modal-box" style="max-width: 500px;">
    <div style="background: linear-gradient(135deg, #1A1410, #2D221A); padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid var(--brand-baia);">
      <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #FFF;">Enviar Notificação de Bons Modos</h3>
      <button class="js-close-warn" style="background: transparent; border: none; color: #FFF; font-size: 20px; cursor: pointer;">&times;</button>
    </div>

    <div style="padding: 20px 24px;">
      <div style="background: var(--surface-sunken); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 8px 12px; margin-bottom: 14px; font-size: 12px;">
        <span style="color: var(--text-muted);">Morador:</span> <strong id="warnUserName" style="color: var(--text-primary);"></strong>
      </div>

      <!-- Presets -->
      <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 14px;">
        <button type="button" class="js-warn-preset" data-preset="1" style="display:inline-flex;align-items:center;gap:4px;font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Respeito Mútuo</button>
        <button type="button" class="js-warn-preset" data-preset="2" style="display:inline-flex;align-items:center;gap:4px;font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Precisão de Local</button>
        <button type="button" class="js-warn-preset" data-preset="3" style="display:inline-flex;align-items:center;gap:4px;font-size: 11px; padding: 4px 8px; border-radius: 12px; background: var(--surface-elevated); border: 1px solid var(--border-subtle); cursor: pointer;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Moderação Geral</button>
      </div>

      <form id="warnForm" action="" method="POST">
        <?= csrfField() ?>
        <textarea id="warnMsgInput" name="mensagem" class="form-input" rows="4" placeholder="Escreva a orientação de conduta..." style="width: 100%; margin-bottom: 16px;" required></textarea>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="js-close-warn btn btn--ghost btn--sm">Cancelar</button>
          <button type="submit" class="btn btn--primary btn--sm">Enviar Notificação</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- SCRIPTS DE INTERATIVIDADE -->
<script nonce="<?= e(CSP_NONCE) ?>">
// ── BUSCA EM TEMPO REAL ──────────────────────────────────────────
document.getElementById('realTimeSearch')?.addEventListener('input', function(e) {
  const term = e.target.value.toLowerCase().trim();
  const rows = document.querySelectorAll('.js-user-row');
  
  rows.forEach(row => {
    const text = row.getAttribute('data-search') || '';
    if (text.includes(term)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});

const warnPresets = {
  1: "Estimado(a) morador(a), pedimos a gentileza de manter o respeito e linguagem cordial ao interagir com outros membros da comunidade na plataforma Djumbai.",
  2: "Estimado(a) morador(a), solicitamos que verifique com atenção a localização exata e o ponto de referência ao submeter novas ocorrências no seu bairro.",
  3: "AVISO DE CONDUTA: Recordamos que o Djumbai promove o diálogo construtivo e o respeito mútuo em todas as publicações."
};

function useWarnPreset(type) {
  if (warnPresets[type]) {
    document.getElementById('warnMsgInput').value = warnPresets[type];
  }
}

function openWarnModal(id, nome) {
  document.getElementById('warnUserName').textContent = nome;
  document.getElementById('warnMsgInput').value = '';
  document.getElementById('warnForm').action = '<?= url("admin/usuarios/") ?>' + id + '/notificar';
  document.getElementById('warnModal').classList.add('is-active');
}

function closeWarnModal() {
  document.getElementById('warnModal').classList.remove('is-active');
}

function abrirRejeitarBiModal(id, nome) {
  const form = document.getElementById('formRejeitarBi');
  form.action = '<?= url("admin/usuarios/") ?>' + id + '/verificar-bi';
  document.getElementById('inputMotivoRejeicao').value = '';
  document.getElementById('rejeitarBiModal').classList.add('is-active');
}

function fecharRejeitarBiModal() {
  document.getElementById('rejeitarBiModal').classList.remove('is-active');
}

function preencherMotivo(txt) {
  document.getElementById('inputMotivoRejeicao').value = txt;
}

function abrirNovoAdminModal() {
  document.getElementById('novoAdminModal').classList.add('is-active');
}

function fecharNovoAdminModal() {
  document.getElementById('novoAdminModal').classList.remove('is-active');
}

// ── DOSSIÊ DINÂMICO DO UTILIZADOR ────────────────────────────────
function carregarDossie(userId) {
  if (!userId) return;

  fetch('<?= url("admin/usuarios/") ?>' + userId + '/dossie')
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }
      preencherDossieModal(data);
    })
    .catch(err => {
      console.error('Erro ao carregar dossiê:', err);
    });
}

function preencherDossieModal(u) {
  document.getElementById('dosNome').textContent = u.nome || '—';
  document.getElementById('dosScore').textContent = (u.reputacao_score !== undefined && u.reputacao_score !== null) ? u.reputacao_score : 50;
  document.getElementById('dosEmail').textContent = u.email || 'Não associado';
  document.getElementById('dosTelefone').textContent = u.telefone || 'Não associado';
  document.getElementById('dosLocal').textContent = (u.bairro_nome || '—') + (u.municipio_nome ? (', ' + u.municipio_nome) : '');
  document.getElementById('dosCriadoEm').textContent = u.criado_em ? u.criado_em.substring(0, 10) : '—';

  // Badge de Status do Documento & Label
  const seloBox = document.getElementById('dosSeloBadge');
  const biStatusLabel = document.getElementById('dosBiStatusLabel');

  if (u.bi_status === 'verificado') {
    seloBox.innerHTML = '<span class="badge-bi--verificado" style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:6px 12px;">BI <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>';
    if (biStatusLabel) {
      biStatusLabel.textContent = 'Verificado';
      biStatusLabel.style.color = '#2E7D32';
    }
  } else if (u.bi_status === 'pendente') {
    seloBox.innerHTML = '<span class="badge-bi--pendente" style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:6px 12px;">Validação Pendente <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>';
    if (biStatusLabel) {
      biStatusLabel.textContent = 'Aguardando Validação';
      biStatusLabel.style.color = '#8C6211';
    }
  } else if (u.bi_status === 'rejeitado') {
    seloBox.innerHTML = '<span class="badge-bi--rejeitado" style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:6px 12px;">Documento Recusado <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>';
    if (biStatusLabel) {
      biStatusLabel.textContent = 'Recusado';
      biStatusLabel.style.color = 'var(--brand-terra)';
    }
  } else {
    seloBox.innerHTML = '<span class="badge-bi--nao-enviado" style="font-size:12px;padding:6px 12px;">Sem BI Submetido</span>';
    if (biStatusLabel) {
      biStatusLabel.textContent = 'Sem Documento';
      biStatusLabel.style.color = 'var(--text-muted)';
    }
  }

  // Preenchimento dos Dados Cívicos de Identificação
  document.getElementById('dosNomeCompletoBi').textContent = u.nome_completo_bi || u.nome || '—';
  
  const biNumEl = document.getElementById('dosBiNumero');
  if (biNumEl) biNumEl.textContent = u.bi_numero || 'Não indicado';

  // Sexo e Nacionalidade coerentes com o BI
  // Se o sexo for masculino será Angolano, se for feminino Angolana
  let generoCode = 'M';
  if (u.genero) {
    generoCode = u.genero.toUpperCase();
  } else if (u.nacionalidade && u.nacionalidade.toLowerCase() === 'angolana') {
    generoCode = 'F';
  } else if (u.estado_civil && (u.estado_civil.toLowerCase().includes('solteira') || u.estado_civil.toLowerCase().includes('casada') || u.estado_civil.toLowerCase().includes('divorciada') || u.estado_civil.toLowerCase().includes('viúva'))) {
    generoCode = 'F';
  }

  const isFeminino = (generoCode === 'F');
  const sexoTexto = isFeminino ? 'Feminino' : 'Masculino';
  const nacionalidadeTexto = isFeminino ? 'Angolana' : 'Angolano';

  const dosSexoEl = document.getElementById('dosSexo');
  if (dosSexoEl) {
    dosSexoEl.textContent = sexoTexto;
  }
  document.getElementById('dosNacionalidade').innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> ${nacionalidadeTexto}`;

  let idadeTxt = '—';
  if (u.idade && parseInt(u.idade) > 0) {
    idadeTxt = u.idade + ' anos';
  } else if (u.data_nascimento) {
    const anos = Math.floor((new Date() - new Date(u.data_nascimento)) / (365.25 * 24 * 3600 * 1000));
    if (!isNaN(anos) && anos > 0) idadeTxt = anos + ' anos';
  }
  document.getElementById('dosIdade').textContent = idadeTxt;
  document.getElementById('dosEstadoCivil').textContent = u.estado_civil || 'Solteiro(a)';

  // Visualização das fotos do BI (Frente e Verso)
  const docsBox = document.getElementById('dosBiDocumentosBox');
  const frenteCont = document.getElementById('dosBiFrenteContainer');
  const versoCont = document.getElementById('dosBiVersoContainer');

  if (docsBox && frenteCont && versoCont) {
    if (u.bi_frente || u.bi_verso) {
      docsBox.style.display = 'block';

      if (u.bi_frente) {
        const urlFrente = '<?= url("admin/usuarios/") ?>' + u.id + '/documento/frente';
        frenteCont.innerHTML = `
          <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Frente do BI</div>
          <a href="${urlFrente}" target="_blank" title="Clique para ampliar o documento" style="display:block;border-radius:8px;overflow:hidden;border:1px solid var(--border-subtle);background:#000;box-shadow:var(--shadow-sm);transition:opacity 0.2s;" onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='1'">
            <img src="${urlFrente}" alt="BI Frente" style="width:100%;height:110px;object-fit:cover;display:block;">
          </a>
        `;
      } else {
        frenteCont.innerHTML = `
          <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Frente do BI</div>
          <div style="height:110px;border-radius:8px;background:var(--surface-sunken);border:1px dashed var(--border-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:11px;">Não submetido</div>
        `;
      }

      if (u.bi_verso) {
        const urlVerso = '<?= url("admin/usuarios/") ?>' + u.id + '/documento/verso';
        versoCont.innerHTML = `
          <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Verso do BI</div>
          <a href="${urlVerso}" target="_blank" title="Clique para ampliar o documento" style="display:block;border-radius:8px;overflow:hidden;border:1px solid var(--border-subtle);background:#000;box-shadow:var(--shadow-sm);transition:opacity 0.2s;" onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='1'">
            <img src="${urlVerso}" alt="BI Verso" style="width:100%;height:110px;object-fit:cover;display:block;">
          </a>
        `;
      } else {
        versoCont.innerHTML = `
          <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">Verso do BI</div>
          <div style="height:110px;border-radius:8px;background:var(--surface-sunken);border:1px dashed var(--border-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:11px;">Não submetido</div>
        `;
      }
    } else {
      docsBox.style.display = 'none';
    }
  }

  // Nota de rejeição
  const notaBox = document.getElementById('dosBiNotaBox');
  if (u.bi_status === 'rejeitado' && u.bi_nota) {
    notaBox.style.display = 'block';
    notaBox.textContent = 'Motivo de recusa anterior: "' + u.bi_nota + '"';
  } else {
    notaBox.style.display = 'none';
  }

  // Ações de validação dentro do modal
  const acoesBox = document.getElementById('dosBiAcoes');
  if (u.bi_status === 'pendente') {
    acoesBox.style.display = 'flex';
    document.getElementById('formAprovarBiModal').action = '<?= url("admin/usuarios/") ?>' + u.id + '/verificar-bi';
    document.getElementById('btnRejeitarBiTrigger').onclick = function() {
      fecharDossieModal();
      abrirRejeitarBiModal(u.id, u.nome);
    };
  } else {
    acoesBox.style.display = 'none';
  }

  // Ocorrências abertas pelo munícipe
  const ocsList = document.getElementById('dosOcorrenciasList');
  if (u.ocorrencias_recentes && u.ocorrencias_recentes.length > 0) {
    ocsList.innerHTML = u.ocorrencias_recentes.map(o => `
      <div style="padding:8px 12px;background:var(--surface-elevated);border:1px solid var(--border-subtle);border-radius:6px;display:flex;justify-content:space-between;align-items:center;font-size:12px;">
        <a href="<?= url('problemas/') ?>${o.id}" target="_blank" style="font-weight:700;color:var(--text-primary);text-decoration:none;">${o.titulo}</a>
        <span style="color:${o.estado_cor};font-weight:700;font-size:11px;">${o.estado_nome}</span>
      </div>
    `).join('');
  } else {
    ocsList.innerHTML = '<span style="font-size:12px;color:var(--text-muted);font-style:italic;">Nenhuma ocorrência submetida por este cidadão.</span>';
  }

  document.getElementById('dossieModal').classList.add('is-active');
}

function fecharDossieModal() {
  document.getElementById('dossieModal').classList.remove('is-active');
}

// ── EVENT BINDINGS (CSP-compliant) ───────────────────────────────
document.querySelectorAll('.js-btn-novo-admin').forEach(b => b.addEventListener('click', abrirNovoAdminModal));
document.querySelectorAll('.js-user-row').forEach(r => r.addEventListener('click', function(e) {
  // Ignorar clique se foi em botões de ação ou links
  if (e.target.closest('.js-row-actions') || e.target.closest('button') || e.target.closest('a') || e.target.closest('form')) {
    return;
  }
  carregarDossie(this.dataset.id);
}));
document.querySelectorAll('.js-row-actions').forEach(el => el.addEventListener('click', function(e) { e.stopPropagation(); }));
document.querySelectorAll('.js-btn-ficha').forEach(b => b.addEventListener('click', function(e) {
  e.stopPropagation();
  carregarDossie(this.dataset.id);
}));
document.querySelectorAll('.js-btn-rejeitar-bi').forEach(b => b.addEventListener('click', function(e) {
  e.stopPropagation();
  abrirRejeitarBiModal(this.dataset.id, this.dataset.nome);
}));
document.querySelectorAll('.js-btn-warn').forEach(b => b.addEventListener('click', function(e) {
  e.stopPropagation();
  openWarnModal(this.dataset.id, this.dataset.nome);
}));
document.querySelectorAll('.js-close-dossie').forEach(b => b.addEventListener('click', fecharDossieModal));
document.querySelectorAll('.js-close-rejeitar-bi').forEach(b => b.addEventListener('click', fecharRejeitarBiModal));
document.querySelectorAll('.js-preset-motivo').forEach(b => b.addEventListener('click', function() { preencherMotivo(this.dataset.motivo); }));
document.querySelectorAll('.js-close-novo-admin').forEach(b => b.addEventListener('click', fecharNovoAdminModal));
document.querySelectorAll('.js-close-warn').forEach(b => b.addEventListener('click', closeWarnModal));
document.querySelectorAll('.js-warn-preset').forEach(b => b.addEventListener('click', function() { useWarnPreset(this.dataset.preset); }));

// Fechar com clique fora
document.querySelectorAll('.modal-overlay').forEach(modal => {
  modal.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('is-active');
    }
  });
});

// ── PESQUISA EM TEMPO REAL (SEM NECESSIDADE DE SUBMETER) ─────────
const inputBuscaUsuarios = document.querySelector('input[name="q"]');
if (inputBuscaUsuarios) {
  inputBuscaUsuarios.addEventListener('input', function() {
    const termo = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('.js-user-row');
    let encontrou = 0;

    rows.forEach(r => {
      const conteudoBusca = (r.dataset.search || r.textContent).toLowerCase();
      if (!termo || conteudoBusca.includes(termo)) {
        r.style.display = '';
        encontrou++;
      } else {
        r.style.display = 'none';
      }
    });

    let msgVazia = document.getElementById('jsUserEmptySearch');
    if (!msgVazia && rows.length > 0) {
      msgVazia = document.createElement('div');
      msgVazia.id = 'jsUserEmptySearch';
      msgVazia.style.cssText = 'text-align:center;padding:50px 20px;color:var(--text-muted);font-size:13.5px;font-weight:600;display:none;';
      msgVazia.innerHTML = '<div style="font-size:24px;margin-bottom:8px;">🔍</div>Nenhum utilizador encontrado com este termo.';
      rows[0].parentNode.appendChild(msgVazia);
    }
    if (msgVazia) {
      msgVazia.style.display = (encontrou === 0 && rows.length > 0) ? 'block' : 'none';
    }
  });
}
</script>
