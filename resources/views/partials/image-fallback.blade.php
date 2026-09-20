{{-- Replace any <img> that fails to load (external URLs, cached 404s, etc.)
     with a placeholder. Missing local files are already handled server-side
     by ImageFallbackController; this is the safety net for everything else.
     Uses a capturing listener so it also covers images added later by JS. --}}
<script>
(function () {
    var fallback = '{{ asset('img/placeholder/image.svg') }}';
    window.addEventListener('error', function (e) {
        var el = e.target;
        if (!el || el.tagName !== 'IMG' || el.dataset.fallbackApplied) return;
        el.dataset.fallbackApplied = '1';
        el.src = fallback;
    }, true);
})();
</script>
