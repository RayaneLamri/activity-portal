@props(['bodyClass' => 'app app-login p-0'])
@php($isSignup = str_contains($bodyClass, 'app-signup'))

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('portal/assets/favicon.svg') }}">

        <script defer src="{{ asset('portal/assets/plugins/fontawesome/js/all.min.js') }}"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['public/portal/assets/scss/portal.scss'])
        <style>
            .select2-container--default .select2-selection--multiple {
                min-height: 38px;
                border-color: #ced4da;
            }
        </style>
        @vite(['resources/js/app.js'])
    </head>

    <body class="{{ $bodyClass }}" data-bs-no-jquery>
        <div class="row g-0 app-auth-wrapper">
            <div class="col-12 {{ $isSignup ? 'col-md-12 col-lg-12' : 'col-md-7 col-lg-6' }} auth-main-col text-center p-5">
                <div class="d-flex flex-column h-100">
                    <div class="app-auth-body mx-auto">
                        <div class="app-auth-branding mb-4">
                            <a class="app-logo" href="{{ url('/') }}">
                                <img class="logo-icon me-2" src="{{ asset('portal/assets/images/app-logo.svg') }}" alt="logo">
                            </a>
                        </div>

                        {{ $slot }}
                    </div>

                    <footer class="app-auth-footer">
                        <div class="container text-center py-3">
                            <small class="copyright">
                                Activity Portal · Demo project
                            </small>
                        </div>
                    </footer>
                </div>
            </div>

            @unless ($isSignup)
                <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
                    <div class="auth-background-holder"></div>
                    <div class="auth-background-mask"></div>
                </div>
            @endunless
        </div>
    </body>
</html>
