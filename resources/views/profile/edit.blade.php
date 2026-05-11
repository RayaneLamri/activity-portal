<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Account</h1>
            </div>
        </div>
    </x-slot>

    <div class="row g-4 settings-section">
        <div class="col-12 col-lg-8">
            <div class="app-card app-card-settings shadow-sm p-4 mb-4">
                <div class="app-card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>