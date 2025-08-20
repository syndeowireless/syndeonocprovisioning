<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm.ico') }}">
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Page transition styles */
            body.fade-init { opacity: 0; }
            body.fade-in { opacity: 1; transition: opacity 100ms ease; }
            body.fade-out { opacity: 0; transition: opacity 80ms ease; }

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
    <body class="fade-init">
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden rounded-3">
                        <div class="card overflow-hidden" style="border-radius: 20px; margin-bottom: 0px !important">
                            <div class="card-body pt-0">
                                
                                <h3 class="text-center mt-5 mb-4">
                                    <a href="{{ url('/') }}" class="d-block auth-logo">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20" class="auth-logo-dark" style="width: 50%;">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20" class="auth-logo-light" style="width: 50%;">
                                    </a>
                                </h3>
                                
                                <div class="p-3">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                        @yield('footer-links')
                    </div>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script>
            (function() {
                var isInternalLink = function(anchor) {
                    if (!anchor || !anchor.href) return false;
                    var url = new URL(anchor.href, window.location.href);
                    var current = new URL(window.location.href);
                    if (url.origin !== current.origin) return false;
                    if (url.hash && (url.pathname === current.pathname) && (url.search === current.search)) return false;
                    if (anchor.hasAttribute('target') && anchor.getAttribute('target') === '_blank') return false;
                    if (anchor.hasAttribute('download')) return false;
                    return true;
                };

                var startEnterTransition = function() {
                    document.body.classList.remove('fade-init');
                    requestAnimationFrame(function() {
                        document.body.classList.add('fade-in');
                    });
                };

                var startExitTransition = function(callback) {
                    document.body.classList.remove('fade-in');
                    document.body.classList.add('fade-out');
                    var done = false;
                    var finish = function() { if (done) return; done = true; callback(); };
                    document.body.addEventListener('transitionend', finish, { once: true });
                    setTimeout(finish, 300);
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', startEnterTransition);
                } else {
                    startEnterTransition();
                }

                document.addEventListener('click', function(e) {
                    var anchor = e.target.closest('a');
                    if (!anchor) return;
                    if (!isInternalLink(anchor)) return;
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    e.preventDefault();
                    var href = anchor.href;
                    startExitTransition(function() { window.location.href = href; });
                });

                document.addEventListener('submit', function(e) {
                    var form = e.target;
                    if (!form || form.target === '_blank') return;
                    e.preventDefault();
                    startExitTransition(function() { form.submit(); });
                }, true);

                window.addEventListener('pageshow', function(event) {
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
                    '.account-pages',
                    '.card',
                    '.p-3',
                    '.row > *'
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
