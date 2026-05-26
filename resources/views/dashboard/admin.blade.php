<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Overview</h1>
                <div class="text-muted">Monitor upcoming activity registrations and user follow-up.</div>
            </div>
        </div>
    </x-slot>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">1. Registrations</h3>
                    <div class="text-muted mb-3">Review requests, invitations and decisions.</div>
                    <a class="btn app-btn-secondary" href="{{ route('admin.registrations.index') }}">Review registrations</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">2. Users</h3>
                    <div class="text-muted mb-3">Check preferences and send targeted invitations.</div>
                    <a class="btn app-btn-secondary" href="{{ route('admin.users.index') }}">Manage users</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">3. History</h3>
                    <div class="text-muted mb-3">Review status changes across the workflow.</div>
                    <a class="btn app-btn-secondary" href="{{ route('admin.registration-events.index') }}">View history</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <div class="app-card app-card-orders-table shadow-sm h-100">
                <div class="app-card-header p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h4 class="app-card-title">Upcoming Activities</h4>
                        </div>
                        <div class="col-auto">
                            <div class="card-header-action">
                                <a href="{{ route('admin.registrations.index') }}">View all</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-card-body p-0">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">Activity</th>
                                    <th class="cell">Period</th>
                                    <th class="cell text-center">Requests</th>
                                    <th class="cell text-center">Invited</th>
                                    <th class="cell text-center">Accepted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($upcomingActivities as $activity)
                                    <tr>
                                        <td class="cell fw-semibold">
                                            <span class="d-block">{{ $activity->title }}</span>
                                            <span class="note">{{ $activity->city ?: 'No city' }}</span>
                                        </td>
                                        <td class="cell">
                                            <span>{{ $activity->period_name ?? 'No period' }}</span>
                                            <span class="note">{{ $activity->starts_on?->format('d/m/Y') }} - {{ $activity->ends_on?->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="cell text-center">{{ $activity->requested_count }}</td>
                                        <td class="cell text-center">{{ $activity->invited_count }}</td>
                                        <td class="cell text-center">{{ $activity->accepted_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="cell text-muted" colspan="5">No upcoming activities.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="app-card app-card-orders-table shadow-sm h-100">
                <div class="app-card-header p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h4 class="app-card-title">Recent Registration Activity</h4>
                        </div>
                        <div class="col-auto">
                            <div class="card-header-action">
                                <a href="{{ route('admin.registration-events.index') }}">View history</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-card-body p-0">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">User</th>
                                    <th class="cell text-center">Status</th>
                                    <th class="cell">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentRegistrations as $registration)
                                    <tr>
                                        <td class="cell fw-semibold">
                                            <span class="d-block">{{ $registration->user->name }}</span>
                                            <span class="note">{{ $registration->activity->title }}</span>
                                        </td>
                                        <td class="cell text-center">
                                            <span class="badge {{ $registration->statusBadgeClass() }}">
                                                {{ $registration->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="cell">
                                            <span>{{ $registration->date?->format('d/m/Y') }}</span>
                                            <span class="note">{{ $registration->date?->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="cell text-muted" colspan="3">No recent registration activity.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
