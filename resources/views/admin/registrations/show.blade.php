<x-app-layout>
    <h1>Registration detail</h1>

    <section>
        <h2>User</h2>
        <p>{{ $registration->user->name }}</p>
        <p>{{ $registration->user->email }}</p>
        <p>{{ $registration->user->is_visible ? 'Visible' : 'Hidden' }}</p>
    </section>

    <section>
        <h2>Activity</h2>
        <p>{{ $registration->activity->title }}</p>
        <p>{{ $registration->activity->city }}</p>
        <p>{{ $registration->activity->starts_on }} to {{ $registration->activity->ends_on }}</p>
    </section>

    <section>
        <h2>Status</h2>
        <p>{{ $registration->status }}</p>
        <p>Created at: {{ $registration->date }}</p>
    </section>

    <section>
        <h2>History</h2>

        @if ($registration->events->isEmpty())
            <p>No events yet.</p>
        @else
            <ul>
                @foreach ($registration->events as $event)
                    <li>
                        <strong>{{ $event->action }}</strong>
                        by {{ $event->user->name ?? 'Unknown' }}
                        on {{ $event->date }}

                        @if ($event->comment)
                            <br>
                            {{ $event->comment }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <p>
        <a href="{{ route('admin.registrations.index') }}">
            Back to registrations
        </a>
    </p>
</x-app-layout>