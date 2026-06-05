@php($titles = [
    \App\Models\Registration::INVITED => 'Invited Users',
    \App\Models\Registration::REQUESTED => 'Registration Requests',
    \App\Models\Registration::ACCEPTED => 'Accepted Participants',
])
@php($showActions = $status == \App\Models\Registration::REQUESTED)

<div class="modal-header">
    <div>
        <h5 class="modal-title mb-1">{{ $titles[$status] ?? 'Registrations' }}</h5>
        <div class="small text-muted">{{ $activity->title }} - {{ $activity->period_name ?? 'No period' }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="table-responsive">
        <table class="table app-table-hover mb-0 text-left">
            <thead>
                <tr>
                    <th class="cell">User</th>
                    <th class="cell">Email</th>
                    <th class="cell">Date</th>
                    @if ($showActions)
                        <th class="cell text-center"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $registration)
                    <tr data-registration-row>
                        <td class="cell fw-semibold">{{ $registration->user->name }}</td>
                        <td class="cell">{{ $registration->user->email }}</td>
                        <td class="cell">
                            <span>{{ \Illuminate\Support\Carbon::parse($registration->date)->format('d/m/Y') }}</span>
                            <span class="note">{{ \Illuminate\Support\Carbon::parse($registration->date)->format('H:i') }}</span>
                        </td>
                        @if ($showActions)
                            <td class="cell text-center">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    @if ($registration->status === \App\Models\Registration::REQUESTED)
                                        <form method="POST" action="{{ route('admin.registrations.accept', $registration) }}" data-live-registration-form data-action-type="accept">
                                            @csrf
                                            <button type="submit" class="btn-sm app-btn-success">Accept</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}" data-live-registration-form data-action-type="reject">
                                            @csrf
                                            <button type="submit" class="btn-sm app-btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $showActions ? 5 : 4 }}" class="cell text-center text-muted py-4">No users in this category.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
