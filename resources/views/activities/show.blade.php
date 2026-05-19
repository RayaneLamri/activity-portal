<x-app-layout>
<h1>{{ $activity->title }}</h1>

<p>{{ $activity->description }}</p>
<p>Location: {{ $activity->location_name }}</p>
<p>City: {{ $activity->city }}</p>
<p>Dates: {{ $activity->starts_on }} - {{ $activity->ends_on }}</p>
<p>Age: {{ $activity->min_age }} - {{ $activity->max_age }}</p>
<p>Remaining places: {{ $activity->remainingCapacity() }}</p>

@if ($existingRegistration)
    <p>Your registration status: {{ $existingRegistration->status }}</p>

    @if ($existingRegistration->status === \App\Models\Registration::INVITED)
        <div class="d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('my-registrations.accept-invitation', $existingRegistration) }}">
                @csrf
                <button type="submit" class="btn app-btn-primary">Accept invitation</button>
            </form>

            <form method="POST" action="{{ route('my-registrations.reject-invitation', $existingRegistration) }}">
                @csrf
                <button type="submit" class="btn app-btn-secondary">Decline invitation</button>
            </form>
        </div>
    @endif
@else
    <form method="POST" action="{{ route('my-registrations.store') }}">
        @csrf
        <input type="hidden" name="activity_id" value="{{ $activity->id }}">

        <button type="submit">
            Request registration
        </button>
    </form>
@endif
</x-app-layout>
