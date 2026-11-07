/**
 * Licence check of a product sold on a marketplace. It runs only when the page holds <meta name="px-license">: hosted
 * services and other products have no such tag, so for them px.js behaves as it always did.
 *
 * Only a clear "invalid" from the licence server counts. No internet, a server that is down or an odd reply changes
 * nothing, so a hospital is never stopped by a problem that is not its licence.
 *
 * A copy with no licence: a popup on every page, and forms are refused (lists and pop-ups keep loading). A refunded or
 * suspended licence: the popup only. It was sold honestly, so the server decides what it may still do, and filtering a
 * report must keep working.
 */
const CACHE_KEY = 'px_license_ok';
const CACHE_MS = 6 * 60 * 60 * 1000;
const TIMEOUT_MS = 8000;

export class PxLicense {
    constructor(win = window) {
        this.win = win;
        this.invalid = false;
        this.code = null;
        this.options = null;
    }

    /** the settings in the page's tag, or null when this page has no licence check */
    readOptions() {
        const tag = this.win.document.querySelector('meta[name="px-license"]');
        if (!tag) {
            return null;
        }
        try {
            const options = JSON.parse(tag.getAttribute('content'));
            return options?.server && options?.product ? options : null;
        } catch (e) {
            return null;
        }
    }

    start() {
        this.options = this.readOptions();
        if (!this.options) {
            return Promise.resolve();
        }
        this.win.document.addEventListener('submit', (event) => this.onSubmit(event), true);
        return this.recentlyValid() ? Promise.resolve() : this.ask();
    }

    async ask() {
        const { server, product } = this.options;
        const controller = new this.win.AbortController();
        const timer = this.win.setTimeout(() => controller.abort(), TIMEOUT_MS);
        try {
            const response = await this.win.fetch(server.replace(/\/+$/, '') + '/api/license/v1/domain-check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({ product, domain: this.win.location.hostname }),
                signal: controller.signal,
            });
            const answer = await response.json();
            if (answer?.valid === false) {
                this.markInvalid(answer.code);
            } else if (answer?.valid === true) {
                this.remember();
            }
        } catch (e) {
            // no answer is not an invalid licence
        } finally {
            this.win.clearTimeout(timer);
        }
    }

    /** before any page script sees the form. Signing out always works. */
    onSubmit(event) {
        if (/logout/i.test(event.target?.getAttribute?.('action') ?? '')) {
            return;
        }
        if (this.refuse()) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }

    /** true while forms are refused; the popup says why */
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
            return Number(this.win.localStorage.getItem(CACHE_KEY)) > Date.now();
        } catch (e) {
            return false;
        }
    }

    remember() {
        try {
            this.win.localStorage.setItem(CACHE_KEY, String(Date.now() + CACHE_MS));
        } catch (e) {
            // private window: it simply asks again next time
        }
    }

    forget() {
        try {
            this.win.localStorage.removeItem(CACHE_KEY);
        } catch (e) {
            // nothing was kept
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
