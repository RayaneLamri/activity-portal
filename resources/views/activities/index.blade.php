<x-app-layout>
<h1>Activities</h1>

<form method="GET" action="{{ route('activities.index') }}">
    <select name="city">
        <option value="">All cities</option>
        @foreach ($cities as $city)
            <option value="{{ $city }}" @selected(request('city') === $city)>
                {{ $city }}
            </option>
        @endforeach
    </select>

    <input type="number" name="age" value="{{ request('age') }}" placeholder="Age">

    <input type="date" name="from" value="{{ request('from') }}">
    <input type="date" name="until" value="{{ request('until') }}">

    <label>
        <input type="checkbox" name="match_preferences" value="1" @checked(request()->boolean('match_preferences'))>
        Match my preferences
    </label>

    <button type="submit">Filter</button>

    <a href="{{ route('activities.index') }}">Reset</a>
</form>

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