<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Registration Events Overview</h1>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-settings shadow-sm p-4 mb-4">
        <div class="app-card-body">
            <form method="GET" action="{{ route('admin.registration-events.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <input id="search" type="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Search user, activity, reference">
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="action" name="action" class="form-select">
                        <option value="">All actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" @selected(($filters['action'] ?? null) === $action)>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <input id="from" type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}" aria-label="From date">
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <input id="until" type="date" name="until" class="form-control" value="{{ $filters['until'] ?? '' }}" aria-label="Until date">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn app-btn-primary">Filter</button>
                </div>

                <div class="col-auto">
                    <a href="{{ route('admin.registration-events.index') }}" class="btn app-btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="app-card app-card-orders-table shadow-sm mb-4">
        <div class="app-card-body">
            <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                    <thead>
                        <tr>
                            <th class="cell">Date</th>
                            <th class="cell">Status</th>
                            <th class="cell">Registration</th>
                            <th class="cell">Activity</th>
                            <th class="cell">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td class="cell">
                                    <span>{{ \Illuminate\Support\Carbon::parse($event->date)->format('d M Y') }}</span>
                                    <span class="note">{{ \Illuminate\Support\Carbon::parse($event->date)->format('H:i') }}</span>
                                </td>
                                <td class="cell">
                                    @php($eventStatus = $event->to_status ?? $event->action)
                                    <span class="badge {{ $eventStatus === 'accepted' ? 'bg-success' : ($eventStatus === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($eventStatus) }}
                                    </span>
                                </td>
                                <td class="cell">
                                    <span class="d-block fw-semibold">{{ $event->registration?->user?->name ?? 'Deleted user' }}</span>
                                    <span class="note">{{ $event->registration?->user?->email ?? '-' }}</span>
                                </td>
                                <td class="cell">
                                    <span class="d-block fw-semibold">{{ $event->registration?->activity?->title ?? 'Deleted activity' }}</span>
                                    <span class="note">{{ $event->registration?->activity?->city ?: 'No city' }}</span>
                                </td>
                                <td class="cell">
                                    @if ($event->registration)
                                        <a href="{{ route('admin.registrations.show', $event->registration) }}" class="btn-sm app-btn-secondary">View</a>
                                    @else
                                        <span class="text-muted">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="cell text-center py-4">No current registration status found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="app-pagination">
        {{ $events->links('pagination::bootstrap-5') }}
    </nav>
</x-app-layout>
