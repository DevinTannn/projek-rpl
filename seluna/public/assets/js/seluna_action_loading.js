/**
 * SELUNA — Action Loading & Anti-Spam Utility
 * =============================================
 * Drop this script into your app layout (e.g. app.blade.php, before </body>)
 * or include as a standalone asset: <script src="{{ asset('js/seluna_action_loading.js') }}"></script>
 *
 * Color palette: #243E36 (primary), #7CA982 (secondary), #C2A83E (accent), #F1F7ED (bg), #E0EEC6 (surface)
 */

(function () {
    'use strict';

    /* ─────────────────────────────────────────
       1. CONFIG
    ───────────────────────────────────────── */
    const CONFIG = {
        /* How long (ms) to wait before re-enabling a button if no page nav occurs */
        timeout: 12000,

        /* Spinner SVG injected into buttons */
        spinnerSVG: `<svg class="seluna-spinner" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" style="
                width:16px;height:16px;animation:seluna-spin 0.7s linear infinite;
                flex-shrink:0;display:inline-block;vertical-align:middle;">
            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83
                     M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
        </svg>`,

        /* Loading label overrides per action type  (data-action="…") */
        labels: {
            create:  'Menyimpan…',
            update:  'Memperbarui…',
            edit:    'Memperbarui…',
            delete:  'Menghapus…',
            view:    'Membuka…',
            search:  'Mencari…',
            upload:  'Mengunggah…',
            payment: 'Memproses…',
            default: 'Memuat…',
        },
    };

    /* ─────────────────────────────────────────
       2. GLOBAL STYLES (injected once)
    ───────────────────────────────────────── */
    function injectStyles() {
        if (document.getElementById('seluna-loading-styles')) return;

        const css = `
        @keyframes seluna-spin {
            to { transform: rotate(360deg); }
        }
        @keyframes seluna-fade-in {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes seluna-pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.55; }
        }

        /* ── Disabled / loading button ── */
        .seluna-loading {
            position: relative !important;
            pointer-events: none !important;
            cursor: not-allowed !important;
            opacity: 0.82 !important;
            animation: seluna-pulse 1.4s ease-in-out infinite !important;
        }

        /* Keep text hidden but keep button width stable */
        .seluna-loading .seluna-btn-original-text {
            opacity: 0;
        }

        /* Overlay that holds spinner + label */
        .seluna-loading-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border-radius: inherit;
            font-size: inherit;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* ── Full-page overlay (form submit) ── */
        #seluna-page-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(36, 62, 54, 0.35);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
            animation: seluna-fade-in 0.2s ease;
        }
        #seluna-page-overlay.active {
            display: flex;
        }
        .seluna-page-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            box-shadow: 0 20px 60px rgba(36,62,54,0.18);
            min-width: 200px;
            animation: seluna-fade-in 0.25s ease;
        }
        .seluna-page-ring {
            width: 52px; height: 52px;
            border-radius: 50%;
            border: 3px solid #E0EEC6;
            border-top-color: #7CA982;
            animation: seluna-spin 0.75s linear infinite;
        }
        .seluna-page-label {
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #243E36;
            letter-spacing: 0.2px;
        }

        /* ── Toast notification ── */
        #seluna-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .seluna-toast {
            background: #243E36;
            color: #F1F7ED;
            border-radius: 14px;
            padding: 12px 20px;
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 24px rgba(36,62,54,0.22);
            animation: seluna-fade-in 0.25s ease;
            pointer-events: auto;
            max-width: 320px;
        }
        .seluna-toast.success { border-left: 4px solid #7CA982; }
        .seluna-toast.warning { border-left: 4px solid #C2A83E; }
        .seluna-toast.error   { border-left: 4px solid #e74c3c; }

        /* ── Delete confirm dialog ── */
        #seluna-confirm-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99998;
            background: rgba(36, 62, 54, 0.4);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }
        #seluna-confirm-overlay.active { display: flex; }
        .seluna-confirm-card {
            background: #fff;
            border-radius: 24px;
            padding: 36px 40px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(36,62,54,0.18);
            animation: seluna-fade-in 0.22s ease;
        }
        .seluna-confirm-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: #fff3cd;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px;
        }
        .seluna-confirm-title {
            font-family: 'Outfit','Segoe UI',sans-serif;
            font-size: 18px; font-weight: 700;
            color: #243E36; margin-bottom: 8px;
        }
        .seluna-confirm-body {
            font-size: 14px; color: #666; margin-bottom: 28px; line-height: 1.5;
        }
        .seluna-confirm-actions {
            display: flex; gap: 12px; justify-content: center;
        }
        .seluna-confirm-actions button {
            padding: 10px 28px; border-radius: 50px;
            font-weight: 700; font-size: 14px; border: none; cursor: pointer;
            transition: all 0.2s;
        }
        .seluna-btn-cancel {
            background: #f0f0f0; color: #555;
        }
        .seluna-btn-cancel:hover { background: #e0e0e0; }
        .seluna-btn-danger {
            background: #e74c3c; color: #fff;
        }
        .seluna-btn-danger:hover { background: #c0392b; transform: translateY(-1px); }
        `;

        const style = document.createElement('style');
        style.id = 'seluna-loading-styles';
        style.textContent = css;
        document.head.appendChild(style);
    }

    /* ─────────────────────────────────────────
       3. PAGE OVERLAY (full-screen spinner)
    ───────────────────────────────────────── */
    let pageOverlay = null;

    function getPageOverlay() {
        if (!pageOverlay) {
            pageOverlay = document.createElement('div');
            pageOverlay.id = 'seluna-page-overlay';
            pageOverlay.innerHTML = `
                <div class="seluna-page-card">
                    <div class="seluna-page-ring"></div>
                    <div class="seluna-page-label" id="seluna-page-label">Memuat…</div>
                </div>`;
            document.body.appendChild(pageOverlay);
        }
        return pageOverlay;
    }

    function showPageOverlay(label) {
        const overlay = getPageOverlay();
        const lbl = overlay.querySelector('#seluna-page-label');
        if (lbl) lbl.textContent = label || CONFIG.labels.default;
        overlay.classList.add('active');
    }

    function hidePageOverlay() {
        if (pageOverlay) pageOverlay.classList.remove('active');
    }

    /* ─────────────────────────────────────────
       4. BUTTON STATE HELPERS
    ───────────────────────────────────────── */
    function getLabelForAction(action) {
        return CONFIG.labels[action] || CONFIG.labels.default;
    }

    /**
     * Put a button into loading state.
     * @param {HTMLElement} btn
     * @param {string} [overrideLabel]
     */
    function setButtonLoading(btn, overrideLabel) {
        if (btn.dataset.selunaLoading === 'true') return; // already loading

        const action  = btn.dataset.action || 'default';
        const label   = overrideLabel || getLabelForAction(action);

        // Save original content
        btn.dataset.selunaLoading       = 'true';
        btn.dataset.selunaOriginalHtml  = btn.innerHTML;
        btn.dataset.selunaOriginalWidth = btn.offsetWidth + 'px';

        // Lock width so button doesn't shrink
        btn.style.minWidth = btn.dataset.selunaOriginalWidth;

        // Wrap original text so we can hide it while keeping width
        btn.innerHTML = `
            <span class="seluna-btn-original-text" aria-hidden="true" style="visibility:hidden;">
                ${btn.dataset.selunaOriginalHtml}
            </span>
            <span class="seluna-loading-overlay">
                ${CONFIG.spinnerSVG}
                <span>${label}</span>
            </span>`;

        btn.classList.add('seluna-loading');
        btn.setAttribute('disabled', 'disabled');
        btn.setAttribute('aria-busy', 'true');

        // Safety timeout — re-enable in case of network error
        btn._selunaTimer = setTimeout(() => resetButton(btn), CONFIG.timeout);
    }

    function resetButton(btn) {
        if (!btn || btn.dataset.selunaLoading !== 'true') return;
        clearTimeout(btn._selunaTimer);
        btn.innerHTML = btn.dataset.selunaOriginalHtml || btn.innerHTML;
        btn.classList.remove('seluna-loading');
        btn.removeAttribute('disabled');
        btn.removeAttribute('aria-busy');
        btn.style.minWidth = '';
        delete btn.dataset.selunaLoading;
        delete btn.dataset.selunaOriginalHtml;
    }

    /* ─────────────────────────────────────────
       5. FORM SUBMIT — show page overlay
    ───────────────────────────────────────── */
    function handleFormSubmit(e) {
        const form   = e.currentTarget;
        const submit = form.querySelector('[type="submit"]');
        const action = submit ? (submit.dataset.action || 'default') : 'default';
        const label  = getLabelForAction(action);

        if (submit) setButtonLoading(submit, label);
        showPageOverlay(label);

        // Re-enable if browser blocks submission (e.g. validation)
        requestAnimationFrame(() => {
            if (document.activeElement === submit) return; // still focused, submitted
            hidePageOverlay();
            if (submit) resetButton(submit);
        });
    }

    /* ─────────────────────────────────────────
       6. DELETE CONFIRM DIALOG
    ───────────────────────────────────────── */
    let confirmOverlay = null;

    function getConfirmOverlay() {
        if (!confirmOverlay) {
            confirmOverlay = document.createElement('div');
            confirmOverlay.id = 'seluna-confirm-overlay';
            confirmOverlay.innerHTML = `
                <div class="seluna-confirm-card">
                    <div class="seluna-confirm-icon">🗑️</div>
                    <div class="seluna-confirm-title" id="seluna-confirm-title">Hapus item ini?</div>
                    <div class="seluna-confirm-body" id="seluna-confirm-body">
                        Tindakan ini tidak bisa dibatalkan. Yakin ingin melanjutkan?
                    </div>
                    <div class="seluna-confirm-actions">
                        <button class="seluna-btn-cancel" id="seluna-confirm-cancel">Batal</button>
                        <button class="seluna-btn-danger" id="seluna-confirm-ok">Ya, Hapus</button>
                    </div>
                </div>`;
            document.body.appendChild(confirmOverlay);

            confirmOverlay.querySelector('#seluna-confirm-cancel').addEventListener('click', () => {
                confirmOverlay.classList.remove('active');
            });
            confirmOverlay.addEventListener('click', (e) => {
                if (e.target === confirmOverlay) confirmOverlay.classList.remove('active');
            });
        }
        return confirmOverlay;
    }

    /**
     * Show a confirmation dialog before executing a delete or other action.
     * @param {object} opts
     * @param {string}   opts.title   - Dialog heading
     * @param {string}   opts.body    - Dialog subtext
     * @param {string}   opts.icon    - Dialog icon/emoji
     * @param {string}   opts.buttonText - Dialog confirmation button text
     * @param {Function} opts.onConfirm - Called when user confirms
     */
    function showDeleteConfirm({ title, body, icon, buttonText, onConfirm } = {}) {
        const overlay = getConfirmOverlay();
        if (title) overlay.querySelector('#seluna-confirm-title').textContent = title;
        if (body)  overlay.querySelector('#seluna-confirm-body').textContent  = body;
        
        const iconEl = overlay.querySelector('.seluna-confirm-icon');
        if (iconEl) iconEl.textContent = icon || '🗑️';

        const okBtn = overlay.querySelector('#seluna-confirm-ok');
        if (okBtn) {
            okBtn.textContent = buttonText || 'Ya, Hapus';
            // Custom button styling
            if (buttonText && (buttonText.toLowerCase().includes('logout') || buttonText.toLowerCase().includes('keluar'))) {
                okBtn.style.backgroundColor = '#e74c3c';
            } else {
                okBtn.style.backgroundColor = '#e74c3c';
            }

            // Remove old listener
            const fresh = okBtn.cloneNode(true);
            okBtn.parentNode.replaceChild(fresh, okBtn);
            fresh.addEventListener('click', () => {
                overlay.classList.remove('active');
                if (typeof onConfirm === 'function') onConfirm();
            });
        }

        overlay.classList.add('active');
    }

    /* ─────────────────────────────────────────
       7. TOAST
    ───────────────────────────────────────── */
    let toastContainer = null;

    function getToastContainer() {
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'seluna-toast-container';
            document.body.appendChild(toastContainer);
        }
        return toastContainer;
    }

    /**
     * Show a toast message.
     * @param {string} message
     * @param {'success'|'warning'|'error'} [type='success']
     * @param {number} [duration=3500]
     */
    function showToast(message, type = 'success', duration = 3500) {
        const icons = { success: '✓', warning: '⚠', error: '✕' };
        const container = getToastContainer();
        const toast = document.createElement('div');
        toast.className = `seluna-toast ${type}`;
        toast.innerHTML = `<span>${icons[type] || '✓'}</span><span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s, transform 0.3s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 320);
        }, duration);
    }

    /* ─────────────────────────────────────────
       8. AUTO-BIND
    ───────────────────────────────────────── */

    /**
     * Binds loading behaviour to all matching elements.
     * Called on DOMContentLoaded and after dynamic content is added
     * (call window.Seluna.bind(rootEl) after AJAX inserts).
     */
    function bind(root = document) {
        /* 8a. Forms — show page overlay on submit */
        root.querySelectorAll('form[data-seluna]').forEach(form => {
            if (form._selunabound) return;
            form._selunabound = true;
            form.addEventListener('submit', handleFormSubmit);
        });

        /* 8a-2. Forms with confirmations (e.g. Logout, Delete) */
        root.querySelectorAll('form[data-confirm]').forEach(form => {
            if (form._confirmbound) return;
            form._confirmbound = true;
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                showDeleteConfirm({
                    title: this.dataset.confirmTitle || 'Konfirmasi',
                    body: this.dataset.confirmBody || 'Apakah Anda yakin?',
                    icon: this.dataset.confirmIcon || '🚪',
                    buttonText: this.dataset.confirmOk || 'Ya',
                    onConfirm: () => {
                        form.submit();
                    }
                });
            });
        });

        /* 8b. Standalone action buttons (non-form) */
        root.querySelectorAll('[data-seluna-btn]').forEach(btn => {
            if (btn._selunabound) return;
            btn._selunabound = true;
            btn.addEventListener('click', function (e) {
                const action = this.dataset.action || 'default';

                /* Delete buttons: show confirm first */
                if (action === 'delete') {
                    e.preventDefault();
                    const href = this.dataset.href || this.getAttribute('href') || '#';
                    showDeleteConfirm({
                        title: this.dataset.confirmTitle || 'Hapus item ini?',
                        body:  this.dataset.confirmBody  || 'Tindakan ini tidak bisa dibatalkan.',
                        onConfirm: () => {
                            setButtonLoading(this);
                            showPageOverlay(getLabelForAction('delete'));
                            /* Navigate or submit a hidden form */
                            const formId = this.dataset.form;
                            if (formId) {
                                document.getElementById(formId)?.submit();
                            } else if (href && href !== '#') {
                                // Submit as DELETE so Laravel routes match correctly
                                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
                                const f = document.createElement('form');
                                f.method = 'POST';
                                f.action = href;
                                f.style.display = 'none';
                                f.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">
                                               <input type="hidden" name="_method" value="DELETE">`;
                                document.body.appendChild(f);
                                f.submit();
                            }
                        },
                    });
                    return;
                }

                /* All other actions */
                setButtonLoading(this);
                const href = this.dataset.href || this.getAttribute('href');
                if (href && href !== '#') {
                    showPageOverlay(getLabelForAction(action));
                    setTimeout(() => { window.location.href = href; }, 40);
                }
            });
        });
    }

    /* ─────────────────────────────────────────
       9. INIT
    ───────────────────────────────────────── */
    function init() {
        injectStyles();
        bind();

        /* Hide page overlay when user navigates back */
        window.addEventListener('pageshow', hidePageOverlay);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /* ─────────────────────────────────────────
       10. PUBLIC API
    ───────────────────────────────────────── */
    window.Seluna = {
        /** Re-bind after dynamic DOM inserts */
        bind,
        /** Manually start loading on a button */
        setButtonLoading,
        /** Reset a button */
        resetButton,
        /** Show the full-page overlay */
        showPageOverlay,
        /** Hide the full-page overlay */
        hidePageOverlay,
        /** Show a toast */
        toast: showToast,
        /** Show a delete confirm dialog */
        confirmDelete: showDeleteConfirm,
    };
})();
