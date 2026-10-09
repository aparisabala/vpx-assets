/*
 * px shell behaviour shared by every px admin layout:
 * sidebar collapse (desktop) / off canvas (mobile), sub menus, active link, light/dark mode.
 */
(function () {
    'use strict';

    var root = document.documentElement;
    var desktop = window.matchMedia('(min-width: 992px)');

    function store(key, value) {
        try { value === null ? localStorage.removeItem(key) : localStorage.setItem(key, value); } catch (e) {}
    }

    function toggleSidebar() {
        if (desktop.matches) {
            var collapsed = root.classList.toggle('px-collapsed');
            store('px.sidebar', collapsed ? 'collapsed' : 'expanded');
        } else {
            root.classList.toggle('px-sidebar-open');
        }
    }

    function toggleMode() {
        var mode = root.getAttribute('data-px-mode') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-px-mode', mode);
        root.setAttribute('data-bs-theme', mode);
        store('px.mode', mode);
    }

    function toggleSubmenu(link) {
        var item = link.closest('.px-menu-item');
        if (!item) { return; }
        var open = !item.classList.contains('open');
        // accordion: close the siblings
        Array.prototype.forEach.call(item.parentNode.children, function (sibling) {
            if (sibling !== item) { sibling.classList.remove('open'); }
        });
        item.classList.toggle('open', open);
    }

    // highlight the link that best matches the current url and open its group
    function markActive() {
        var here = window.location.href.split(/[?#]/)[0].replace(/\/$/, '');
        var best = null, bestLength = 0;
        document.querySelectorAll('.px-menu-link[href], .px-submenu-link[href]').forEach(function (link) {
            var href = link.href.split(/[?#]/)[0].replace(/\/$/, '');
            if (!href || href.indexOf('javascript:') === 0) { return; }
            if ((here === href || here.indexOf(href + '/') === 0) && href.length > bestLength) {
                best = link;
                bestLength = href.length;
            }
        });
        if (!best) { return; }
        best.classList.add('active');
        var item = best.closest('.px-submenu') && best.closest('.px-menu-item');
        if (item) { item.classList.add('open'); }
    }

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-px-toggle], [data-px-submenu]');
        if (!trigger) { return; }
        if (trigger.hasAttribute('data-px-submenu')) {
            event.preventDefault();
            toggleSubmenu(trigger);
            return;
        }
        var action = trigger.getAttribute('data-px-toggle');
        if (action === 'sidebar') { toggleSidebar(); }
        if (action === 'mode') { toggleMode(); }
    });

    // close the mobile menu when switching to desktop
    desktop.addEventListener('change', function () { root.classList.remove('px-sidebar-open'); });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', markActive);
    } else {
        markActive();
    }
})();
