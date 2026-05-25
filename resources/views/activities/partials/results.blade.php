<div class="row g-4">
    <div class="col-12">
        <div class="app-card app-card-orders-table shadow-sm">
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left">
                        <thead>
                            <tr>
                                <th class="cell">Activity</th>
                                <th class="cell">Location</th>
                                <th class="cell">Period</th>
                                <th class="cell">Dates</th>
                                <th class="cell">Age</th>
                                <th class="cell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activities as $activity)
                                <tr>
                                    <td class="cell">
                                        <span class="d-block fw-semibold">{{ $activity->title }}</span>
                                        <span class="note">{{ \Illuminate\Support\Str::limit($activity->description, 110) }}</span>
                                    </td>
                                    <td class="cell">
                                        <span class="d-block">{{ $activity->location_name }}</span>
                                        <span class="note">{{ $activity->city ?: 'No city' }}</span>
                                    </td>
                                    <td class="cell">{{ $activity->period_name ?? 'No period' }}</td>
                                    <td class="cell">
                                        <span>{{ $activity->starts_on?->format('d M Y') }}</span>
                                        <span class="note">{{ $activity->ends_on?->format('d M Y') }}</span>
                                    </td>
                                    <td class="cell">{{ $activity->ageGroupLabel() }}</td>
                                    <td class="cell text-end">
                                        <form
                                            method="POST"
                                            action="{{ route('my-registrations.store') }}"
                                            data-registration-request-form
                                            data-activity-title="{{ $activity->title }}"
                                            data-activity-date="{{ $activity->starts_on?->format('d M Y') }} - {{ $activity->ends_on?->format('d M Y') }}"
                                            data-activity-location="{{ $activity->location_name }}{{ $activity->city ? ' - '.$activity->city : '' }}"
                                        >
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                            <button type="submit" class="btn-sm app-btn-primary">Request</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="cell text-center py-4">No activities match the selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <nav class="app-pagination mt-4">
            {{ $activities->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
