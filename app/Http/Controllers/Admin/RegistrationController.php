<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService,
    ) {}

    public function index(Request $request)
    {
        $today = now()->toDateString();
        $cities = (array) $request->query('cities', []);
        $periodNames = (array) $request->query('period_names', []);
        $minAge = $request->filled('min_age') ? (int) $request->query('min_age') : null;
        $maxAge = $request->filled('max_age') ? (int) $request->query('max_age') : null;
        $search = $request->query('search') ?: null;

        $filters = [
            'search' => $search,
            'cities' => $cities,
            'period_names' => $periodNames,
            'min_age' => $minAge,
            'max_age' => $maxAge,
        ];

        $activities = Activity::with([
            'registrations' => fn ($query) => $query->with('user')->orderBy('date'),
        ])
            ->upcoming($today)
            ->applyFilters($filters)
            ->orderBy('starts_on')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $cities = Activity::cityOptions(
            Activity::query()->upcoming($today)
        );

        $periods = Activity::periodOptions(
            Activity::query()->upcoming($today)
        );

        $users = User::query()
        ->where('role', 'user')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        $data = [
            'activities' => $activities,
            'cities' => $cities,
            'periods' => $periods,
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
        abort_if($status === Registration::REJECTED, 404);
        abort_unless(in_array($status, Registration::statuses(), true), 404);

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

    public function invite(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $activity = Activity::findOrFail($validated['activity_id']);

        $this->registrationService->createInvite($user, $activity);

        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => 'Invitation sent.',
                'activity_id' => $activity->id,
                'user_id' => $user->id,
            ]);
        }

        return redirect()
            ->back()
            ->with('status', 'User invited to activity.');
    }

    public function accept(Registration $registration)
    {
        $this->registrationService->accept($registration);

        if (request()->expectsJson()) {
            return new JsonResponse([
                'message' => 'Registration accepted.',
                'registration_id' => $registration->id,
                'activity_id' => $registration->activity_id,
                'status' => $registration->status,
            ]);
        }

        return redirect()
            ->route('admin.registrations.index')
            ->with('status', 'Registration accepted.');
    }

    public function reject(Registration $registration)
    {
        $this->registrationService->reject($registration);

        if (request()->expectsJson()) {
            return new JsonResponse([
                'message' => 'Registration rejected.',
                'registration_id' => $registration->id,
                'activity_id' => $registration->activity_id,
                'status' => $registration->status,
            ]);
        }

        return redirect()
            ->route('admin.registrations.index')
            ->with('status', 'Registration rejected.');
    }
}
