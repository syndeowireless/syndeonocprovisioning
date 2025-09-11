@extends('layouts.app')

@section('content')

<style>
    .finish-hero {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        text-align: center;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .finish-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(19,57,93,.15);
        padding: 2.5rem 2rem;
        max-width: 760px;
        width: 100%;
        border: 4px solid #fbbf0f;
        position: relative;
        overflow: hidden;
    }
    .finish-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(closest-side, rgba(19,57,93,.06), transparent 60%);
        transform: rotate(15deg);
    }
    .finish-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: #13395d;
        border: 2px solid #fbbf0f;
        color: #fff;
        font-weight: 700;
        letter-spacing: .5px;
        border-radius: 9999px;
        padding: .5rem 1rem;
        box-shadow: 0 6px 14px rgba(19,57,93,.25);
    }
    .finish-title {
        font-size: 2rem;
        font-weight: 800;
        color: #13395d;
        margin: 1.25rem 0 .5rem;
    }
    .finish-sub {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 1.25rem;
    }
    .confetti {
        font-size: 52px;
        line-height: 1;
        margin-bottom: .5rem;
        animation: pop 1.2s ease-in-out infinite;
    }
    @keyframes pop {
        0%,100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-6px) scale(1.05); }
    }
    .cta-row { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; margin-top: 1.25rem; }
    .btn-primary-syndeo {
        background-color: #13395d;
        color: #fff;
        border: 3px solid #fbbf0f;
        padding: .85rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .25s ease;
    }
    .btn-primary-syndeo:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 10px 22px rgba(19,57,93,.35);
        background-color: #fbbf0f;
        border-color: #13395d;
        color: #fff;
    }
    .muted { color: #94a3b8; font-size: .9rem; }
    /* Ensure finish content stays hidden until overlay completes */
    #finish-content { visibility: hidden; }
    /* Simple spinner keyframes as fallback */
    @keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
</style>

<!-- Content wrapper hidden until transition completes -->
<div id="finish-content">
<div class="container-fluid finish-hero">
    <div class="finish-card">
        <div class="finish-badge">
            <i class="mdi mdi-check-decagram"></i>
            Success
        </div>
        <div class="confetti">🎉</div>
        <h1 class="finish-title" id="finish-title">Provisioning completed</h1>
        <div class="finish-sub" id="finish-sub">Everything is set. Great job!</div>

        <div class="cta-row">
            <a href="#" class="btn-primary-syndeo" id="grafana-credentials-btn">
                <i class="mdi mdi-account-key-outline"></i>
                Customer Grafana Credentials
            </a>
        </div>

        <div class="mt-3 muted">You can safely close this page.</div>
    </div>
</div>
</div>

<footer class="mt-6 border-t border-gray-200 py-4">
    <div class="max-w-4xl mx-auto px-4 text-center text-sm text-gray-600">
        © <script>document.write(new Date().getFullYear())</script> Syndeo Wireless. All rights reserved.
    </div>
</footer>

<script>
// Populate title/subtitle from query string: ?name=...
(function() {
    const params = new URLSearchParams(window.location.search);
    const name = params.get('name');
    if (name) {
        document.getElementById('finish-title').textContent = name + ' provisioning finished';
        document.getElementById('finish-sub').textContent = 'The provisioning "' + name + '" completed successfully.';
    }
})();

// Always show transition GIF first, then reveal content
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('finish-content');

    let overlay;
    try {
        overlay = document.createElement('div');
        overlay.id = 'transition-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 70px; /* Height of topbar */
            left: 240px; /* Sidebar width */
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
            opacity: 0;
        `;
        if (window.innerWidth <= 992) {
            overlay.style.left = '0px';
            overlay.style.top = '60px';
        }

        const loadingContainer = document.createElement('div');
        loadingContainer.style.cssText = `
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        `;

        const gifElement = document.createElement('img');
        gifElement.src = '/assets/images/Transition_Animation.gif';
        gifElement.alt = 'Loading...';
        gifElement.style.cssText = `
            max-width: 300px;
            max-height: 300px;
            width: auto;
            height: auto;
            margin-bottom: 1.5rem;
        `;

        const spinner = document.createElement('div');
        spinner.style.cssText = `
            width: 60px;
            height: 60px;
            border: 6px solid #e5e7eb;
            border-top: 6px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
            display: none;
        `;

        gifElement.addEventListener('error', () => {
            gifElement.style.display = 'none';
            spinner.style.display = 'block';
        });

        loadingContainer.appendChild(gifElement);
        loadingContainer.appendChild(spinner);
        overlay.appendChild(loadingContainer);
        document.body.appendChild(overlay);

        requestAnimationFrame(() => { overlay.style.opacity = '1'; });
    } catch (_) {}

    // Minimum visible time for the overlay to ensure GIF is seen
    const MIN_MS = 1200;
    const start = Date.now();

    function reveal() {
        const elapsed = Date.now() - start;
        const wait = Math.max(0, MIN_MS - elapsed);
        setTimeout(() => {
            if (content) content.style.visibility = 'visible';
            if (overlay && overlay.parentNode) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
                }, 300);
            }
        }, wait);
    }

    // If GIF loads, still enforce minimum time; on error fallback quickly
    // Also add a safety timeout in case load doesn't fire
    const safety = setTimeout(reveal, 4000);
    const img = document.querySelector('#transition-overlay img');
    if (img) {
        img.addEventListener('load', () => { clearTimeout(safety); reveal(); });
        img.addEventListener('error', () => { clearTimeout(safety); reveal(); });
    } else {
        reveal();
    }
});
</script>

@endsection