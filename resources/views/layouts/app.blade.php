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
        <style>
            .select2-container--default .select2-selection--multiple {
                min-height: 38px;
                border-color: #ced4da;
            }

            .age-range-filter {
                padding: 0.5rem 0.25rem 0;
            }

            .age-range-filter__header {
                display: flex;
                justify-content: space-between;
                gap: 1rem;
                font-size: 0.875rem;
                color: #6c757d;
                margin-bottom: 0.75rem;
            }

            .age-range-filter__value {
                font-weight: 600;
                color: #212529;
            }

            .age-range-filter__slider {
                margin: 0 0.35rem 0.85rem;
            }

            .age-range-filter__slider.ui-slider {
                height: 0.35rem;
                border: 0;
                background: #dee2e6;
            }

            .age-range-filter__slider .ui-slider-range {
                background: #4f46e5;
            }

            .age-range-filter__slider .ui-slider-handle {
                width: 1rem;
                height: 1rem;
                border-radius: 999px;
                border: 2px solid #4f46e5;
                background: #fff;
                top: -0.35rem;
                cursor: pointer;
            }

            .age-range-filter__ends {
                display: flex;
                justify-content: space-between;
                font-size: 0.75rem;
                color: #6c757d;
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
