<div class="table-responsive">
    <table class="table app-table-hover mb-0 text-left">
        <thead>
            <tr>
                <th class="cell">Activity</th>
                <th class="cell">Location</th>
                <th class="cell">Period</th>
                <th class="cell">Age</th>
                <th class="cell">Capacity</th>
                <th class="cell text-center"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $activity)
                <tr data-admin-invite-row>
                    <td class="cell">
                        <span class="d-block fw-semibold">{{ $activity->title }}</span>
                    </td>
                    <td class="cell">
                        <span class="d-block">{{ $activity->location_name }}</span>
                        <span class="note">{{ $activity->city ?: 'No city' }}</span>
                    </td>
                    <td class="cell">
                        <span>{{ $activity->period_name ?? 'No period' }}</span>
                        <span class="note">{{ $activity->starts_on?->format('d/m/Y') }} - {{ $activity->ends_on?->format('d/m/Y') }}</span>
                    </td>
                    <td class="cell">
                        {{ $activity->ageGroupLabel() }}
                    </td>
                    <td class="cell">
                        {{ $activity->accepted_registrations_count }} / {{ $activity->capacity }}
                    </td>
                    <td class="cell text-center">
                        <form
                            id="invite-user-{{ $user->id }}-activity-{{ $activity->id }}"
                            method="POST"
                            action="{{ route('admin.registrations.invite') }}"
                            data-admin-invite-form
                            data-user-name="{{ $user->name }}"
                            data-activity-title="{{ $activity->title }}"
                            data-activity-date="{{ $activity->starts_on?->format('d/m/Y') }} - {{ $activity->ends_on?->format('d/m/Y') }}"
                            data-activity-location="{{ $activity->location_name }}{{ $activity->city ? ', '.$activity->city : '' }}"
                        >
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                        </form>
                        <button type="submit" form="invite-user-{{ $user->id }}-activity-{{ $activity->id }}" class="btn-sm app-btn-primary">
                            Invite
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="cell text-center py-4">
                        No matching active activities are available for this user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
