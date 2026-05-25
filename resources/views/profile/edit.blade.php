<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Account</h1>
            </div>
        </div>
    </x-slot>

    <div class="row g-4 settings-section align-items-start">
        <div class="col-12 col-xl-4">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="profile-initials">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">{{ $user->role }}</div>
                            <h2 class="h5 mb-1">{{ $user->name }}</h2>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    @if ($user->isUser())
                        <div class="border-top pt-4">
                            <h3 class="h6 mb-3">Current preferences</h3>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Cities</div>
                                <div>{{ $preference?->citySummary() ?? 'Any city' }}</div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Age groups</div>
                                <div>
                                    @forelse ($preference?->ageGroupList() ?? [] as $ageGroup)
                                        <span class="d-block">{{ \App\Models\Activity::ageGroupLabelFor($ageGroup) }}</span>
                                    @empty
                                        Any age
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Periods</div>
                                <div>
                                    @forelse ($preference?->periodNameList() ?? [] as $period)
                                        <span class="d-block">{{ $period }}</span>
                                    @empty
                                        Any period
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    <div class="pb-4 mb-4 border-bottom">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    @if ($user->isUser())
                        @include('profile.partials.update-preferences-form')
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
