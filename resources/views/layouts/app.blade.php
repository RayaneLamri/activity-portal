<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="{{ asset('portal/assets/favicon.ico') }}">

        <script defer src="{{ asset('portal/assets/plugins/fontawesome/js/all.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <style>
            .select2-container--default .select2-selection--multiple {
                min-height: 38px;
                border-color: #ced4da;
            }

        </style>
        @vite(['public/portal/assets/scss/portal.scss', 'resources/js/app.js'])
    </head>

    <body
        class="app"
        data-bs-no-jquery
        data-flash-success="{{ e(session('success') ?? session('status') ?? '') }}"
        data-flash-warning="{{ e(session('warning') ?? '') }}"
        data-flash-error="{{ e(session('error') ?? '') }}"
        data-validation-errors="{{ e(json_encode($errors->all())) }}"
    >
        @include('layouts.navigation')

        <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-flex d-xl-none position-fixed top-0 start-0 m-3 p-2 bg-white shadow-sm rounded" href="#" style="z-index: 1030;">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 30 30" role="img">
                <title>Menu</title>
                <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
            </svg>
        </a>

        <div class="app-wrapper">
            <div class="app-content pt-3 p-md-3 p-lg-4">
                <div class="container-xl">
                    @isset($header)
                        {{ $header }}
                    @endisset

                    @includeIf('partials.flash')

                    {{ $slot }}
                </div>
            </div>

            <footer class="app-footer">
                <div class="container text-center py-3">
                    <small class="copyright">
                        Activity Portal · Demo project
                    </small>
                </div>
            </footer>
        </div>

        <script src="{{ asset('portal/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('portal/assets/js/app.js') }}"></script>
    </body>
</html>
