<!-- ╔══════════════════════════════════════════════════════════╗
     ║  VIEW: problemas/show.php (Redesenho com Respostas)      ║
     ╚══════════════════════════════════════════════════════════╝ -->

<div class="container" style="padding-block: var(--space-8);">

  <!-- Breadcrumb elegante -->
  <nav aria-label="Navegação de localização" style="margin-bottom: var(--space-6);">
    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted);">
      <a href="<?= (isLoggedIn() && !isAdmin()) ? url('perfil') : url() ?>" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Início
      </a>
      <span style="color: var(--border-subtle);">/</span>
      <a href="<?= url('problemas') ?>" style="color: var(--text-muted); text-decoration: none;">Ocorrências</a>
      <span style="color: var(--border-subtle);">/</span>
      <span style="color: var(--text-primary); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 280px;"><?= e($problema['titulo']) ?></span>
    </div>
  </nav>

  <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-8); align-items: start;">
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: var(--space-8); align-items: start;">

      <!-- ── COLUNA PRINCIPAL ────────────────────────────────────── -->
      <div>

        <!-- Card da Ocorrência -->
        <article style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm); margin-bottom: 28px;">

          <!-- Topbar do Card: Estado & Autor -->
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; padding-bottom: 16px; border-bottom: 1px solid var(--border-subtle);">
            
            <div style="display: flex; align-items: center; gap: 10px;">
              <span class="badge" style="background: <?= e($problema['estado_cor']) ?>18; color: <?= e($problema['estado_cor']) ?>; border: 1px solid <?= e($problema['estado_cor']) ?>44; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: <?= e($problema['estado_cor']) ?>;"></span>
                <?= e($problema['estado_nome']) ?>
              </span>

              <span style="background: var(--surface-sunken); color: var(--text-secondary); border: 1px solid var(--border-subtle); font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                <?= e($problema['categoria_nome']) ?>
              </span>
            </div>

            <!-- Autor -->
            <div style="display: flex; align-items: center; gap: 10px;">
              <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--brand-terra); color: #FFF; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center;">
                <?= strtoupper(substr($problema['autor_nome'], 0, 1)) ?>
              </div>
              <div style="font-size: 12px; line-height: 1.3;">
                <strong style="color: var(--text-primary); display: block;"><?= e($problema['autor_nome']) ?></strong>
                <span style="color: var(--text-muted); font-size: 11px;"><?= timeAgo($problema['criado_em']) ?></span>
              </div>
            </div>

          </div>

          <!-- Título Principal -->
          <h1 style="font-family: var(--font-display); font-size: 1.65rem; font-weight: 800; color: var(--text-primary); line-height: 1.25; margin: 0 0 20px;">
            <?= e($problema['titulo']) ?>
          </h1>

          <!-- Fotografia da Ocorrência (se existir) -->
          <?php if (!empty($problema['foto'])): ?>
            <div style="margin-bottom: 24px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-subtle); background: var(--surface-dark); max-height: 440px; box-shadow: var(--shadow-sm);">
              <img src="<?= url($problema['foto']) ?>" alt="Fotografia do problema: <?= e($problema['titulo']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
            </div>
          <?php endif; ?>

          <!-- Descrição Detalhada -->
          <div style="font-size: 14.5px; color: var(--text-secondary); line-height: 1.75; margin-bottom: 28px; white-space: pre-line;">
            <?= e($problema['descricao']) ?>
          </div>

          <!-- Painel de Localização & Detalhes -->
          <div style="background: var(--surface-sunken); border-radius: 12px; border: 1px solid var(--border-subtle); padding: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            
            <div style="display: flex; gap: 10px; align-items: flex-start;">
              <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(180,69,31,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              </div>
              <div>
                <span style="display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Bairro / Localidade</span>
                <strong style="font-size: 13.5px; color: var(--text-primary);"><?= e($problema['bairro_nome']) ?></strong>
                <span style="display: block; font-size: 11.5px; color: var(--text-muted);"><?= e($problema['municipio_nome']) ?> · <?= e($problema['provincia_nome']) ?></span>
              </div>
            </div>

            <?php if (!empty($problema['referencia_local'])): ?>
              <div style="display: flex; gap: 10px; align-items: flex-start;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(217,154,30,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-dendem)" stroke-width="2"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                  <span style="display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Ponto de Referência</span>
                  <span style="font-size: 13px; color: var(--text-primary); font-weight: 600;"><?= e($problema['referencia_local']) ?></span>
                </div>
              </div>
            <?php endif; ?>

          </div>

        </article>

        <!-- ── SECÇÃO DE COMENTÁRIOS E RESPOSTAS ─────────────────── -->
        <section style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm);">
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-subtle);">
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-baia)" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              Discussão Comunitária
            </h2>
            <span style="font-size: 11px; font-weight: 700; background: var(--surface-sunken); border: 1px solid var(--border-subtle); color: var(--text-secondary); padding: 4px 12px; border-radius: 20px;">
              <?= count($comentarios) ?> comentário<?= count($comentarios) !== 1 ? 's' : '' ?>
            </span>
          </div>

          <!-- Formulário de Comentário Principal -->
          <?php if (isLoggedIn()): ?>
            <div style="margin-bottom: 32px; background: var(--surface-sunken); border-radius: 12px; border: 1px solid var(--border-subtle); padding: 20px;">
              <h3 style="font-size: 13px; font-weight: 700; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--brand-terra)" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Deixar um Comentário / Atualização
              </h3>

              <form action="<?= url('problemas/' . $problema['id'] . '/comentar') ?>" method="POST">
                <?= csrfField() ?>
                <div style="margin-bottom: 14px;">
                  <textarea name="texto" rows="3" class="form-textarea" placeholder="Partilhe informações adicionais sobre esta ocorrência (ex: estado atual da rua, horários, equipas no local)..." required style="width: 100%; font-size: 13.5px; line-height: 1.5; resize: vertical; box-sizing: border-box;"></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-size: 11px; color: var(--text-muted);">Mantenha o respeito e a precisão nas informações.</span>
                  <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; background: var(--brand-terra); color: #FFF; border: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.15s;" onmouseenter="this.style.background='#9E3A1A'" onmouseleave="this.style.background='var(--brand-terra)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Publicar Comentário
                  </button>
                </div>
              </form>
            </div>
          <?php else: ?>
            <div style="margin-bottom: 28px; background: var(--surface-sunken); border-radius: 12px; border: 1px dashed var(--border-subtle); padding: 20px; text-align: center;">
              <p style="font-size: 13.5px; color: var(--text-muted); margin: 0 0 12px;">
                Precisa de ter conta para participar na discussão comunitária.
              </p>
              <button type="button" onclick="openAuthPromptModal('comentar')" class="btn btn--primary btn--sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Comentar ou Responder
              </button>
            </div>
          <?php endif; ?>

          <!-- Lista de Comentários & Respostas Encadeadas -->
          <?php if (empty($comentarios)): ?>
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--border-subtle)" stroke-width="1.5" style="margin-bottom: 10px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <p style="font-weight: 600; margin: 0 0 4px; font-size: 14px; color: var(--text-secondary);">Ainda não existem comentários</p>
              <p style="font-size: 12px; margin: 0;">Seja o primeiro morador a comentar ou adicionar detalhes sobre este caso.</p>
            </div>
          <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
              <?php foreach ($comentarios as $c): ?>
                <div style="padding: 18px 20px; background: var(--surface-sunken); border-radius: 12px; border: 1px solid var(--border-subtle);">
                  
                  <!-- Autor e Meta do Comentário Pai -->
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--brand-baia); color: #FFF; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center;">
                        <?= strtoupper(substr($c['autor_nome'], 0, 1)) ?>
                      </div>
                      <div>
                        <strong style="color: var(--text-primary); font-size: 13.5px; display: block; line-height: 1.2;"><?= e($c['autor_nome']) ?></strong>
                        <?php if (($c['autor_tipo'] ?? 'cidadao') === 'admin'): ?>
                          <span style="font-size: 9.5px; font-weight: 700; color: var(--brand-terra); text-transform: uppercase;">Gestor Municipal</span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);"><?= timeAgo($c['criado_em']) ?></span>
                  </div>

                  <!-- Texto do Comentário -->
                  <p style="font-size: 13.5px; color: var(--text-primary); line-height: 1.55; margin: 0 0 12px 42px; white-space: pre-line;">
                    <?= e($c['texto']) ?>
                  </p>

                  <!-- Botão para Responder -->
                  <?php if (isLoggedIn()): ?>
                    <div style="margin-left: 42px; margin-bottom: 8px;">
                      <button type="button" onclick="toggleReplyForm(<?= $c['id'] ?>)" style="background: none; border: none; padding: 0; color: var(--brand-terra); font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                        Responder
                      </button>
                    </div>

                    <!-- Formulário Inline de Resposta (Escondido por padrão) -->
                    <div id="reply-form-<?= $c['id'] ?>" style="display: none; margin-left: 42px; margin-top: 12px; background: var(--surface-card); padding: 14px; border-radius: 10px; border: 1px solid var(--border-subtle);">
                      <form action="<?= url('problemas/' . $problema['id'] . '/comentar') ?>" method="POST">
                        <?= csrfField() ?>
                        <input type="hidden" name="parent_id" value="<?= $c['id'] ?>">
                        <textarea name="texto" rows="2" class="form-textarea" placeholder="Escreva a sua resposta para <?= e(addslashes($c['autor_nome'])) ?>..." required style="width: 100%; font-size: 13px; margin-bottom: 10px; resize: vertical; box-sizing: border-box;"></textarea>
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                          <button type="button" onclick="toggleReplyForm(<?= $c['id'] ?>)" class="btn btn--ghost btn--sm" style="padding: 4px 10px; font-size: 11px;">Cancelar</button>
                          <button type="submit" class="btn btn--primary btn--sm" style="padding: 4px 12px; font-size: 11px;">Enviar Resposta</button>
                        </div>
                      </form>
                    </div>
                  <?php else: ?>
                    <div style="margin-left: 42px; margin-bottom: 8px;">
                      <button type="button" onclick="openAuthPromptModal('comentar')" style="background: none; border: none; padding: 0; color: var(--brand-terra); font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                        Responder
                      </button>
                    </div>
                  <?php endif; ?>

                  <!-- ── RESPOSTAS ENCADEADAS ────────────────────────── -->
                  <?php if (!empty($c['respostas'])): ?>
                    <div style="margin-left: 42px; margin-top: 14px; display: flex; flex-direction: column; gap: 10px; border-left: 2px solid var(--brand-dendem); padding-left: 14px;">
                      <?php foreach ($c['respostas'] as $resp): ?>
                        <div style="background: var(--surface-card); padding: 12px 14px; border-radius: 8px; border: 1px solid var(--border-subtle);">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                              <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--brand-terra); color: #FFF; font-weight: 700; font-size: 11px; display: flex; align-items: center; justify-content: center;">
                                <?= strtoupper(substr($resp['autor_nome'], 0, 1)) ?>
                              </div>
                              <strong style="color: var(--text-primary); font-size: 12.5px;"><?= e($resp['autor_nome']) ?></strong>
                              <span style="background: rgba(217,154,30,0.15); color: var(--brand-dendem); font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">Resposta</span>
                            </div>
                            <span style="font-size: 10.5px; color: var(--text-muted);"><?= timeAgo($resp['criado_em']) ?></span>
                          </div>
                          <p style="font-size: 13px; color: var(--text-primary); line-height: 1.45; margin: 0 0 0 32px; white-space: pre-line;">
                            <?= e($resp['texto']) ?>
                          </p>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </section>

      </div>

      <!-- ── BARRA LATERAL (VOTAÇÃO & AÇÕES) ───────────────────── -->
      <aside style="position: sticky; top: 96px;">
        <div style="background: var(--surface-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 24px; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 20px;">
          
          <!-- Contagem de Apoios -->
          <div style="text-align: center; padding-bottom: 18px; border-bottom: 1px solid var(--border-subtle);">
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--brand-baia); line-height: 1; margin-bottom: 4px;">
              <?= number_format($problema['total_confirmacoes']) ?>
            </div>
            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px;">Moradores Apoiaram</span>
          </div>

          <!-- Botão de Confirmação/Apoio -->
          <?php if (isLoggedIn()): ?>
            <button class="btn <?= $jaConfirmou ? 'btn--secondary' : 'btn--primary' ?> btn--full btn--lg js-vote-btn <?= $jaConfirmou ? 'is-voted' : '' ?>" data-id="<?= $problema['id'] ?>" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border-radius: 10px; font-weight: 700;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
              <span><?= $jaConfirmou ? 'Apoiado por si' : 'Apoiar esta causa' ?></span>
            </button>
            <p style="font-size: 11.5px; color: var(--text-muted); text-align: center; margin: 0; line-height: 1.4;">
              O seu apoio ajuda a priorizar a intervenção das autoridades municipais.
            </p>
          <?php else: ?>
            <button type="button" onclick="openAuthPromptModal('confirmar')" class="btn btn--primary btn--full btn--lg" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border-radius: 10px; font-weight: 700;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
              Apoiar esta causa
            </button>
            <p style="font-size: 11.5px; color: var(--text-muted); text-align: center; margin: 0; line-height: 1.4;">
              É necessário ter conta no Djumbai para validar ocorrências comunitárias.
            </p>
          <?php endif; ?>

          <!-- Ações Secundárias -->
          <div style="padding-top: 16px; border-top: 1px solid var(--border-subtle); display: flex; flex-direction: column; gap: 8px;">
            <a href="<?= url('reportar') ?>" class="btn btn--ghost btn--full btn--sm" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Reportar Outro Problema
            </a>
            <a href="<?= url('problemas') ?>" class="btn btn--ghost btn--full btn--sm" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
              Voltar às Ocorrências
            </a>
          </div>

        </div>
      </aside>

    </div>
  </div>

</div>

<script>
function toggleReplyForm(commentId) {
  const form = document.getElementById('reply-form-' + commentId);
  if (!form) return;
  if (form.style.display === 'none' || form.style.display === '') {
    form.style.display = 'block';
    const txt = form.querySelector('textarea');
    if (txt) txt.focus();
  } else {
    form.style.display = 'none';
  }
}
</script>
