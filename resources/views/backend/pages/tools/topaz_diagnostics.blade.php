@extends('backend.master.template')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1">Topaz Diagnostics</h1>
            <p class="text-muted mb-0">Quick SigWeb health check for this workstation/browser.</p>
        </div>
        <a href="{{ url('new_application') }}" class="btn btn-outline-secondary">Back to New Application</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-info">
                If detection fails in Chrome/Edge, allow Local Network Access for this site, then retry.
            </div>
            <div class="d-flex align-items-center mb-3">
                <strong class="mr-2">Connection Status:</strong>
                <span id="diagStatus" class="badge badge-secondary">Not checked</span>
            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-primary mr-2" onclick="runTopazDiagnostics()">Run Diagnostics</button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearCanvas()">Clear Canvas</button>
            </div>
            <div class="mb-3">
                <canvas id="cnv" width="500" height="120" style="border:1px solid #ced4da;background:#fff;"></canvas>
            </div>
            <pre id="diagLog" class="p-3 mb-0" style="background:#f8f9fa;border:1px solid #e9ecef;border-radius:4px;min-height:180px;">Press "Run Diagnostics" to start.</pre>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://www.sigplusweb.com/SigWebTablet.js"></script>
<script>
    var sigWebTimer = null;

    function setStatus(text, kind) {
        var el = document.getElementById('diagStatus');
        if (!el) return;
        el.className = 'badge';
        if (kind === 'ok') {
            el.classList.add('badge-success');
        } else if (kind === 'warn') {
            el.classList.add('badge-warning');
        } else if (kind === 'fail') {
            el.classList.add('badge-danger');
        } else {
            el.classList.add('badge-secondary');
        }
        el.textContent = text;
    }

    function appendLog(line) {
        var log = document.getElementById('diagLog');
        if (!log) return;
        log.textContent += '\n' + line;
    }

    function clearCanvas() {
        var cnv = document.getElementById('cnv');
        if (!cnv || !cnv.getContext) return;
        var ctx = cnv.getContext('2d');
        ctx.clearRect(0, 0, cnv.width, cnv.height);
    }

    function stopTablet() {
        try {
            if (typeof SetTabletState === 'function' && sigWebTimer !== null) {
                SetTabletState(0, sigWebTimer);
                sigWebTimer = null;
            }
        } catch (e) {
            appendLog('stopTablet error: ' + (e.message || e));
        }
    }

    function runTopazDiagnostics() {
        var log = document.getElementById('diagLog');
        if (log) log.textContent = 'Starting diagnostics...';
        setStatus('Checking', 'warn');
        stopTablet();
        clearCanvas();

        appendLog('Timestamp: ' + new Date().toString());
        appendLog('URL: ' + window.location.href);
        appendLog('Browser: ' + navigator.userAgent);
        appendLog('Tip: Chrome -> chrome://settings/content/localNetworkAccess');
        appendLog('Tip: Edge -> edge://settings/privacy/sitePermissions/allPermissions/localNetworkAccess');

        if (typeof IsSigWebInstalled !== 'function') {
            appendLog('Result: SigWeb script did not load (IsSigWebInstalled missing).');
            appendLog('Action: ensure internet access to sigplusweb.com and allow Local Network Access for this site.');
            setStatus('Script not loaded', 'fail');
            return;
        }

        var installed = false;
        try {
            installed = IsSigWebInstalled();
            appendLog('IsSigWebInstalled(): ' + installed);
        } catch (e) {
            appendLog('IsSigWebInstalled() error: ' + (e.message || e));
            setStatus('Check failed', 'fail');
            return;
        }

        if (!installed) {
            appendLog('SigWeb is not running or cannot be reached.');
            appendLog('Action: start SigWeb locally, then allow Local Network Access and reload this page.');
            setStatus('SigWeb not running', 'warn');
            return;
        }

        setStatus('Connected', 'ok');

        try {
            if (typeof GetSigWebVersion === 'function') {
                appendLog('GetSigWebVersion(): ' + GetSigWebVersion());
            } else {
                appendLog('GetSigWebVersion(): unavailable in this script build.');
            }
        } catch (e) {
            appendLog('GetSigWebVersion() error: ' + (e.message || e));
        }

        try {
            var cnv = document.getElementById('cnv');
            var ctx = cnv.getContext('2d');
            if (typeof SetTabletState === 'function') {
                sigWebTimer = SetTabletState(1, ctx, 50);
                appendLog('SetTabletState(1, ctx, 50): started');
                setTimeout(function () {
                    stopTablet();
                    appendLog('Tablet polling stopped.');
                }, 1200);
            } else {
                appendLog('SetTabletState() unavailable.');
            }
        } catch (e) {
            appendLog('Tablet start error: ' + (e.message || e));
        }
    }
</script>
@endsection
