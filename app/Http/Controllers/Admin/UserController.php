<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('role', 'user')
            ->with('preference')
            ->withCount('registrations')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function toggleVisibility(User $user)
    {
        abort_unless($user->isUser(), 404);

        $user->update([
            'is_visible' => ! $user->is_visible,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User visibility updated.');
    }
}
