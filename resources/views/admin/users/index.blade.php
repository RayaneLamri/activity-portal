<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Users</h1>
                <div class="text-muted">Manage user visibility and send targeted activity invitations.</div>
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
                            <th class="cell text-center">Visible</th>
                            <th class="cell text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="cell">
                                    <span class="d-block fw-semibold">{{ $user->name }}</span>
                                    <span class="note">{{ $user->email }}</span>
                                </td>
                                <td class="cell text-center">
                                    <form method="POST" action="{{ route('admin.users.toggle-visibility', $user) }}" class="d-inline-flex justify-content-center">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch m-0">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                aria-label="Toggle visibility for {{ $user->name }}"
                                                @checked($user->is_visible)
                                                onchange="this.form.submit()"
                                            >
                                        </div>
                                    </form>
                                </td>
                                <td class="cell text-center">
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <button
                                            type="button"
                                            class="btn-sm app-btn-primary"
                                            data-user-invite-modal-url="{{ route('admin.users.invite-options', $user) }}"
                                            @disabled(! $user->is_visible)
                                        >
                                            Invite
                                        </button>
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
