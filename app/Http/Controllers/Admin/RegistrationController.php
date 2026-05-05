<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRegistrationIndexRequest;
use App\Models\Activity;
use App\Models\User;

class RegistrationController extends Controller
{
    public function index(AdminRegistrationIndexRequest $request)
    {
        $activities = Activity::query()
        ->with([
            'registrations' => fn ($query) => $query
            ->with('user')
            ->orderBy('date'),
        ])
        ->orderBy('starts_on')
        ->paginate(12);

        $users = User::query()
        ->where('role', 'user')
        ->where('is_visible', true)
        ->orderBy('name')
        ->get();

        return view('admin.registrations.index', [
            'activities' => $activities,
            'users' => $users,
        ]);
    }
}