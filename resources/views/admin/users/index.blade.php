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
                                <td class="cell">{{ $user->preference?->city ?: 'No saved preference' }}</td>
                                <td class="cell">{{ $user->registrations_count }}</td>
                                <td class="cell">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button
                                            type="button"
                                            class="btn-sm app-btn-primary"
                                            data-user-invite-modal-url="{{ route('admin.users.invite-options', $user) }}"
                                            @disabled(! $user->is_visible)
                                        >
                                            Invite
                                        </button>

                                        <form method="POST" action="{{ route('admin.users.toggle-visibility', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                            <button type="submit" class="btn-sm app-btn-secondary">
                                                {{ $user->is_visible ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                    </div>
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

    <div class="modal fade" id="user-invite-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" data-user-invite-modal-content></div>
        </div>
    </div>
</x-app-layout>
