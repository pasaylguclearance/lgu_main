/* ============================================================
   PNP DATEPICKER — one branded calendar for every date field.

   Every <input type="date"> becomes a text input driven by the
   jQuery UI datepicker (already bundled in /ui/jquery-ui.js):
     • value stays YYYY-MM-DD, exactly what the native control
       submitted, so page scripts that .val() / .val('2026-09-21')
       and inline onchange="..." handlers keep working unchanged;
     • the open calendar ("slate") is sized to the input it belongs
       to (clamped 280–420px) and positioned under it;
     • disabled / readonly inputs are converted for the look but
       never open a calendar;
     • inputs added later (modals, JS-rendered forms) are picked up
       by a MutationObserver.
   Styling lives in theme.css (.pnp-date, #ui-datepicker-div).
   ============================================================ */
(function ($) {
    'use strict';
    if (!$ || !$.fn || !$.fn.datepicker) { return; }

    var ISO = /^\d{4}-\d{2}-\d{2}$/;

    function enhance(input) {
        var $el = $(input);
        if ($el.data('pnpDate')) { return; }
        $el.data('pnpDate', true);

        var min = input.getAttribute('min');
        var max = input.getAttribute('max');
        var value = input.value;

        // Swap the control type; keep name/id/value so forms & scripts see no change.
        try { input.type = 'text'; } catch (e) { input.setAttribute('type', 'text'); }
        $el.addClass('pnp-date')
           .attr({ autocomplete: 'off', inputmode: 'numeric' });
        if (!$el.attr('placeholder')) { $el.attr('placeholder', 'YYYY-MM-DD'); }
        if (value) { input.value = value; }

        if (input.disabled || input.readOnly) { return; }

        $el.datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '1900:+10',
            showAnim: '',
            showButtonPanel: false,
            firstDay: 0,
            minDate: min && ISO.test(min) ? min : null,
            maxDate: max && ISO.test(max) ? max : null,
            constrainInput: false,
            beforeShow: function (el, inst) {
                // jQuery UI rebuilds the shared picker div on every open and month
                // change and wipes its inline width, which made it flash full-width.
                // Size it through a CSS variable on <html> (theme.css reads it with
                // !important) so the first paint is already the right size.
                var w = $(el).outerWidth();
                var width = Math.max(280, Math.min(420, w));
                document.documentElement.style.setProperty('--pnp-dp-w', width + 'px');
                inst.dpDiv.addClass('pnp-datepicker');
            },
            onClose: function () {
                $(this).trigger('blur');
            }
        });
    }

    function scan(root) {
        var scope = root && root.querySelectorAll ? root : document;
        var nodes = scope.querySelectorAll('input[type="date"]');
        for (var i = 0; i < nodes.length; i++) { enhance(nodes[i]); }
    }

    $(function () {
        scan(document);
        if (window.MutationObserver) {
            new MutationObserver(function (muts) {
                for (var i = 0; i < muts.length; i++) {
                    var added = muts[i].addedNodes;
                    for (var j = 0; j < added.length; j++) {
                        var n = added[j];
                        if (n.nodeType !== 1) { continue; }
                        if (n.matches && n.matches('input[type="date"]')) { enhance(n); }
                        else { scan(n); }
                    }
                }
            }).observe(document.body, { childList: true, subtree: true });
        }
        $(document).on('shown.bs.modal', function () { scan(document); });

        // Keep the open calendar attached to its input when the page/modal scrolls.
        $(window).on('resize', function () {
            var $div = $('#ui-datepicker-div');
            if ($div.is(':visible')) { $.datepicker._hideDatepicker(); }
        });
    });
})(window.jQuery);
