<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRegistrationIndexRequest;
use App\Models\Activity;

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

        return view('admin.registrations.index', [
            'activities' => $activities,
        ]);
    }
}