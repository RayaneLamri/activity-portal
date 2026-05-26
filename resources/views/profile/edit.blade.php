<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Account</h1>
                <div class="text-muted">
                    {{ $user->isUser() ? 'Manage your profile details and activity matching preferences.' : 'Manage your profile details.' }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="settings-section account-settings">
        <div class="app-card app-card-settings shadow-sm p-4 mb-4">
            <div class="app-card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        @if ($user->isUser())
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    @include('profile.partials.update-preferences-form')
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
