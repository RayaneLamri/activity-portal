<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationIndexRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    public function index(AdminRegistrationIndexRequest $request)
    {
        $filters = $request->validated();

        $activities = Activity::query()
            ->with([
                'registrations' => fn ($query) => $query
                    ->with('user')
                    ->orderBy('date'),
            ])
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('external_reference', 'like', "%{$search}%")
                        ->orWhere('location_name', 'like', "%{$search}%");
                });
            })
            ->when(($filters['cities'] ?? []) !== [], fn ($query) => $query->whereIn('city', $filters['cities']))
            ->when(($filters['period_names'] ?? []) !== [], fn ($query) => $query->whereIn('period_name', $filters['period_names']))
            ->when(($filters['age_groups'] ?? []) !== [], fn ($query) => $query->whereIn('age_group', $filters['age_groups']))
            ->orderBy('starts_on')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $cities = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $periods = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->orderBy('starts_on')
            ->pluck('period_name')
            ->filter()
            ->unique()
            ->values();

        $users = User::query()
            ->where('role', 'user')
            ->where('is_visible', true)
            ->orderBy('name')
            ->get();

        $data = [
            'activities' => $activities,
            'cities' => $cities,
            'periods' => $periods,
            'ageGroups' => Activity::ageGroups(),
            'filters' => $filters,
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
                ->orderBy('date'),
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
            'events',
        ]);

        return view('admin.registrations.show', [
            'registration' => $registration,
        ]);
    }
}
