/* ============================================================
   PNP SELECT — branded dropdown "slate" for every <select>.

   The native <select> stays in the form (hidden, still holds the
   value, still validated, still receives .val() / change), and a
   button + panel render on top of it:
     • panel shows ~8 rows then scrolls (never the whole list);
     • search box appears when there are more than 8 options;
     • keyboard: Enter/Space/ArrowDown open, arrows move, Enter
       picks, Esc closes, typing filters;
     • picking an option sets the select and dispatches a native
       `change` (bubbles) → jQuery handlers AND inline onchange="…"
       run exactly as before;
     • programmatic $(sel).val(x) re-syncs the label (jQuery.val is
       wrapped to emit `pnp:sync`); option lists rebuilt by JS are
       picked up by a MutationObserver;
     • the panel is appended to <body> with fixed positioning so it
       is never clipped by modal bodies or cards.

   Skipped: [multiple], [size], .dataTables_length select,
   #ui-datepicker-div select, [data-no-pnp-select], .no-pnp-select *.
   Styling: theme.css (.pnp-select, .pnp-select__panel).
   ============================================================ */
(function ($) {
    'use strict';
    if (!$) { return; }

    var SKIP = 'select[multiple], select[size], .dataTables_length select, #ui-datepicker-div select, select[data-no-pnp-select], .no-pnp-select select, select.pnp-select__native';
    var SEARCH_AFTER = 8;
    var open = null; // { wrap, panel, select }

    function optionLabel(opt) { return (opt.textContent || '').replace(/\s+/g, ' ').trim(); }

    function isPlaceholder(opt) {
        return !opt || opt.value === '' || /^(select|choose|--|—)/i.test(optionLabel(opt));
    }

    function syncLabel(wrap) {
        var select = wrap.select, btn = wrap.btn;
        var opt = select.options[select.selectedIndex];
        var label = opt ? optionLabel(opt) : '';
        btn.label.textContent = label || (select.getAttribute('data-placeholder') || 'Select…');
        btn.el.classList.toggle('is-placeholder', isPlaceholder(opt));
        btn.el.disabled = select.disabled;
        btn.el.classList.toggle('is-invalid', select.classList.contains('is-invalid'));
        btn.el.classList.toggle('form-control-sm', select.classList.contains('form-control-sm'));
    }

    function buildList(wrap) {
        var select = wrap.select, list = wrap.list;
        list.innerHTML = '';
        var groups = select.querySelectorAll('optgroup');
        var container = select;
        var items = [];
        function add(opt, groupLabel) {
            if (opt.hidden) { return; }
            var li = document.createElement('div');
            li.className = 'pnp-select__option' + (opt.disabled ? ' is-disabled' : '') + (opt.selected ? ' is-selected' : '');
            li.setAttribute('role', 'option');
            li.setAttribute('data-index', opt.index);
            li.textContent = optionLabel(opt) || ' ';
            if (opt.selected) { li.setAttribute('aria-selected', 'true'); }
            list.appendChild(li);
            items.push(li);
        }
        if (groups.length) {
            Array.prototype.forEach.call(select.children, function (child) {
                if (child.tagName === 'OPTGROUP') {
                    var h = document.createElement('div');
                    h.className = 'pnp-select__group';
                    h.textContent = child.label;
                    list.appendChild(h);
                    Array.prototype.forEach.call(child.children, function (o) { add(o); });
                } else if (child.tagName === 'OPTION') { add(child); }
            });
        } else {
            Array.prototype.forEach.call(select.options, function (o) { add(o); });
        }
        wrap.items = items;
        wrap.search.parentNode.style.display = select.options.length > SEARCH_AFTER ? '' : 'none';
    }

    function filterList(wrap, q) {
        q = (q || '').trim().toLowerCase();
        var any = false;
        wrap.items.forEach(function (li) {
            var hit = !q || li.textContent.toLowerCase().indexOf(q) !== -1;
            li.style.display = hit ? '' : 'none';
            any = any || hit;
        });
        wrap.empty.style.display = any ? 'none' : '';
    }

    function position(wrap) {
        var r = wrap.btn.el.getBoundingClientRect();
        var panel = wrap.panel;
        var vh = window.innerHeight, vw = window.innerWidth;
        var width = Math.max(r.width, 200);
        var left = Math.min(r.left, vw - width - 8);
        panel.style.width = width + 'px';
        panel.style.left = Math.max(8, left) + 'px';
        panel.style.maxHeight = '';
        var ph = panel.offsetHeight;
        var below = vh - r.bottom - 8, above = r.top - 8;
        if (ph <= below || below >= above) {
            panel.style.top = (r.bottom + 6) + 'px';
            panel.style.bottom = '';
            panel.classList.remove('is-up');
            if (ph > below) { panel.style.maxHeight = Math.max(160, below - 6) + 'px'; }
        } else {
            panel.style.top = '';
            panel.style.bottom = (vh - r.top + 6) + 'px';
            panel.classList.add('is-up');
            if (ph > above) { panel.style.maxHeight = Math.max(160, above - 6) + 'px'; }
        }
    }

    function highlight(wrap, li) {
        wrap.items.forEach(function (x) { x.classList.remove('is-active'); });
        if (li) {
            li.classList.add('is-active');
            var top = li.offsetTop, bottom = top + li.offsetHeight;
            var l = wrap.list;
            if (top < l.scrollTop) { l.scrollTop = top; }
            else if (bottom > l.scrollTop + l.clientHeight) { l.scrollTop = bottom - l.clientHeight; }
        }
    }

    function visibleItems(wrap) { return wrap.items.filter(function (li) { return li.style.display !== 'none' && !li.classList.contains('is-disabled'); }); }

    function openPanel(wrap) {
        if (open) { closePanel(); }
        if (wrap.select.disabled) { return; }
        buildList(wrap);
        wrap.search.value = '';
        filterList(wrap, '');
        document.body.appendChild(wrap.panel);
        wrap.panel.classList.add('is-open');
        wrap.btn.el.setAttribute('aria-expanded', 'true');
        wrap.btn.el.classList.add('is-open');
        position(wrap);
        var sel = wrap.items.filter(function (li) { return li.classList.contains('is-selected'); })[0];
        highlight(wrap, sel || visibleItems(wrap)[0]);
        open = wrap;
        if (wrap.search.parentNode.style.display !== 'none') { setTimeout(function () { wrap.search.focus(); }, 0); }
        else { wrap.list.focus(); }
    }

    function closePanel(refocus) {
        if (!open) { return; }
        var wrap = open;
        open = null;
        wrap.panel.classList.remove('is-open');
        if (wrap.panel.parentNode) { wrap.panel.parentNode.removeChild(wrap.panel); }
        wrap.btn.el.setAttribute('aria-expanded', 'false');
        wrap.btn.el.classList.remove('is-open');
        if (refocus) { wrap.btn.el.focus(); }
    }

    function choose(wrap, li) {
        if (!li || li.classList.contains('is-disabled')) { return; }
        var idx = parseInt(li.getAttribute('data-index'), 10);
        var select = wrap.select;
        var changed = select.selectedIndex !== idx;
        select.selectedIndex = idx;
        syncLabel(wrap);
        closePanel(true);
        if (changed) {
            select.dispatchEvent(new Event('input', { bubbles: true }));
            select.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    function enhance(select) {
        if (select.__pnp || $(select).is(SKIP)) { return; }
        select.__pnp = true;

        var wrap = { select: select };
        var div = document.createElement('div');
        div.className = 'pnp-select';
        // carry spacing/grid utility classes to the wrapper
        var carried = (select.className.match(/\b(m[tblrxy]?-\d|col(-\w+)?-\d+|w-\d+)\b/g) || []);
        carried.forEach(function (c) { div.classList.add(c); select.classList.remove(c); });
        select.classList.add('pnp-select__native');
        select.setAttribute('tabindex', '-1');
        select.setAttribute('aria-hidden', 'true');

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pnp-select__btn form-control';
        btn.setAttribute('aria-haspopup', 'listbox');
        btn.setAttribute('aria-expanded', 'false');
        var label = document.createElement('span'); label.className = 'pnp-select__label';
        var caret = document.createElement('span'); caret.className = 'pnp-select__caret';
        btn.appendChild(label); btn.appendChild(caret);
        wrap.btn = { el: btn, label: label };

        var panel = document.createElement('div');
        panel.className = 'pnp-select__panel';
        panel.setAttribute('role', 'dialog');
        var searchWrap = document.createElement('div'); searchWrap.className = 'pnp-select__search';
        var search = document.createElement('input'); search.type = 'search'; search.placeholder = 'Search…'; search.setAttribute('autocomplete', 'off');
        searchWrap.appendChild(search);
        var list = document.createElement('div'); list.className = 'pnp-select__list'; list.setAttribute('role', 'listbox'); list.tabIndex = -1;
        var empty = document.createElement('div'); empty.className = 'pnp-select__empty'; empty.textContent = 'No matches'; empty.style.display = 'none';
        panel.appendChild(searchWrap); panel.appendChild(list); panel.appendChild(empty);
        wrap.panel = panel; wrap.search = search; wrap.list = list; wrap.empty = empty; wrap.items = [];

        select.parentNode.insertBefore(div, select);
        div.appendChild(select);
        div.appendChild(btn);

        btn.addEventListener('click', function () { open === wrap ? closePanel(true) : openPanel(wrap); });
        btn.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openPanel(wrap); }
        });
        function keyNav(e) {
            var vis = visibleItems(wrap);
            var cur = vis.indexOf(wrap.items.filter(function (li) { return li.classList.contains('is-active'); })[0]);
            if (e.key === 'ArrowDown') { e.preventDefault(); highlight(wrap, vis[Math.min(vis.length - 1, cur + 1)]); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); highlight(wrap, vis[Math.max(0, cur - 1)]); }
            else if (e.key === 'Enter') { e.preventDefault(); choose(wrap, vis[cur] || vis[0]); }
            else if (e.key === 'Escape') { e.preventDefault(); closePanel(true); }
            else if (e.key === 'Tab') { closePanel(false); }
        }
        search.addEventListener('input', function () { filterList(wrap, search.value); highlight(wrap, visibleItems(wrap)[0]); position(wrap); });
        search.addEventListener('keydown', keyNav);
        list.addEventListener('keydown', function (e) {
            if (e.key.length === 1 && !e.ctrlKey && !e.metaKey) {
                // type-ahead when there is no search box
                var q = e.key.toLowerCase();
                var hit = visibleItems(wrap).filter(function (li) { return li.textContent.toLowerCase().indexOf(q) === 0; })[0];
                if (hit) { highlight(wrap, hit); }
            } else { keyNav(e); }
        });
        list.addEventListener('mousemove', function (e) {
            var li = e.target.closest('.pnp-select__option');
            if (li && !li.classList.contains('is-active')) { highlight(wrap, li); }
        });
        list.addEventListener('mousedown', function (e) { e.preventDefault(); });
        list.addEventListener('click', function (e) {
            var li = e.target.closest('.pnp-select__option');
            if (li) { choose(wrap, li); }
        });

        // keep the label in step with the real select
        select.addEventListener('change', function () { syncLabel(wrap); });
        $(select).on('pnp:sync', function () { syncLabel(wrap); });
        if (window.MutationObserver) {
            new MutationObserver(function () {
                syncLabel(wrap);
                if (open === wrap) { buildList(wrap); filterList(wrap, wrap.search.value); position(wrap); }
            }).observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'class', 'selected', 'value'] });
        }
        syncLabel(wrap);
    }

    function scan(root) {
        var scope = root && root.querySelectorAll ? root : document;
        var nodes = scope.querySelectorAll('select');
        for (var i = 0; i < nodes.length; i++) { enhance(nodes[i]); }
    }

    // $(sel).val(x) → refresh the visible label
    var origVal = $.fn.val;
    $.fn.val = function () {
        var r = origVal.apply(this, arguments);
        if (arguments.length) { this.filter('select.pnp-select__native').trigger('pnp:sync'); }
        return r;
    };

    $(function () {
        scan(document);
        if (window.MutationObserver) {
            new MutationObserver(function (muts) {
                for (var i = 0; i < muts.length; i++) {
                    var added = muts[i].addedNodes;
                    for (var j = 0; j < added.length; j++) {
                        var n = added[j];
                        if (n.nodeType !== 1) { continue; }
                        if (n.tagName === 'SELECT') { enhance(n); } else { scan(n); }
                    }
                }
            }).observe(document.body, { childList: true, subtree: true });
        }
        document.addEventListener('mousedown', function (e) {
            if (open && !open.panel.contains(e.target) && !open.btn.el.contains(e.target)) { closePanel(false); }
        });
        window.addEventListener('resize', function () { if (open) { position(open); } });
        window.addEventListener('scroll', function (e) {
            if (open && !open.panel.contains(e.target)) { position(open); }
        }, true);
        $(document).on('hide.bs.modal', function () { closePanel(false); });
    });
})(window.jQuery);
