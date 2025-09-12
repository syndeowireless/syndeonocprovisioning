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
    /* Hide Grafana view by default */
    #grafana-credentials-view { 
        display: none; 
        position: relative;
        z-index: 1;
    }
    #grafana-credentials-view.finish-hero {
        display: flex !important;
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
    $hasGrafana = false;
    $customerEmail = null;
    $randomPassword = null;
    if ($nmRecord) {
        $customerEmail = $nmRecord->customer_email ?? null;
        $randomPassword = $nmRecord->random_password ?? null;
        $hasGrafana = isset($nmRecord->grafana_toggle) ? (int)$nmRecord->grafana_toggle === 1 : false;
        if (!$hasGrafana && ($customerEmail || $randomPassword)) { $hasGrafana = true; }
    }
@endphp

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

<!-- Grafana Credentials View -->
<div id="grafana-credentials-view" class="container-fluid finish-hero">
    <div class="finish-card">
        <div class="finish-badge">
            <i class="mdi mdi-shield-key-outline"></i>
            Grafana Credentials
        </div>
        <div class="confetti">🔐</div>
        @if($hasGrafana)
            <div style="text-align:left; margin: 1rem auto; max-width: 520px;">
                <div style="display:flex; justify-content:space-between; padding:.75rem 1rem; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:.75rem;">
                    <span style="color:#64748b; font-weight:600;">Username</span>
                    <code style="color:#13395d;">{{ $customerEmail ? explode('@', $customerEmail)[0] : 'N/A' }}</code>
                </div>
                <div style="display:flex; justify-content:space-between; padding:.75rem 1rem; border:1px solid #e5e7eb; border-radius:10px;">
                    <span style="color:#64748b; font-weight:600;">Password</span>
                    <div class="password-group" style="display:flex; align-items:center; gap:8px;">
                        <input class="password-input" type="password" value="{{ $randomPassword ?? 'N/A' }}" id="password_grafana" readonly style="border:none; background:transparent; outline:none; font-size:.95rem; color:#1e293b; font-weight:500; width:120px;">
                        <i class="mdi mdi-eye password-icon" onclick="show_password_grafana()" title="Show/Hide Password" style="color:#64748b; cursor:pointer; font-size:18px; transition:all 0.3s ease; border-radius:50%; padding:4px;"></i>
                        <i class="mdi mdi-content-copy password-icon" onclick="copy_to_clipboard_grafana()" title="Copy to Clipboard" style="color:#64748b; cursor:pointer; font-size:18px; transition:all 0.3s ease; border-radius:50%; padding:4px;"></i>
                    </div>
                </div>
            </div>
            <div class="cta-row">
                <a type="button"
                    class="btn-primary-syndeo"
                    style="text-decoration: none; display: flex; align-items: center; gap: 8px;"
                    href="mailto:?subject={{ rawurlencode('Grafana Credentials - ' . ($finishName ?? 'PROPERTY NAME')) }}&body={{ rawurlencode('Grafana Credentials:\n\nUsername: ' . ($customerEmail ? explode('@', $customerEmail)[0] : 'N/A') . '\nPassword: ' . ($randomPassword ?? 'N/A')) }}"
                    target="_blank"
                >
                    <i class="mdi mdi-share-variant" style="color: white;"></i>
                    Share
                </a>
            </div>
        @else
            <h1 class="finish-title">No Credentials Available</h1>
            <div class="finish-sub">Credentials were not generated for this provisioning.</div>
            <div class="cta-row">
                <a href="#" class="btn-primary-syndeo" id="back-to-finish" onclick="return backToFinish();">
                    <i class="mdi mdi-arrow-left"></i>
                    Back
                </a>
            </div>
        @endif
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

// Global variables for view elements
let content, grafanaView;

// Always show transition GIF first, then reveal content
document.addEventListener('DOMContentLoaded', function() {
    content = document.getElementById('finish-content');
    grafanaView = document.getElementById('grafana-credentials-view');
    
    // Debug all elements
    console.log('=== DEBUGGING ELEMENTS ===');
    console.log('content element:', content);
    console.log('grafanaView element:', grafanaView);
    
    const grafanaBtn = document.getElementById('grafana-credentials-btn');
    console.log('grafanaBtn element:', grafanaBtn);
    
    // Add click event listener to Grafana credentials button
    if (grafanaBtn) {
        console.log('Adding event listener to button');
        grafanaBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('=== BUTTON CLICKED ===');
            console.log('content before:', content ? content.style.display : 'null');
            console.log('grafanaView before:', grafanaView ? grafanaView.style.display : 'null');
            
            if (content) {
                content.style.display = 'none';
                console.log('Content set to none');
            } else {
                console.log('Content element not found!');
            }
            
             if (grafanaView) {
                 grafanaView.style.display = 'flex';
                 grafanaView.style.alignItems = 'center';
                 grafanaView.style.justifyContent = 'center';
                 grafanaView.style.minHeight = 'calc(100vh - 200px)';
                 console.log('Grafana view set to flex with centering');
             } else {
                 console.log('Grafana view element not found!');
             }
        });
    } else {
        console.log('Grafana button not found!');
    }

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
    const MIN_MS = 5000;
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
    const safety = setTimeout(reveal, 5000);
    const img = document.querySelector('#transition-overlay img');
    if (img) {
        img.addEventListener('load', () => { clearTimeout(safety); reveal(); });
        img.addEventListener('error', () => { clearTimeout(safety); reveal(); });
    } else {
        reveal();
    }

    // Explicit functions for inline handlers (most reliable across browsers)
    window.showGrafanaCredentials = function() {
        console.log('showGrafanaCredentials called');
        console.log('content element:', content);
        console.log('grafanaView element:', grafanaView);
        try {
            if (content) {
                content.style.display = 'none';
                console.log('content hidden');
            }
             if (grafanaView) {
                 grafanaView.style.display = 'flex';
                 grafanaView.style.alignItems = 'center';
                 grafanaView.style.justifyContent = 'center';
                 grafanaView.style.minHeight = 'calc(100vh - 200px)';
                 console.log('grafanaView shown with centering');
             }
        } catch (e) {
            console.error('Error in showGrafanaCredentials:', e);
        }
        return false; // prevent default anchor navigation
    };
    window.backToFinish = function() {
        console.log('backToFinish called');
        try {
            if (grafanaView) {
                grafanaView.style.display = 'none';
                console.log('grafanaView hidden');
            }
            if (content) {
                content.style.display = '';
                console.log('content shown');
            }
        } catch (e) {
            console.error('Error in backToFinish:', e);
        }
        return false;
    };
    
    // Password visibility toggle for Grafana
    window.show_password_grafana = function() {
        var x = document.getElementById('password_grafana');
        var icon = event.target;
        if (x.type === 'password') {
            x.type = 'text';
            icon.classList.remove('mdi-eye');
            icon.classList.add('mdi-eye-off');
        } else {
            x.type = 'password';
            icon.classList.remove('mdi-eye-off');
            icon.classList.add('mdi-eye');
        }
    };
    
    // Copy to clipboard for Grafana
    window.copy_to_clipboard_grafana = function() {
        var copyText = document.getElementById('password_grafana');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        var copyIcon = event.target;
        copyIcon.classList.remove('mdi-content-copy');
        copyIcon.classList.add('mdi-check');
        copyIcon.style.color = '#10b981';
        setTimeout(function() {
            copyIcon.classList.remove('mdi-check');
            copyIcon.classList.add('mdi-content-copy');
            copyIcon.style.color = '#64748b';
        }, 2000);
    };

    // Toggle views using event delegation for reliability
    document.addEventListener('click', function(e) {
        console.log('Click event detected on:', e.target);
        console.log('Target ID:', e.target.id);
        console.log('Target classes:', e.target.className);
        
        // Check if the clicked element is the button or inside the button
        if (e.target.id === 'grafana-credentials-btn' || e.target.closest('#grafana-credentials-btn')) {
            console.log('Grafana credentials button clicked!');
            e.preventDefault();
            e.stopPropagation();
            
            if (content) {
                content.style.display = 'none';
                console.log('content hidden via delegation');
            }
             if (grafanaView) {
                 grafanaView.style.display = 'flex';
                 grafanaView.style.alignItems = 'center';
                 grafanaView.style.justifyContent = 'center';
                 grafanaView.style.minHeight = 'calc(100vh - 200px)';
                 console.log('grafanaView shown via delegation with centering');
             }
            return false;
        }
        const backBtn = e.target.closest('#back-to-finish');
        if (backBtn) {
            console.log('Back button clicked via event delegation');
            e.preventDefault();
            if (grafanaView) {
                grafanaView.style.display = 'none';
                console.log('grafanaView hidden via delegation');
            }
            if (content) {
                content.style.display = '';
                console.log('content shown via delegation');
            }
            return false;
        }
    });
});
</script>

@endsection