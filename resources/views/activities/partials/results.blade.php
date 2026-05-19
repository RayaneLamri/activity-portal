<div class="row g-4">
    <div class="col-12">
        <div class="row g-4">
            @forelse ($activities as $activity)
                <div class="col-12">
                    <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                        <div class="app-card-header p-3 border-bottom-0 w-100">
                            <div class="row justify-content-between align-items-start gx-3">
                                <div class="col">
                                    <h4 class="app-card-title mb-1">{{ $activity->title }}</h4>
                                    <div class="text-muted small">{{ $activity->location_name }}{{ $activity->city ? ' - '.$activity->city : '' }}</div>
                                </div>
                                <div class="col-auto text-end small text-muted">
                                    <div>{{ $activity->starts_on->format('d M Y') }} - {{ $activity->ends_on->format('d M Y') }}</div>
                                    <div>Capacity: {{ $activity->remainingCapacity() === null ? 'Unlimited' : $activity->remainingCapacity() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="app-card-body px-4 w-100">
                            <p class="mb-3">{{ \Illuminate\Support\Str::limit($activity->description, 180) }}</p>
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="item border rounded p-3 h-100">
                                        <div class="item-label"><strong>Age range</strong></div>
                                        <div class="item-data">{{ $activity->min_age ?? 'Any' }} - {{ $activity->max_age ?? 'Any' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="item border rounded p-3 h-100">
                                        <div class="item-label"><strong>Accepted</strong></div>
                                        <div class="item-data">{{ $activity->acceptedRegistrationsCount() }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="item border rounded p-3 h-100">
                                        <div class="item-label"><strong>Status</strong></div>
                                        <div class="item-data">{{ $activity->is_active ? 'Open' : 'Inactive' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-card-footer p-4 mt-auto w-100 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <a href="{{ route('activities.show', $activity) }}" class="btn app-btn-secondary">View Details</a>

                            <form method="POST" action="{{ route('my-registrations.store') }}">
                                @csrf
                                <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                <x-primary-button>Request Registration</x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body text-muted">No activities match the selected filters.</div>
                    </div>
                </div>
            @endforelse
        </div>

        <nav class="app-pagination mt-4">
            {{ $activities->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
