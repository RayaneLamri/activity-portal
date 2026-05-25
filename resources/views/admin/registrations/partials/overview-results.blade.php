<div class="app-card app-card-orders-table shadow-sm mb-4">
    <div class="app-card-body">
        <div class="table-responsive">
            <table class="table app-table-hover mb-0 text-left">
                <thead>
                    <tr>
                        <th class="cell">Activity</th>
                        <th class="cell">Location</th>
                        <th class="cell">Period</th>
                        <th class="cell">Age</th>
                        <th class="cell text-center">Invitations</th>
                        <th class="cell text-center">Requests</th>
                        <th class="cell text-center">Participants</th>
                        <th class="cell text-center">Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        @php($invitedRegistrations = $activity->registrations->where('status', \App\Models\Registration::INVITED)->values())
                        @php($requestedRegistrations = $activity->registrations->where('status', \App\Models\Registration::REQUESTED)->values())
                        @php($acceptedRegistrations = $activity->registrations->where('status', \App\Models\Registration::ACCEPTED)->values())
                        <tr class="{{ $activity->isFull() ? 'activity-row-full' : '' }}" data-activity-row data-activity-id="{{ $activity->id }}" data-capacity="{{ $activity->capacity }}">
                            <td class="cell">
                                <span class="d-block fw-semibold">{{ $activity->title }}</span>
                            </td>
                            <td class="cell">
                                <span class="d-block">{{ $activity->location_name }}</span>
                                <span class="note">{{ $activity->city ?: 'No city' }}</span>
                            </td>
                            <td class="cell">
                                <span>{{ $activity->period_name ?? 'No period' }}</span>
                                <span class="note">{{ $activity->starts_on?->format('d M Y') }} - {{ $activity->ends_on?->format('d M Y') }}</span>
                            </td>
                            <td class="cell">{{ $activity->ageGroupLabel() }}</td>
                            <td class="cell text-center">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="invited">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $invitedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'invited']) }}">
                                        <span data-count-value>{{ $invitedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $invitedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell text-center">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="requested">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $requestedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'requested']) }}">
                                        <span data-count-value>{{ $requestedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $requestedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell text-center">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="accepted">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $acceptedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'accepted']) }}">
                                        <span data-count-value>{{ $acceptedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $acceptedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell text-center">
                                <span data-capacity-label>{{ $acceptedRegistrations->count() }} / {{ $activity->capacity }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="cell text-center py-4">No future activities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav class="app-pagination">
    {{ $activities->links('pagination::bootstrap-5') }}
</nav>
