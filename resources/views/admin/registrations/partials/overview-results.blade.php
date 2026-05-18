<div class="app-card app-card-orders-table shadow-sm mb-4">
    <div class="app-card-body">
        <div class="table-responsive">
            <table class="table app-table-hover mb-0 text-left">
                <thead>
                    <tr>
                        <th class="cell">Activity</th>
                        <th class="cell">Location</th>
                        <th class="cell">Dates</th>
                        <th class="cell">Invitations</th>
                        <th class="cell">Demandes</th>
                        <th class="cell">Participants / Acceptés</th>
                        <th class="cell">Capacity</th>
                        <th class="cell">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        @php($invitedRegistrations = $activity->registrations->where('status', \App\Models\Registration::INVITED)->values())
                        @php($requestedRegistrations = $activity->registrations->where('status', \App\Models\Registration::REQUESTED)->values())
                        @php($acceptedRegistrations = $activity->registrations->where('status', \App\Models\Registration::ACCEPTED)->values())
                        @php($remainingCapacity = $activity->remainingCapacity())
                        <tr data-activity-row data-activity-id="{{ $activity->id }}" data-capacity="{{ $activity->capacity ?? '' }}">
                            <td class="cell">
                                <span class="d-block fw-semibold">{{ $activity->title }}</span>
                                <span class="note">{{ $activity->external_reference }}</span>
                            </td>
                            <td class="cell">
                                <span class="d-block">{{ $activity->location_name }}</span>
                                <span class="note">{{ $activity->city ?: 'No city' }}</span>
                            </td>
                            <td class="cell">
                                <span>{{ $activity->starts_on->format('d M Y') }}</span>
                                <span class="note">{{ $activity->ends_on->format('d M Y') }}</span>
                            </td>
                            <td class="cell">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="invited">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $invitedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'invited']) }}">
                                        <span data-count-value>{{ $invitedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $invitedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="requested">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $requestedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'requested']) }}">
                                        <span data-count-value>{{ $requestedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $requestedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell">
                                <span data-count-container data-activity-id="{{ $activity->id }}" data-status="accepted">
                                    <button type="button" class="btn-sm app-btn-secondary {{ $acceptedRegistrations->count() > 0 ? '' : 'd-none' }}" data-count-trigger data-registration-modal-url="{{ route('admin.activities.registrations', [$activity, 'accepted']) }}">
                                        <span data-count-value>{{ $acceptedRegistrations->count() }}</span>
                                    </button>
                                    <span class="text-muted {{ $acceptedRegistrations->count() > 0 ? 'd-none' : '' }}" data-count-zero>0</span>
                                </span>
                            </td>
                            <td class="cell">
                                @if ($activity->capacity === null)
                                    <span data-capacity-label>Unlimited</span>
                                @else
                                    <span data-capacity-label>{{ $acceptedRegistrations->count() }} / {{ $activity->capacity }}</span>
                                    <span class="note" data-capacity-note>{{ $remainingCapacity }} remaining</span>
                                @endif
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