/* ============================================================
   PNP TABLE MANAGEMENT STANDARD — applied to every DataTable.
   Show 5/10/20/30/50/100 entries · live search · click-to-sort
   headers with visible sort buttons · "Showing x to y of z entries"
   · Previous / 1 2 3 / Next. Default 10 rows per page.

   Loaded in backend/master/template.blade.php AFTER the Bootlab
   bundles (they expose their own jQuery + DataTables, which is the
   instance the page scripts use) and BEFORE @yield('scripts'), so
   page-level DataTable({...}) calls inherit these defaults and may
   still override any of them.
   Columns headed "#" / "Action" / "Actions" are never sortable.
   ============================================================ */
(function ($) {
    'use strict';
    if (!$ || !$.fn || !$.fn.dataTable) { return; }

    var NO_SORT = /^(#|no\.?|action|actions)$/i;
    var SIZES = [5, 10, 20, 30, 50, 100];

    // DataTables' defaults object is keyed with its internal (Hungarian)
    // names; camelCase is only translated for per-call options.
    $.extend(true, $.fn.dataTable.defaults, {
        iDisplayLength: 10,
        aLengthMenu: [SIZES, SIZES],
        sPaginationType: 'simple_numbers',
        bAutoWidth: false,
        oLanguage: {
            sSearch: 'Search',
            sSearchPlaceholder: 'Search…',
            sLengthMenu: 'Show _MENU_ entries',
            sInfo: 'Showing _START_ to _END_ of _TOTAL_ entries',
            sInfoEmpty: 'Showing 0 to 0 of 0 entries',
            sInfoFiltered: '(filtered from _MAX_ total entries)',
            sZeroRecords: 'No matching records found',
            sEmptyTable: 'No data available in table',
            oPaginate: { sPrevious: 'Previous', sNext: 'Next' }
        }
    });
    // Pages that pass their own lengthMenu keep it; those that don't get the standard sizes.
    $.fn.dataTable.defaults.aLengthMenu = [SIZES.slice(), SIZES.slice()];

    // Before a table initialises: "#" and "Action" columns are not sortable.
    $(document).on('preInit.dt', function (e, settings) {
        if (!settings || !settings.aoColumns) { return; }
        $.each(settings.aoColumns, function (i, col) {
            var th = col.nTh;
            var label = th ? $(th).text().replace(/\s+/g, ' ').trim() : '';
            if (NO_SORT.test(label)) {
                col.bSortable = false;
                $(th).removeClass('sorting sorting_asc sorting_desc').addClass('sorting_disabled');
            }
        });
    });

    // After init: drop the sort listener on the disabled headers and tag the wrapper.
    $(document).on('init.dt', function (e, settings) {
        if (!settings) { return; }
        $.each(settings.aoColumns || [], function (i, col) {
            if (col.bSortable === false && col.nTh) {
                $(col.nTh).off('click.DT keypress.DT')
                    .removeClass('sorting sorting_asc sorting_desc')
                    .addClass('sorting_disabled')
                    .removeAttr('tabindex aria-controls');
            }
        });
        if (settings.nTableWrapper) { $(settings.nTableWrapper).addClass('pnp-table-toolkit'); }
    });

    // Column roles: "#" → tight index column, "Action(s)" → tight right-aligned
    // action column with icon buttons. Re-applied on every draw (server-side
    // tables re-render their cells).
    var INDEX = /^(#|no\.?)$/i;
    var ACTION = /^(action|actions)$/i;
    function tagColumns(settings) {
        if (!settings || !settings.aoColumns) { return; }
        var $table = $(settings.nTable);
        $.each(settings.aoColumns, function (i, col) {
            var label = col.nTh ? $(col.nTh).text().replace(/\s+/g, ' ').trim() : '';
            var cls = INDEX.test(label) ? 'pnp-col-index' : (ACTION.test(label) ? 'pnp-col-actions' : null);
            if (!cls) { return; }
            $(col.nTh).addClass(cls);
            $table.find('> tbody > tr').each(function () {
                var cells = this.children;
                if (cells[i] && cells[i].tagName === 'TD' && !$(this).hasClass('child')) { cells[i].classList.add(cls); }
            });
        });
    }
    $(document).on('init.dt draw.dt', function (e, settings) { tagColumns(settings); });
})(window.jQuery);

/* Tables initialised inside a hidden modal measure 0px columns; re-measure on open. */
(function ($) {
    'use strict';
    if (!$ || !$.fn || !$.fn.dataTable) { return; }
    $(document).on('shown.bs.modal', function (e) {
        var $tables = $(e.target).find('table.dataTable');
        if (!$tables.length) { return; }
        setTimeout(function () {
            $tables.each(function () {
                try { $(this).DataTable().columns.adjust(); } catch (err) {}
            });
        }, 50);
    });
})(window.jQuery);
