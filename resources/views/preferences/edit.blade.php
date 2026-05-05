<x-app-layout>
    <form method="POST" action="{{ route('preferences.update') }}">
    @csrf
    @method('PUT')

    <select name="preferred_city">
        <option value="">Any city</option>
        @foreach ($cities as $city)
            <option value="{{ $city }}" @selected(old('preferred_city', $preference?->preferred_city) === $city)>
                {{ $city }}
            </option>
        @endforeach
    </select>

    <input
        type="number"
        name="preferred_min_age"
        value="{{ old('preferred_min_age', $preference?->preferred_min_age) }}"
        placeholder="Minimum age"
    >

    <input
        type="number"
        name="preferred_max_age"
        value="{{ old('preferred_max_age', $preference?->preferred_max_age) }}"
        placeholder="Maximum age"
    >

    <input
        type="date"
        name="available_from"
        value="{{ old('available_from', $preference?->available_from) }}"
    >

    <input
        type="date"
        name="available_until"
        value="{{ old('available_until', $preference?->available_until) }}"
    >

    <button type="submit">Save preferences</button>
</form>
</x-app-layout>