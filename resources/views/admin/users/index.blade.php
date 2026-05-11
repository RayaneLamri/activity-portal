<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Users</h1>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-orders-table shadow-sm mb-4">
        <div class="app-card-body">
            <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                    <thead>
                        <tr>
                            <th class="cell">User</th>
                            <th class="cell">Visibility</th>
                            <th class="cell">Preference</th>
                            <th class="cell">Registrations</th>
                            <th class="cell">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="cell">
                                    <span class="d-block fw-semibold">{{ $user->name }}</span>
                                    <span class="note">{{ $user->email }}</span>
                                </td>
                                <td class="cell">
                                    <span class="badge {{ $user->is_visible ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->is_visible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="cell">{{ $user->preference?->preferred_city ?: 'No saved preference' }}</td>
                                <td class="cell">{{ $user->registrations_count }}</td>
                                <td class="cell">
                                    <form method="POST" action="{{ route('admin.users.toggle-visibility', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-sm app-btn-secondary">
                                            {{ $user->is_visible ? 'Hide' : 'Show' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="app-pagination">
        {{ $users->links('pagination::bootstrap-5') }}
    </nav>
</x-app-layout>
