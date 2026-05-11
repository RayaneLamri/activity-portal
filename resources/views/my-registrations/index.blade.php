<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">My Registrations</h1>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-orders-table shadow-sm mb-4">
        <div class="app-card-body">
            <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                    <thead>
                        <tr>
                            <th class="cell">Activity</th>
                            <th class="cell">Location</th>
                            <th class="cell">Dates</th>
                            <th class="cell">Status</th>
                            <th class="cell">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $registration)
                            <tr>
                                <td class="cell">
                                    <a href="{{ route('activities.show', $registration->activity) }}" class="fw-semibold app-link">
                                        {{ $registration->activity->title }}
                                    </a>
                                </td>
                                <td class="cell">{{ $registration->activity->city ?: '-' }}</td>
                                <td class="cell">{{ $registration->activity->starts_on->format('d M Y') }}</td>
                                <td class="cell">
                                    <span class="badge {{ $registration->status === 'accepted' ? 'bg-success' : ($registration->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                                <td class="cell">
                                    <span>{{ \Illuminate\Support\Carbon::parse($registration->created_at)->format('d M Y') }}</span>
                                    <span class="note">{{ \Illuminate\Support\Carbon::parse($registration->created_at)->format('H:i') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="cell text-center py-4">No registrations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="app-pagination">
        {{ $registrations->links('pagination::bootstrap-5') }}
    </nav>
</x-app-layout>