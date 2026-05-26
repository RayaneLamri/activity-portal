<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ config('app.name', 'Laravel') }} - Server error</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('portal/assets/favicon.svg') }}">

        <script defer src="{{ asset('portal/assets/plugins/fontawesome/js/all.min.js') }}"></script>
        @vite(['public/portal/assets/scss/portal.scss'])
    </head>

    <body class="app app-404-page">
        <div class="container mb-5">
            <div class="row">
                <div class="col-12 col-md-11 col-lg-7 col-xl-6 mx-auto">
                    <div class="app-branding text-center mb-5">
                        <a class="app-logo" href="{{ url('/') }}">
                            <img class="logo-icon me-2" src="{{ asset('portal/assets/images/app-logo.svg') }}" alt="Activity Portal logo">
                            <span class="logo-text">ACTIVITY PORTAL</span>
                        </a>
                    </div>

                    <div class="app-card p-5 text-center shadow-sm">
                        <h1 class="page-title mb-4">
                            500<br>
                            <span class="font-weight-light">Server Error</span>
                        </h1>

                        <div class="mb-4">
                            Something went wrong while processing this request.
                        </div>

                        <a class="btn app-btn-primary" href="{{ url('/') }}">Go to home page</a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="app-footer">
            <div class="container text-center py-3">
                <small class="copyright">Activity Portal · Demo project</small>
            </div>
        </footer>
    </body>
</html>
