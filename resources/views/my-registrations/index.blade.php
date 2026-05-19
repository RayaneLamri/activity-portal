<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">My Registrations</h1>
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
                'title' => 'Declined or rejected',
                'registrations' => $rejectedRegistrations,
                'empty' => 'No declined or rejected registrations.',
                'show_actions' => false,
            ],
        ];
    @endphp

    @foreach ($sections as $section)
        <div class="app-card app-card-orders-table shadow-sm mb-4">
            <div class="app-card-header p-3 border-bottom-0">
                <h4 class="app-card-title mb-0">{{ $section['title'] }}</h4>
            </div>
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left">
                        <thead>
                            <tr>
                                <th class="cell">Activity</th>
                                <th class="cell">Location</th>
                                <th class="cell">Dates</th>
                                <th class="cell">Status</th>
                                <th class="cell">Date</th>
                                @if ($section['show_actions'])
                                    <th class="cell">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($section['registrations'] as $registration)
                                @php
                                    $statusClass = $registration->status === \App\Models\Registration::ACCEPTED
                                        ? 'bg-success'
                                        : ($registration->status === \App\Models\Registration::REJECTED ? 'bg-danger' : 'bg-warning');
                                @endphp
                                <tr>
                                    <td class="cell">
                                        <a href="{{ route('activities.show', $registration->activity) }}" class="fw-semibold app-link">
                                            {{ $registration->activity->title }}
                                        </a>
                                    </td>
                                    <td class="cell">{{ $registration->activity->city ?: '-' }}</td>
                                    <td class="cell">{{ $registration->activity->starts_on->format('d M Y') }}</td>
                                    <td class="cell">
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td class="cell">
                                        <span>{{ \Illuminate\Support\Carbon::parse($registration->date)->format('d M Y') }}</span>
                                        <span class="note">{{ \Illuminate\Support\Carbon::parse($registration->date)->format('H:i') }}</span>
                                    </td>
                                    @if ($section['show_actions'])
                                        <td class="cell">
                                            <div class="d-flex flex-wrap gap-2">
                                                <form method="POST" action="{{ route('my-registrations.accept-invitation', $registration) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-sm app-btn-primary">Accept</button>
                                                </form>

                                                <form method="POST" action="{{ route('my-registrations.reject-invitation', $registration) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-sm app-btn-secondary">Decline</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $section['show_actions'] ? 6 : 5 }}" class="cell text-center py-4">{{ $section['empty'] }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($section['registrations']->hasPages())
            <nav class="app-pagination mb-4">
                {{ $section['registrations']->links('pagination::bootstrap-5') }}
            </nav>
        @endif
    @endforeach
</x-app-layout>
