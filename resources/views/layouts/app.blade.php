<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title>Syndeo</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm.ico') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- App favicon -->

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet">
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet">
    
    <!-- Scripts do Vite (mantenha apenas se necessário) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sidebar toggle smoothness */
        .vertical-menu {
            transition: width 220ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: width;
        }
        .navbar-brand-box {
            transition: width 220ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: width;
        }
        .main-content {
            transition: margin-left 220ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: margin-left;
        }
        .footer { /* if present */
            transition: left 220ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: left;
        }

        /* Ensure content shifts on small screens when sidebar is opened */
        @media (max-width: 992px) {
            body.sidebar-enable .main-content { margin-left: 240px; }
        }

        /* Page transition styles */
        body.fade-init { opacity: 0; }
        body.fade-in { opacity: 1; transition: opacity 250ms ease; }
        body.fade-out { opacity: 0; transition: opacity 200ms ease; }

        /* Element reveal animations */
        :root {
            --reveal-distance: 16px;
            --reveal-duration: 500ms;
            --reveal-ease: cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-up {
            opacity: 0;
            transform: translateY(var(--reveal-distance));
            transition: opacity var(--reveal-duration) var(--reveal-ease) var(--reveal-delay, 0ms),
                        transform var(--reveal-duration) var(--reveal-ease) var(--reveal-delay, 0ms);
            will-change: opacity, transform;
        }
        .reveal-up.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal-up, .reveal-up.revealed {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased fade-init">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <div id="layout-wrapper">
        <!-- Top Navigation -->
        @include('layouts.navigation')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Area that respects sidebar width -->
        <main class="main-content">
            <div class="page-content">
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    <!-- SCRIPTS (movidos para dentro do body) -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- REMOVA ESTES SE NÃO FOR ESSENCIAIS (podem causar conflitos) -->
    <!--
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>
        <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
        -->
        
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <!-- Mantenha apenas estes se forem necessários -->
    
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Inicialização manual dos dropdowns (adicione este script) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownTriggers = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
            dropdownTriggers.map(function(trigger) {
                return new bootstrap.Dropdown(trigger)
            });
        });
    </script>
    <script>
        (function() {
            var isInternalLink = function(anchor) {
                if (!anchor || !anchor.href) return false;
                var url = new URL(anchor.href, window.location.href);
                var current = new URL(window.location.href);
                if (url.origin !== current.origin) return false;
                if (url.hash && (url.pathname === current.pathname) && (url.search === current.search)) return false; // ignore same-page hash
                if (anchor.hasAttribute('target') && anchor.getAttribute('target') === '_blank') return false;
                if (anchor.hasAttribute('download')) return false;
                return true;
            };

            var startEnterTransition = function() {
                // Trigger fade-in
                document.body.classList.remove('fade-init');
                // Next frame, add fade-in so transition applies
                requestAnimationFrame(function() {
                    document.body.classList.add('fade-in');
                });
            };

            var startExitTransition = function(callback) {
                // Trigger fade-out then navigate
                document.body.classList.remove('fade-in');
                document.body.classList.add('fade-out');
                var done = false;
                var finish = function() { if (done) return; done = true; callback(); };
                document.body.addEventListener('transitionend', finish, { once: true });
                // Fallback in case transitionend doesn't fire
                setTimeout(finish, 300);
            };

            // Enter on DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startEnterTransition);
            } else {
                startEnterTransition();
            }

            // Intercept link clicks for smooth exit
            document.addEventListener('click', function(e) {
                var anchor = e.target.closest('a');
                if (!anchor) return;
                if (!isInternalLink(anchor)) return;
                // Allow modified clicks (ctrl/cmd/shift)
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                e.preventDefault();
                var href = anchor.href;
                startExitTransition(function() { window.location.href = href; });
            });

            // Intercept form submissions
            document.addEventListener('submit', function(e) {
                var form = e.target;
                if (!form || form.target === '_blank') return;
                // Skip interception for forms marked as AJAX or no-transition
                if (form.hasAttribute('data-ajax') || form.hasAttribute('data-no-transition')) return;
                // Let the form submit after exit animation
                e.preventDefault();
                startExitTransition(function() { form.submit(); });
            }, true);

            // Handle back/forward navigation
            window.addEventListener('pageshow', function(event) {
                // When navigating with bfcache, ensure we fade in
                if (event.persisted) {
                    document.body.classList.remove('fade-out');
                    document.body.classList.add('fade-in');
                }
            });

            window.addEventListener('beforeunload', function() {
                document.body.classList.remove('fade-in');
                document.body.classList.add('fade-out');
            });
        })();
    </script>
    <script>
        // Reveal-on-scroll animations (fade-in up)
        (function() {
            var defaultSelectors = [
                '.main-form-container',
                '.form-title',
                '.form-group',
                '.button-container',
                '.page-content > *'
            ];

            var collect = function(selector) { return Array.prototype.slice.call(document.querySelectorAll(selector)); };
            var unique = function(list) { return Array.from(new Set(list)); };

            var elements = unique(
                collect('[data-reveal]')
                    .concat.apply([], defaultSelectors.map(collect))
            ).filter(function(el) { return !el.classList.contains('reveal-skip'); });

            if (!elements.length) return;

            elements.forEach(function(el, index) {
                el.classList.add('reveal-up');
                el.style.setProperty('--reveal-delay', (index * 60) + 'ms');
            });

            var observer = new IntersectionObserver(function(entries, obs) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        requestAnimationFrame(function() { el.classList.add('revealed'); });
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -10% 0px' });

            elements.forEach(function(el) { observer.observe(el); });
        })();
    </script>
</body>
</html>