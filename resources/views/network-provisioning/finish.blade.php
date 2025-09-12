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
        pointer-events: none;
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
        cursor: pointer;
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
    /* Hide Grafana view by default and ensure it doesn't interfere */
    #grafana-credentials-view { 
        display: none !important;
        visibility: hidden;
    }
    /* When shown, override the display none */
    #grafana-credentials-view.active {
        display: flex !important;
        visibility: visible;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        text-align: center;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .password-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .password-icon {
        color: #64748b;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
        border-radius: 50%;
        padding: 4px;
    }
    .password-icon:hover {
        color: #13395d;
        background-color: rgba(19, 57, 93, 0.1);
        transform: scale(1.1);
    }
    .password-icon:active {
        transform: scale(0.95);
    }
    .password-input {
        border: none;
        background: transparent;
        outline: none;
        font-size: .95rem;
        color: #1e293b;
        font-weight: 500;
        width: 120px;
        transition: all 0.2s ease;
    }
    .password-input:focus {
        background-color: rgba(19, 57, 93, 0.05);
        border-radius: 4px;
        padding: 2px 4px;
    }
</style>

@php
    $finishId = request()->query('id');
    $finishName = request()->query('name');
    $nmRecord = null;
    try {
        if ($finishId) {
            $nmRecord = \Illuminate\Support\Facades\DB::table('networkmanagement')->where('id', $finishId)->first();
        }
        // Fallback: if no id in URL, try resolving by name (property_name) most recent
        if (!$nmRecord && $finishName) {
            $nmRecord = \Illuminate\Support\Facades\DB::table('networkmanagement')
                ->where('property_name', $finishName)
                ->orderByDesc('id')
                ->first();
        }
    } catch (\Throwable $e) { $nmRecord = null; }
    
    // Strictly check grafana_toggle flag only
    $hasGrafana = false;
    $customerEmail = null;
    $randomPassword = null;
    if ($nmRecord) {
        $customerEmail = $nmRecord->customer_email ?? null;
        $randomPassword = $nmRecord->random_password ?? null;
        // Only show Grafana button if grafana_toggle is explicitly set to 1
        $hasGrafana = isset($nmRecord->grafana_toggle) && (int)$nmRecord->grafana_toggle === 1;
    }
@endphp

<!-- Main content wrapper -->
<div id="main-wrapper">
    <!-- Success View -->
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

                @if($hasGrafana)
                <div class="cta-row">
                    <button type="button" class="btn-primary-syndeo" id="grafana-credentials-btn">
                        <i class="mdi mdi-account-key-outline"></i>
                        Customer Grafana Credentials
                    </button>
                </div>
                @endif

                <div class="mt-3 muted">You can safely close this page.</div>
            </div>
        </div>
    </div>

    <!-- Grafana Credentials View (Initially Hidden) -->
    @if($hasGrafana)
    <div id="grafana-credentials-view" class="container-fluid">
        <div class="finish-card">
            <div class="finish-badge">
                <i class="mdi mdi-shield-key-outline"></i>
                Grafana Credentials
            </div>
            <div class="confetti">🔐</div>
            <h1 class="finish-title">Grafana Credentials</h1>
            <div style="text-align:left; margin: 1rem auto; max-width: 520px;">
                <div style="display:flex; justify-content:space-between; padding:.75rem 1rem; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:.75rem;">
                    <span style="color:#64748b; font-weight:600;">Username</span>
                    <code style="color:#13395d;">{{ $customerEmail ? explode('@', $customerEmail)[0] : 'N/A' }}</code>
                </div>
                <div style="display:flex; justify-content:space-between; padding:.75rem 1rem; border:1px solid #e5e7eb; border-radius:10px;">
                    <span style="color:#64748b; font-weight:600;">Password</span>
                    <div class="password-group">
                        <input class="password-input" type="password" value="{{ $randomPassword ?? 'N/A' }}" id="password_grafana" readonly>
                        <i class="mdi mdi-eye password-icon" id="toggle-password" title="Show/Hide Password"></i>
                        <i class="mdi mdi-content-copy password-icon" id="copy-password" title="Copy to Clipboard"></i>
                    </div>
                </div>
            </div>
            <div class="cta-row">
                <button type="button" class="btn-primary-syndeo" id="back-to-finish">
                    <i class="mdi mdi-arrow-left"></i>
                    Back to Success
                </button>
                <a class="btn-primary-syndeo"
                   href="mailto:?subject={{ rawurlencode('Grafana Credentials - ' . ($finishName ?? 'Property')) }}&body={{ rawurlencode('Grafana Credentials:\n\nUsername: ' . ($customerEmail ? explode('@', $customerEmail)[0] : 'N/A') . '\nPassword: ' . ($randomPassword ?? 'N/A')) }}"
                   target="_blank">
                    <i class="mdi mdi-share-variant"></i>
                    Share
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<footer class="mt-6 border-t border-gray-200 py-4">
    <div class="max-w-4xl mx-auto px-4 text-center text-sm text-gray-600">
        © <script>document.write(new Date().getFullYear())</script> Syndeo Wireless. All rights reserved.
    </div>
</footer>

<script>
// Populate title/subtitle from query string
(function() {
    const params = new URLSearchParams(window.location.search);
    const name = params.get('name');
    if (name) {
        document.getElementById('finish-title').textContent = name + ' provisioning finished';
        document.getElementById('finish-sub').textContent = 'The provisioning "' + name + '" completed successfully.';
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('finish-content');
    const grafanaView = document.getElementById('grafana-credentials-view');
    const grafanaBtn = document.getElementById('grafana-credentials-btn');
    const backBtn = document.getElementById('back-to-finish');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const copyPasswordBtn = document.getElementById('copy-password');
    const passwordInput = document.getElementById('password_grafana');

    // State management function
    function showView(viewName) {
        if (viewName === 'success') {
            if (content) {
                content.style.display = '';
                content.style.visibility = 'visible';
            }
            if (grafanaView) {
                grafanaView.classList.remove('active');
            }
        } else if (viewName === 'grafana') {
            if (content) {
                content.style.display = 'none';
            }
            if (grafanaView) {
                grafanaView.classList.add('active');
            }
        }
    }

    // Button event listeners
    if (grafanaBtn) {
        grafanaBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showView('grafana');
        });
    }

    if (backBtn) {
        backBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showView('success');
        });
    }

    // Password toggle
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('mdi-eye');
                this.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('mdi-eye-off');
                this.classList.add('mdi-eye');
            }
        });
    }

    // Copy to clipboard
    if (copyPasswordBtn && passwordInput) {
        copyPasswordBtn.addEventListener('click', function() {
            passwordInput.select();
            passwordInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(passwordInput.value).then(() => {
                this.classList.remove('mdi-content-copy');
                this.classList.add('mdi-check');
                this.style.color = '#10b981';
                setTimeout(() => {
                    this.classList.remove('mdi-check');
                    this.classList.add('mdi-content-copy');
                    this.style.color = '#64748b';
                }, 2000);
            });
        });
    }

    // Transition overlay
    let overlay;
    try {
        overlay = document.createElement('div');
        overlay.id = 'transition-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 70px;
            left: 240px;
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

        requestAnimationFrame(() => { 
            overlay.style.opacity = '1'; 
        });
    } catch (_) {}

    // Minimum visible time for overlay
    const MIN_MS = 5000;
    const start = Date.now();

    function reveal() {
        const elapsed = Date.now() - start;
        const wait = Math.max(0, MIN_MS - elapsed);
        setTimeout(() => {
            if (content) {
                content.style.visibility = 'visible';
            }
            if (overlay && overlay.parentNode) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    if (overlay.parentNode) {
                        overlay.parentNode.removeChild(overlay);
                    }
                }, 300);
            }
        }, wait);
    }

    // Handle GIF loading
    const safety = setTimeout(reveal, 5000);
    const img = document.querySelector('#transition-overlay img');
    if (img) {
        img.addEventListener('load', () => { 
            clearTimeout(safety); 
            reveal(); 
        });
        img.addEventListener('error', () => { 
            clearTimeout(safety); 
            reveal(); 
        });
    } else {
        reveal();
    }
});
</script>

@endsection