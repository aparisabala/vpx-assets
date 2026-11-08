/*
 * The browser's licence check of a product sold on a marketplace.
 *
 * It does nothing unless the page has <meta name="px-license" content='{"server":"...","product":"...","page":"..."}'>.
 * Only a marketplace install's panel prints that tag, so hosted services and every other product are not affected.
 *
 * It asks the licence server about this domain, at most every 6 hours per browser. Only a clear "invalid" counts: no
 * internet or a server problem shows nothing. An unlicensed domain gets a popup and its forms are blocked. A refunded or
 * suspended licence only gets the popup, because the server side already stops its changes.
 */

const STORAGE_KEY = 'px_license_ok';
const REMEMBER_MS = 6 * 60 * 60 * 1000;
const ASK_TIMEOUT_MS = 8000;

export class PxLicense {
    constructor(win = window) {
        this.win = win;
        this.invalid = false;
        this.code = null;
        this.options = null;
    }

    /** Runs the check once the page is ready. */
    static boot(win = window) {
        const run = () => { new PxLicense(win).start(); };
        if (win.document.readyState === 'loading') {
            win.document.addEventListener('DOMContentLoaded', run);
        } else {
            run();
        }
    }

    readOptions() {
        const tag = this.win.document.querySelector('meta[name="px-license"]');
        if (!tag) {
            return null;
        }
        try {
            const options = JSON.parse(tag.getAttribute('content'));
            return options?.server && options?.product ? options : null;
        } catch (error) {
            return null;
        }
    }

    start() {
        this.options = this.readOptions();
        if (!this.options) {
            return Promise.resolve();
        }
        // capture: this runs before the page's own submit handlers
        this.win.document.addEventListener('submit', (event) => this.onSubmit(event), true);
        return this.recentlyValid() ? Promise.resolve() : this.ask();
    }

    async ask() {
        const { server, product } = this.options;
        const abort = new this.win.AbortController();
        const timer = this.win.setTimeout(() => abort.abort(), ASK_TIMEOUT_MS);
        try {
            const response = await this.win.fetch(server.replace(/\/+$/, '') + '/api/license/v1/domain-check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({ product, domain: this.win.location.hostname }),
                signal: abort.signal,
            });
            const answer = await response.json();
            if (answer?.valid === false) {
                this.markInvalid(answer.code);
            } else if (answer?.valid === true) {
                this.remember();
            }
        } catch (error) {
            // no answer is not a "no": the page carries on
        } finally {
            this.win.clearTimeout(timer);
        }
    }

    onSubmit(event) {
        // signing out always works
        if (/logout/i.test(event.target?.getAttribute?.('action') ?? '')) {
            return;
        }
        if (this.refuse()) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }

    refuse() {
        if (this.invalid) {
            this.showPopup();
        }
        return this.invalid;
    }

    markInvalid(code) {
        this.code = code ?? 'unlicensed';
        this.invalid = this.code !== 'refunded' && this.code !== 'suspended';
        this.forget();
        this.showPopup();
    }

    recentlyValid() {
        try {
            return Number(this.win.localStorage.getItem(STORAGE_KEY)) > Date.now();
        } catch (error) {
            return false;
        }
    }

    remember() {
        try {
            this.win.localStorage.setItem(STORAGE_KEY, String(Date.now() + REMEMBER_MS));
        } catch (error) {
            // private windows can refuse storage: it only means asking again
        }
    }

    forget() {
        try {
            this.win.localStorage.removeItem(STORAGE_KEY);
        } catch (error) {
            // as above
        }
    }

    showPopup() {
        const doc = this.win.document;
        if (doc.getElementById('px-license-popup')) {
            return;
        }
        const overlay = doc.createElement('div');
        overlay.id = 'px-license-popup';
        overlay.setAttribute('role', 'alertdialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-labelledby', 'px-license-title');
        overlay.style.cssText = 'position:fixed;inset:0;z-index:2147483000;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(0,0,0,.6);font-family:Segoe UI,Tahoma,Arial,sans-serif';

        const box = doc.createElement('div');
        box.style.cssText = 'background:#fff;color:#1f2937;max-width:440px;width:100%;padding:28px;border-radius:12px;border-top:5px solid #dc2626;text-align:center';

        const title = doc.createElement('h2');
        title.id = 'px-license-title';
        title.style.cssText = 'margin:0 0 12px;font-size:22px;color:#1f2937';
        title.textContent = 'Licence invalid';

        const text = doc.createElement('p');
        text.style.cssText = 'margin:0 0 20px;font-size:15px;line-height:1.6;color:#4b5563';
        text.textContent = (this.invalid
            ? 'This copy has no valid licence for ' + this.win.location.hostname + '.'
            : 'This licence is ' + this.code + '.') + ' You can view and export your records, but not add or change them.';

        const actions = doc.createElement('div');
        actions.style.cssText = 'display:flex;gap:8px;justify-content:center;flex-wrap:wrap';

        const link = doc.createElement('a');
        link.href = this.options?.page || '/license';
        link.textContent = 'Licence page';
        link.style.cssText = 'padding:10px 16px;border-radius:6px;background:#2563eb;color:#fff;text-decoration:none;font-size:15px';

        const close = doc.createElement('button');
        close.type = 'button';
        close.textContent = 'View records';
        close.style.cssText = 'padding:10px 16px;border-radius:6px;background:#fff;color:#1f2937;border:1px solid #9ca3af;font-size:15px;cursor:pointer';
        close.addEventListener('click', () => overlay.remove());

        actions.append(link, close);
        box.append(title, text, actions);
        overlay.append(box);
        doc.body.append(overlay);
        close.focus();
    }
}
