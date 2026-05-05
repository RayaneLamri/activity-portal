<x-app-layout>
<h1>Activities</h1>

@foreach ($activities as $activity)
    <article>
        <h2>{{ $activity->title }}</h2>

        <p>{{ $activity->city }}</p>
        <p>{{ $activity->starts_on }} - {{ $activity->ends_on }}</p>
        <p>Remaining capacity: {{ $activity->remainingCapacity() }}</p>

        <form method="POST" action="{{ route('my-registrations.store') }}">
            @csrf
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">

            <button type="submit">
                Request registration
            </button>
        </form>
    </article>
@endforeach

{{ $activities->links() }}
</x-app-layout>