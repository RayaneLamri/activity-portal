<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRegistrationIndexRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('external_reference', 'like', "%{$search}%")
                ->orWhere('location_name', 'like', "%{$search}%");
            });
        })
        ->when($request->filled('city'), function ($query) use ($request) {
            $query->where('city', $request->input('city'));
        })
        ->when($request->filled('from'), function ($query) use ($request) {
            $query->whereDate('starts_on', '>=', $request->date('from'));
        })
        ->when($request->filled('until'), function ($query) use ($request) {
            $query->whereDate('starts_on', '<=', $request->date('until'));
        })
        ->when($request->input('activity_status') === 'active', function ($query) {
            $query->where('is_active', true);
        })
        ->when($request->input('activity_status') === 'inactive', function ($query) {
            $query->where('is_active', false);
        })
        ->orderBy('starts_on')
        ->orderBy('title')
        ->paginate(12)
        ->withQueryString();

        $cities = Activity::query()
        ->whereNotNull('city')
        ->distinct()
        ->orderBy('city')
        ->pluck('city');

        $users = User::query()
        ->where('role', 'user')
        ->where('is_visible', true)
        ->orderBy('name')
        ->get();

        $data = [
            'activities' => $activities,
            'cities' => $cities,
            'filters' => $request->validated(),
            'users' => $users,
        ];

        if ($request->expectsJson()) {
            return new JsonResponse([
                'html' => view('admin.registrations.partials.overview-results', $data)->render(),
            ]);
        }

        return view('admin.registrations.index', $data);
    }

    public function activityRegistrations(Activity $activity, string $status): JsonResponse
    {
        abort_unless(in_array($status, [
            Registration::INVITED,
            Registration::REQUESTED,
            Registration::ACCEPTED,
        ], true), 404);

        $activity->load([
            'registrations' => fn ($query) => $query
                ->where('status', $status)
                ->with('user')
                ->orderBy('created_at'),
        ]);

        return new JsonResponse([
            'html' => view('admin.registrations.partials.activity-registrations-modal', [
                'activity' => $activity,
                'status' => $status,
                'registrations' => $activity->registrations,
            ])->render(),
        ]);
    }

    public function show(Registration $registration)
    {
        $registration->load([
            'user',
            'activity',
            'events.user',
        ]);

        return view('admin.registrations.show', [
            'registration' => $registration,
        ]);
    }
}