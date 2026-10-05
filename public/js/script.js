/**
 * DJUMBAI – Enterprise Front-End & Security-Hardened Client Engine
 */

(function () {
  'use strict';

  /* ── 1. POP-UP TOAST NOTIFICATION SYSTEM (10s DURAÇÃO) ── */
  let toastWrapper = document.querySelector('.dj-pop-toast-wrapper');
  if (!toastWrapper) {
    toastWrapper = document.createElement('div');
    toastWrapper.className = 'dj-pop-toast-wrapper';
    toastWrapper.setAttribute('aria-live', 'polite');
    toastWrapper.setAttribute('aria-atomic', 'true');
    document.body.appendChild(toastWrapper);
  }

  window.showToast = function (message, type = 'success', customTitle = null) {
    if (!toastWrapper || !document.body.contains(toastWrapper)) {
      toastWrapper = document.createElement('div');
      toastWrapper.className = 'dj-pop-toast-wrapper';
      toastWrapper.setAttribute('aria-live', 'polite');
      toastWrapper.setAttribute('aria-atomic', 'true');
      document.body.appendChild(toastWrapper);
    }

    const toast = document.createElement('div');
    toast.className = `dj-pop-toast dj-pop-toast--${type}`;
    toast.setAttribute('role', 'alert');

    const titles = {
      success: 'Sucesso',
      error: 'Atenção',
      warning: 'Aviso',
      info: 'Informação'
    };
    const titleText = customTitle || titles[type] || 'Djumbai';

    let iconSvg = '';
    if (type === 'success') {
      iconSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
    } else if (type === 'error') {
      iconSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    } else if (type === 'warning') {
      iconSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
    } else {
      iconSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
    }

    toast.innerHTML = `
      <div class="dj-pop-toast__body">
        <div class="dj-pop-toast__icon">${iconSvg}</div>
        <div class="dj-pop-toast__content">
          <h5 class="dj-pop-toast__title"></h5>
          <p class="dj-pop-toast__msg"></p>
        </div>
        <button type="button" class="dj-pop-toast__close" aria-label="Fechar">&times;</button>
      </div>
      <div class="dj-pop-toast__progress">
        <div class="dj-pop-toast__bar"></div>
      </div>
    `;

    toast.querySelector('.dj-pop-toast__title').textContent = titleText;
    toast.querySelector('.dj-pop-toast__msg').textContent = String(message);

    const closeBtn = toast.querySelector('.dj-pop-toast__close');
    let isDismissed = false;
    const dismiss = () => {
      if (isDismissed) return;
      isDismissed = true;
      toast.classList.add('dj-pop-toast--hiding');
      setTimeout(() => {
        if (toast && toast.parentElement) toast.remove();
      }, 350);
    };

    closeBtn.addEventListener('click', dismiss);
    toastWrapper.appendChild(toast);

    // Auto-dismiss após 10 segundos
    setTimeout(dismiss, 10000);
  };

  /* ── 2. CSRF TOKEN HELPER ───────────────────────────────── */
  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  /* ── 3. NAVBAR & MOBILE DRAWER ──────────────────────────── */
  const navToggle = document.getElementById('navToggle');
  const mobileDrawer = document.getElementById('mobileDrawer');

  if (navToggle && mobileDrawer) {
    navToggle.addEventListener('click', () => {
      const isOpen = mobileDrawer.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    document.addEventListener('click', (e) => {
      if (mobileDrawer.classList.contains('is-open') && !mobileDrawer.contains(e.target) && !navToggle.contains(e.target)) {
        mobileDrawer.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ── 4. CONTADORES ANIMADOS ─────────────────────────────── */
  const statNumbers = document.querySelectorAll('[data-target]');

  function animateCounter(el, target, duration = 1200) {
    const start = performance.now();

    function tick(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.round(eased * target);

      el.textContent = current.toLocaleString('pt-PT');

      if (progress < 1) {
        requestAnimationFrame(tick);
      } else {
        el.textContent = target.toLocaleString('pt-PT');
      }
    }

    requestAnimationFrame(tick);
  }

  if ('IntersectionObserver' in window && statNumbers.length) {
    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const el = entry.target;
            const target = parseInt(el.dataset.target, 10);
            if (!isNaN(target)) animateCounter(el, target);
            counterObserver.unobserve(el);
          }
        });
      },
      { threshold: 0.4 }
    );

    statNumbers.forEach((el) => counterObserver.observe(el));
  }

  /* ── 5. CONFIRMAÇÃO COMUNITÁRIA COM AJAX & CSRF BLINDADO ── */
  document.addEventListener('click', async (e) => {
    const voteBtn = e.target.closest('.js-vote-btn');
    if (voteBtn) {
      e.preventDefault();
      const probId = voteBtn.dataset.id;
      const countEl = voteBtn.querySelector('.js-vote-count');

      // Se for visitante (não logado), exibir modal 'Desejas entrar ou participar?'
      if (typeof window.IS_LOGGED_IN !== 'undefined' && !window.IS_LOGGED_IN) {
        if (typeof window.openAuthPromptModal === 'function') {
          window.openAuthPromptModal('confirmar');
        }
        return;
      }

      // Se não tiver ID (ex: preview estático), apenas simula visualmente
      if (!probId) {
        if (countEl) {
          const current = parseInt(countEl.textContent, 10) || 0;
          countEl.textContent = current + 1;
        }
        window.showToast('Obrigado! A sua confirmação fortalece este reporte.', 'success');
        return;
      }

      // Requisição AJAX real autenticada com CSRF
      const csrf = getCsrfToken();
      const baseUrl = window.location.pathname.startsWith('/djumbai/public') ? '/djumbai/public' : '';

      try {
        const res = await fetch(`${baseUrl}/confirmar/${probId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-Token': csrf,
            'Content-Type': 'application/json',
          },
        });

        if (res.status === 401 || res.status === 403) {
          if (typeof window.openAuthPromptModal === 'function') {
            window.openAuthPromptModal('confirmar');
          } else {
            window.showToast('Inicie sessão para confirmar este problema.', 'warning');
          }
          return;
        }

        if (res.status === 429) {
          window.showToast('Demasiadas confirmações. Aguarde um instante.', 'warning');
          return;
        }

        const data = await res.json();

        if (data.erro) {
          window.showToast(data.erro, 'warning');
          return;
        }

        if (countEl && typeof data.total !== 'undefined') {
          countEl.textContent = data.total;
        }

        if (data.confirmado) {
          voteBtn.classList.add('is-voted');
          window.showToast('Confirmação registada com sucesso!', 'success');
        } else {
          voteBtn.classList.remove('is-voted');
          window.showToast('Confirmação removida.', 'info');
        }
      } catch (err) {
        window.showToast('Erro de comunicação. Tente novamente.', 'warning');
      }
    }
  });

  /* ── 6. SELETORES GEOGRÁFICOS EM CASCATA (ANGOLA) ───────── */
  const angolaGeoData = {
    1: { // Luanda
      municipios: {
        1: { nome: 'Luanda (Sede)', bairros: ['Alvalade', 'Ingombota', 'Maianga', 'Rangel', 'Sambizanga', 'Samba', 'Miramar', 'Mutamba', 'Cidade Alta', 'Marçal'] },
        2: { nome: 'Belas', bairros: ['Talatona', 'Camama', 'Kilamba', 'Benfica', 'Morro Bento'] },
        3: { nome: 'Cacuaco', bairros: ['Cacuaco Centro', 'Funda', 'Sequele'] },
        4: { nome: 'Cazenga', bairros: ['Cazenga Centro', 'Hoji-ya-Henda', 'Tala Hady', 'Kalawenda'] },
        5: { nome: 'Kilamba Kiaxi', bairros: ['Kilamba Kiaxi', 'Palanca', 'Golfe', 'Cassenda'] },
        6: { nome: 'Viana', bairros: ['Viana Centro', 'Mulenvos', 'Zango', 'Estalagem'] }
      }
    },
    2: { // Benguela
      municipios: {
        7: { nome: 'Benguela', bairros: ['Benguela Centro', 'Praia Morena', 'Benfica'] },
        8: { nome: 'Lobito', bairros: ['Restinga', 'Caponte', 'Alto Liro'] }
      }
    },
    3: { // Huambo
      municipios: {
        9: { nome: 'Huambo', bairros: ['Huambo Centro', 'São Pedro', 'Fátima'] }
      }
    }
  };

  const selProvincia = document.getElementById('regProvincia');
  const selMunicipio = document.getElementById('regMunicipio');
  const selBairro = document.getElementById('regBairro');

  if (selProvincia && selMunicipio && selBairro) {
    function populateMunicipios() {
      const provId = selProvincia.value;
      const prov = angolaGeoData[provId];
      selMunicipio.innerHTML = '';
      selBairro.innerHTML = '';

      if (prov && prov.municipios) {
        Object.keys(prov.municipios).forEach((mId, index) => {
          const m = prov.municipios[mId];
          const opt = document.createElement('option');
          opt.value = mId;
          opt.textContent = m.nome;
          if (index === 0) opt.selected = true;
          selMunicipio.appendChild(opt);
        });
        populateBairros();
      } else {
        const optM = document.createElement('option');
        optM.value = '1';
        optM.textContent = 'Município Sede';
        selMunicipio.appendChild(optM);

        const optB = document.createElement('option');
        optB.value = '1';
        optB.textContent = 'Bairro Central';
        selBairro.appendChild(optB);
      }
    }

    function populateBairros() {
      const provId = selProvincia.value;
      const munId = selMunicipio.value;
      selBairro.innerHTML = '';

      if (angolaGeoData[provId] && angolaGeoData[provId].municipios[munId]) {
        const bairros = angolaGeoData[provId].municipios[munId].bairros;
        bairros.forEach((bName, index) => {
          const opt = document.createElement('option');
          opt.value = index + 1;
          opt.textContent = bName;
          if (index === 0) opt.selected = true;
          selBairro.appendChild(opt);
        });
      } else {
        const optB = document.createElement('option');
        optB.value = '1';
        optB.textContent = 'Centro / Sede';
        selBairro.appendChild(optB);
      }
    }

    selProvincia.addEventListener('change', populateMunicipios);
    selMunicipio.addEventListener('change', populateBairros);
  }

  /* ── 7. MÁSCARA INTELIGENTE PARA TELEFONE (+244 ANGOLA) ──── */
  const phoneInputs = document.querySelectorAll('input[type="tel"]');
  phoneInputs.forEach((input) => {
    input.addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '');
      if (val.startsWith('244')) val = val.substring(3);
      val = val.substring(0, 9);

      if (val.length > 0) {
        let formatted = '+244 ';
        if (val.length > 3 && val.length <= 6) {
          formatted += val.substring(0, 3) + ' ' + val.substring(3);
        } else if (val.length > 6) {
          formatted += val.substring(0, 3) + ' ' + val.substring(3, 6) + ' ' + val.substring(6);
        } else {
          formatted += val;
        }
        e.target.value = formatted;
      }
    });
  });

  /* ── 8. MOSTRAR / OCULTAR PALAVRA-PASSE ───────────────────── */
  function setupPwToggle(btnId, inputId) {
    const btn = document.getElementById(btnId);
    const input = document.getElementById(inputId);

    if (!btn || !input) return;

    btn.addEventListener('click', () => {
      const isPw = input.type === 'password';
      input.type = isPw ? 'text' : 'password';

      btn.innerHTML = isPw
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    });
  }

  setupPwToggle('togglePasswordBtn', 'loginPassword');
  setupPwToggle('toggleRegPasswordBtn', 'regPassword');

  /* ── 9. MEDIDOR EM TEMPO REAL DE FORÇA DA SENHA ──────────── */
  const regPwInput = document.getElementById('regPassword');
  const pwBars = document.querySelectorAll('.strength-bar');

  if (regPwInput && pwBars.length) {
    regPwInput.addEventListener('input', (e) => {
      const val = e.target.value;
      pwBars.forEach((bar) => { bar.className = 'strength-bar'; });

      if (val.length >= 1) pwBars[0].classList.add('is-weak');
      if (val.length >= 8) pwBars[1].classList.add('is-medium');
      if (val.length >= 10 && /[A-Z]/.test(val) && /[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) {
        pwBars[2].classList.add('is-strong');
      }
    });
  }

})();
