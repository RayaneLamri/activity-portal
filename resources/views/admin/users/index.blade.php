<x-app-layout>
    <h1>Users</h1>

    @foreach ($users as $user)
        <section>
            <h2>{{ $user->name }}</h2>

            <p>{{ $user->email }}</p>

            <p>
                Visibility:
                {{ $user->is_visible ? 'Visible' : 'Hidden' }}
            </p>

            <p>
                Registrations:
                {{ $user->registrations_count }}
            </p>

            @if ($user->preference)
                <p>Preferred city: {{ $user->preference->preferred_city ?? 'Any' }}</p>
                <p>Age: {{ $user->preference->preferred_min_age }} - {{ $user->preference->preferred_max_age }}</p>
                <p>Available: {{ $user->preference->available_from }} to {{ $user->preference->available_until }}</p>
            @else
                <p>No preferences yet.</p>
            @endif

            <form method="POST" action="{{ route('admin.users.toggle-visibility', $user) }}">
                @csrf
                @method('PATCH')

                <button type="submit">
                    {{ $user->is_visible ? 'Hide' : 'Show' }}
                </button>
            </form>
        </section>
    @endforeach

    {{ $users->links() }}
</x-app-layout>