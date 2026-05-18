<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="{{ asset('portal/assets/favicon.ico') }}">
        <script defer src="{{ asset('portal/assets/plugins/fontawesome/js/all.min.js') }}"></script>
        <link id="theme-style" rel="stylesheet" href="{{ asset('portal/assets/css/portal.css') }}">
    </head>
    <body class="app app-login p-0">
        <div class="row g-0 app-auth-wrapper">
            <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
                <div class="d-flex flex-column align-content-end">
                    <div class="app-auth-body mx-auto">
                        <div class="app-auth-branding mb-4">
                            <a class="app-logo" href="{{ url('/') }}">
                                <img class="logo-icon me-2" src="{{ asset('portal/assets/images/app-logo.svg') }}" alt="logo">
                            </a>
                        </div>

                        <h2 class="auth-heading text-center mb-4">Portal Activity Workflow</h2>
                        <div class="auth-form-container text-start">
                            <p class="text-muted mb-4">
                                Browse activities, manage registrations, and handle admin decisions inside a Portal Bootstrap 5 interface faithful to the original theme.
                            </p>

                            @if (Route::has('login'))
                                <div class="d-grid gap-3">
                                    @auth
                                        <a href="{{ route('dashboard') }}" class="btn app-btn-primary w-100 theme-btn mx-auto">Open app</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn app-btn-primary w-100 theme-btn mx-auto">Log in</a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="btn app-btn-secondary w-100">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif

                            <div class="mt-4 p-3 bg-light rounded">
                                <div class="fw-semibold mb-2">Seeded credentials</div>
                                <div>Admin: <code>admin@example.com</code> / <code>password</code></div>
                                <div>User: <code>marion@example.com</code> / <code>password</code></div>
                            </div>
                        </div>
                    </div>

                    <footer class="app-auth-footer">
                        <div class="container text-center py-3">
                            <small class="copyright">
                                Designed with <span class="sr-only">love</span>
                                <i class="fas fa-heart" style="color: #fb866a;"></i>
                                by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank" rel="noreferrer">Xiaoying Riley</a> for developers
                            </small>
                        </div>
                    </footer>
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
                <div class="auth-background-holder"></div>
                <div class="auth-background-mask"></div>
                <div class="auth-background-overlay p-3 p-lg-5">
                    <div class="d-flex flex-column align-content-end h-100">
                        <div class="h-100"></div>
                        <div class="overlay-content p-3 p-lg-4 rounded">
                            <h5 class="mb-3 overlay-title">Bootstrap 5 Portal Theme</h5>
                            <div>
                                Interface admin modernisée, sidepanel Portal, cartes natives du thème, formulaires auth Portal et tables style original.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>