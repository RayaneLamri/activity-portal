<x-app-layout>
    <h1>Admin registrations</h1>

    @foreach ($activities as $activity)
    <section>
        <h2>{{ $activity->title }}</h2>

        <p>
            {{ $activity->city }}
            -
            {{ $activity->starts_on }} to {{ $activity->ends_on }}
        </p>

        <p>
            Capacity:
            {{ $activity->acceptedRegistrationsCount() }} / {{ $activity->capacity }}
        </p>

        <form method="POST" action="{{ route('admin.registrations.invite') }}">
            @csrf

            <input type="hidden" name="activity_id" value="{{ $activity->id }}">

            <select name="user_id" required>
                <option value="">Choose user</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }} - {{ $user->email }}
                </option>
                @endforeach
            </select>

            <input type="text" name="comment" placeholder="Comment">

            <button type="submit">Invite</button>
        </form>

        @if ($activity->registrations->isEmpty())
        <p>No registrations yet.</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Requested at</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($activity->registrations as $registration)
                <tr>
                    <td>{{ $registration->user->name }}</td>
                    <td>{{ $registration->user->email }}</td>
                    <td>{{ $registration->status }}</td>
                    <td>{{ $registration->date }}</td>
                    <td>
                        <a href="{{ route('admin.registrations.show', $registration) }}">
                            View
                        </a>
                        @if ($registration->isRequested() || $registration->isInvited())
                        <form method="POST" action="{{ route('admin.registrations.accept', $registration) }}">
                            @csrf
                            <button type="submit">Accept</button>
                        </form>

                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                            @csrf
                            <button type="submit">Reject</button>
                        </form>
                        @else
                        No action
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </section>
    @endforeach

    {{ $activities->links() }}
</x-app-layout>