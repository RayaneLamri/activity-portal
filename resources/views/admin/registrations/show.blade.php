<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Registration History</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.registrations.index') }}" class="btn app-btn-secondary">Back to overview</a>
            </div>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                <div class="app-card-header p-3 border-bottom-0">
                    <h4 class="app-card-title">Registration Summary</h4>
                </div>
                <div class="app-card-body px-4 w-100">
                    <div class="item border-bottom py-3">
                        <div class="item-label"><strong>User</strong></div>
                        <div class="item-data">{{ $registration->user->name }}</div>
                        <div class="text-muted small">{{ $registration->user->email }}</div>
                    </div>
                    <div class="item border-bottom py-3">
                        <div class="item-label"><strong>Activity</strong></div>
                        <div class="item-data">{{ $registration->activity->title }}</div>
                        <div class="text-muted small">{{ $registration->activity->city ?: 'No city' }}</div>
                    </div>
                    <div class="item border-bottom py-3">
                        <div class="item-label"><strong>Current status</strong></div>
                        <div class="item-data">
                            <span class="badge {{ $registration->status === 'accepted' ? 'bg-success' : ($registration->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="item py-3">
                        <div class="item-label"><strong>Created</strong></div>
                        <div class="item-data">{{ \Illuminate\Support\Carbon::parse($registration->created_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    <h4 class="app-card-title mb-3">Timeline</h4>
                    @forelse ($registration->events as $event)
                        <div class="item border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold text-uppercase small">
                                        {{ ucfirst($event->to_status ?? $event->action) }}
                                    </div>
                                    <div class="text-muted small">{{ $event->user?->name ?? 'System' }}</div>
                                </div>
                                <div class="text-muted small">{{ \Illuminate\Support\Carbon::parse($event->created_at)->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No admin history yet for this registration.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
