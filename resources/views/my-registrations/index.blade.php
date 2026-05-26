<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">My Registrations</h1>
                <div class="text-muted">Review active requests and invitations for upcoming activities.</div>
            </div>
        </div>
    </x-slot>

    @php
        $sections = [
            [
                'title' => 'Invitations',
                'registrations' => $invitedRegistrations,
                'empty' => 'No pending invitations.',
                'show_actions' => true,
            ],
            [
                'title' => 'Requests',
                'registrations' => $requestedRegistrations,
                'empty' => 'No pending requests.',
                'show_actions' => false,
            ],
            [
                'title' => 'Accepted',
                'registrations' => $acceptedRegistrations,
                'empty' => 'No accepted registrations yet.',
                'show_actions' => false,
            ],
            [
                'title' => 'Declined',
                'registrations' => $rejectedRegistrations,
                'empty' => 'No declined registrations.',
                'show_actions' => false,
            ],
        ];

        $visibleSections = collect($sections)->filter(fn ($section) => $section['registrations']->total() > 0);
    @endphp

    <div class="d-flex flex-column gap-4">
        @forelse ($visibleSections as $section)
            <section class="app-card app-card-orders-table shadow-sm">
                <div class="app-card-header px-4 py-3">
                    <h2 class="registration-section-title mb-0">{{ $section['title'] }}</h2>
                </div>

                <div class="app-card-body p-0">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">Activity</th>
                                    <th class="cell">Location</th>
                                    <th class="cell">Period</th>
                                    <th class="cell">Age</th>
                                    <th class="cell">Updated</th>
                                    @if ($section['show_actions'])
                                        <th class="cell text-center"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($section['registrations'] as $registration)
                                    @php
                                        $activity = $registration->activity;
                                        $location = $activity->location_name . ($activity->city ? ' - '.$activity->city : '');
                                        $activityDates = $activity->starts_on?->format('d/m/Y') . ' - ' . $activity->ends_on?->format('d/m/Y');
                                    @endphp

                                    <tr>
                                        <td class="cell fw-semibold">{{ $activity->title }}</td>
                                        <td class="cell">
                                            <span class="d-block">{{ $activity->location_name }}</span>
                                            <span class="note">{{ $activity->city ?: 'No city' }}</span>
                                        </td>
                                        <td class="cell">
                                            <span>{{ $activity->period_name ?? 'No period' }}</span>
                                            <span class="note">{{ $activityDates }}</span>
                                        </td>
                                        <td class="cell">{{ $activity->ageGroupLabel() }}</td>
                                        <td class="cell">
                                            <span>{{ \Illuminate\Support\Carbon::parse($registration->date)->format('d/m/Y') }}</span>
                                            <span class="note">{{ \Illuminate\Support\Carbon::parse($registration->date)->format('H:i') }}</span>
                                        </td>
                                        @if ($section['show_actions'])
                                            <td class="cell">
                                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                    <form
                                                        method="POST"
                                                        action="{{ route('my-registrations.accept-invitation', $registration) }}"
                                                        data-invitation-response-form
                                                        data-action-type="accept"
                                                        data-activity-title="{{ $activity->title }}"
                                                        data-activity-date="{{ $activityDates }}"
                                                        data-activity-location="{{ $location }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="btn-sm app-btn-success">Accept</button>
                                                    </form>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('my-registrations.reject-invitation', $registration) }}"
                                                        data-invitation-response-form
                                                        data-action-type="decline"
                                                        data-activity-title="{{ $activity->title }}"
                                                        data-activity-date="{{ $activityDates }}"
                                                        data-activity-location="{{ $location }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="btn-sm app-btn-danger">Decline</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($section['registrations']->hasPages())
                    <div class="app-card-footer px-4 py-3">
                        <nav class="app-pagination">
                            {{ $section['registrations']->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                @endif
            </section>
        @empty
            <div class="app-card shadow-sm">
                <div class="app-card-body p-5 text-center text-muted">No active registrations.</div>
            </div>
        @endforelse
    </div>
</x-app-layout>
