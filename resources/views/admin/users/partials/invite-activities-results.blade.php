<div class="table-responsive">
    <table class="table app-table-hover mb-0 text-left">
        <thead>
            <tr>
                <th class="cell">Activity</th>
                <th class="cell">Location</th>
                <th class="cell">Dates</th>
                <th class="cell">Age</th>
                <th class="cell">Capacity</th>
                <th class="cell">Comment</th>
                <th class="cell">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $activity)
                <tr>
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
                        {{ $activity->min_age ?? '0' }} - {{ $activity->max_age ?? '99' }}
                    </td>
                    <td class="cell">
                        @if ($activity->capacity === null)
                            Unlimited
                        @else
                            {{ $activity->accepted_registrations_count }} / {{ $activity->capacity }}
                        @endif
                    </td>
                    <td class="cell" style="min-width: 220px;">
                        <form id="invite-user-{{ $user->id }}-activity-{{ $activity->id }}" method="POST" action="{{ route('admin.registrations.invite') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                        </form>
                    </td>
                    <td class="cell">
                        <button type="submit" form="invite-user-{{ $user->id }}-activity-{{ $activity->id }}" class="btn-sm app-btn-primary">
                            Invite
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="cell text-center py-4">
                        No matching active activities are available for this user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>