<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: home/como_funciona.php                            ║
     ╚══════════════════════════════════════════════════════════╝ -->

<section class="section">
  <div class="container" style="max-width: 960px;">

    <div class="section-header" style="text-align: center; margin-bottom: var(--space-12);">
      <span class="section-header__kicker">Guia de Participação</span>
      <h1 class="section-header__title" style="margin-top: 4px;">Como Funciona o Djumbai</h1>
      <p class="section-header__subtitle" style="margin-inline: auto;">
        Um canal cívico direto, transparente e comunitário para dar visibilidade aos desafios dos bairros de Angola.
      </p>
    </div>

    <!-- Etapas Detalhadas -->
    <div style="display: flex; flex-direction: column; gap: var(--space-8); margin-bottom: var(--space-12);">

      <div class="step-card" style="display: flex; gap: var(--space-6); align-items: flex-start;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--brand-terra); color: #FFF; font-size: var(--font-lg); font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</div>
        <div>
          <h2 style="font-size: var(--font-lg); font-weight: 800; margin-bottom: 8px;">Registar a Ocorrência com Evidências</h2>
          <p style="color: var(--text-secondary); line-height: 1.7; font-size: var(--font-base);">
            Qualquer cidadão registado pode reportar um problema que afete a sua rua ou bairro — como fugas ou corte de água, buracos intransitáveis, acúmulo de lixo ou postes sem iluminação. Adiciona o título, a localização precisa e, sempre que possível, uma fotografia clara.
          </p>
        </div>
      </div>

      <div class="step-card" style="display: flex; gap: var(--space-6); align-items: flex-start;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--brand-baia); color: #FFF; font-size: var(--font-lg); font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</div>
        <div>
          <h2 style="font-size: var(--font-lg); font-weight: 800; margin-bottom: 8px;">Validação e Apoio Comunitário ("Também Acontece Aqui")</h2>
          <p style="color: var(--text-secondary); line-height: 1.7; font-size: var(--font-base);">
            Os outros moradores da mesma zona que acedem à plataforma podem confirmar a ocorrência com um simples clique. Quantas mais confirmações um problema reunir, maior a sua relevância e urgência no radar das autoridades e da comunidade.
          </p>
        </div>
      </div>

      <div class="step-card" style="display: flex; gap: var(--space-6); align-items: flex-start;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--brand-capim); color: #FFF; font-size: var(--font-lg); font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</div>
        <div>
          <h2 style="font-size: var(--font-lg); font-weight: 800; margin-bottom: 8px;">Monitorização e Resolução Transparente</h2>
          <p style="color: var(--text-secondary); line-height: 1.7; font-size: var(--font-base);">
            As equipas da administração municipal e organizações parceiras acompanham os reportes e atualizam o estado do problema de <em>Pendente</em> para <em>Em análise</em> e, finalmente, <em>Resolvido</em>. Todas as alterações ficam registadas num histórico público auditável.
          </p>
        </div>
      </div>

    </div>

    <!-- Regras da Comunidade & Apoio (Definidas pelo Admin) -->
    <?php $regrasComunidade = siteSetting('regras_comunidade'); ?>
    <?php if (!empty($regrasComunidade)): ?>
      <div style="background:var(--surface-sunken);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-12);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div style="width:32px;height:32px;border-radius:8px;background:rgba(217,154,30,0.15);display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <h3 style="margin:0;font-size:1.1rem;font-weight:800;color:var(--text-primary);">Regras de Conduta Cívica</h3>
        </div>
        <p style="font-size:var(--font-sm);color:var(--text-secondary);line-height:1.7;margin:0 0 16px;white-space:pre-line;">
          <?= e($regrasComunidade) ?>
        </p>
        <div style="display:flex;gap:20px;flex-wrap:wrap;border-top:1px solid var(--border-subtle);padding-top:14px;font-size:12px;color:var(--text-muted);">
          <span><strong>E-mail de Apoio:</strong> <?= e(siteSetting('email_suporte', 'suporte@djumbai.ao')) ?></span>
          <span><strong>Linha de Emergência:</strong> <?= e(siteSetting('telefone_emergencia', '+244 923 000 000')) ?></span>
        </div>
      </div>
    <?php endif; ?>

    <!-- CTA Final -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: var(--space-8); text-align: center;">
      <h2 style="font-size: var(--font-xl); font-weight: 800; margin-bottom: 8px;">Pronto para transformar a sua comunidade?</h2>
      <p style="color: var(--text-secondary); max-width: 480px; margin-inline: auto; margin-bottom: var(--space-6);">
        Crie a sua conta gratuita hoje mesmo e junte-se aos milhares de angolanos que estão a construir bairros melhores.
      </p>
      <div style="display: flex; gap: var(--space-3); justify-content: center; flex-wrap: wrap;">
        <a href="<?= url('cadastro') ?>" class="btn btn--primary btn--lg">Criar Conta Gratuita</a>
        <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--lg">Explorar Ocorrências</a>
      </div>
    </div>

  </div>
</section>
