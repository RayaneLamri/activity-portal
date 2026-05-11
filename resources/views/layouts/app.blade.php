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
        <link id="theme-style" rel="stylesheet" href="{{ asset('portal/assets/css/portal.css') }}">
        @vite(['resources/js/app.js'])
    </head>

    <body
        class="app"
        data-flash-success="{{ e(session('success') ?? session('status') ?? '') }}"
        data-flash-warning="{{ e(session('warning') ?? '') }}"
        data-flash-error="{{ e(session('error') ?? '') }}"
        data-validation-errors="{{ e(json_encode($errors->all())) }}"
    >
        @include('layouts.navigation')

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
                        Designed with <span class="sr-only">love</span>
                        <i class="fas fa-heart" style="color: #fb866a;"></i>
                        by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank" rel="noreferrer">Xiaoying Riley</a> for developers
                    </small>
                </div>
            </footer>
        </div>

        <script src="{{ asset('portal/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('portal/assets/js/app.js') }}"></script>
    </body>
</html>