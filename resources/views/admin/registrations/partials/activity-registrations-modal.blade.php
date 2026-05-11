@php($titles = [
    'invited' => 'Invited Users',
    'requested' => 'Registration Requests',
    'accepted' => 'Accepted Participants',
])

<div class="modal-header">
    <div>
        <h5 class="modal-title mb-1">{{ $titles[$status] ?? 'Registrations' }}</h5>
        <div class="small text-muted">{{ $activity->title }} - {{ $activity->starts_on->format('d M Y') }}</div>
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
                    <th class="cell">Visibility</th>
                    <th class="cell">Registered</th>
                    <th class="cell">Status</th>
                    <th class="cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $registration)
                    <tr data-registration-row>
                        <td class="cell fw-semibold">{{ $registration->user->name }}</td>
                        <td class="cell">{{ $registration->user->email }}</td>
                        <td class="cell">
                            <span class="badge {{ $registration->user->is_visible ? 'bg-success' : 'bg-secondary' }}">
                                {{ $registration->user->is_visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="cell">
                            <span>{{ \Illuminate\Support\Carbon::parse($registration->created_at)->format('d M Y') }}</span>
                            <span class="note">{{ \Illuminate\Support\Carbon::parse($registration->created_at)->format('H:i') }}</span>
                        </td>
                        <td class="cell">
                            <span class="badge {{ $registration->status === 'accepted' ? 'bg-success' : ($registration->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                        <td class="cell">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.registrations.show', $registration) }}" class="btn-sm app-btn-secondary">View</a>

                                @if (in_array($registration->status, [\App\Models\Registration::REQUESTED, \App\Models\Registration::INVITED], true))
                                    <form method="POST" action="{{ route('admin.registrations.accept', $registration) }}" data-live-registration-form data-action-type="accept">
                                        @csrf
                                        <button type="submit" class="btn-sm app-btn-primary">Accept</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}" class="d-flex gap-2" data-live-registration-form data-action-type="reject">
                                        @csrf
                                        <input type="hidden" name="comment" value="Rejected from the activity overview modal.">
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="cell text-center text-muted py-4">No users in this category.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
