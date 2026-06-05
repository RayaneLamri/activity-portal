<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function toggleActive(User $user)
    {
        abort_unless($user->isUser(), 404);

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User activity updated.');
    }

    public function inviteOptions(User $user, Request $request)
    {
        abort_unless($user->isUser(), 404);

        $user->load('preference');

        $preference = $user->preference;
        $preferredFilters = Activity::filtersForPreference($preference);

        $hasFilterQuery = $request->hasAny([
            'cities',
            'period_names',
            'min_age',
            'max_age',
            'search',
            'match_preferences',
        ]);

        $filters = [
            'search' => $hasFilterQuery ? ($request->query('search') ?: null) : null,
            'cities' => $hasFilterQuery ? (array) $request->query('cities', []) : $preferredFilters['cities'],
            'period_names' => $hasFilterQuery ? (array) $request->query('period_names', []) : $preferredFilters['period_names'],
            'min_age' => $hasFilterQuery ? (int) $request->query('min_age', 3) : ($preferredFilters['min_age'] ?? 3),
            'max_age' => $hasFilterQuery ? (int) $request->query('max_age', 18) : ($preferredFilters['max_age'] ?? 18),
            'match_preferences' => $hasFilterQuery ? $request->boolean('match_preferences') : true,
        ];

        $today = now()->toDateString();
        $queryFilters = $hasFilterQuery ? [
            'search' => $filters['search'],
            'cities' => $filters['cities'],
            'period_names' => $filters['period_names'],
            'min_age' => $filters['min_age'] !== 3 ? $filters['min_age'] : null,
            'max_age' => $filters['max_age'] !== 18 ? $filters['max_age'] : null,
        ] : [];

        $activities = Activity::query()
            ->withCount([
                'registrations as accepted_registrations_count' => fn ($query) => $query
                    ->where('status', Registration::ACCEPTED),
            ])
            ->available()
            ->upcoming($today)
            ->withoutUserRegistration($user)
            ->applyFilters($queryFilters)
            ->when($filters['match_preferences'], fn ($query) => $query->applyPreference($preference))
            ->orderBy('starts_on')
            ->orderBy('title')
            ->limit(20)
            ->get();

        $availableCities = Activity::cityOptions(
            Activity::query()
                ->available()
                ->upcoming($today)
                ->withoutUserRegistration($user)
        );

        $availablePeriods = Activity::periodOptions(
            Activity::query()
                ->available()
                ->upcoming($today)
                ->withoutUserRegistration($user)
        );

        $viewData = [
            'user' => $user,
            'activities' => $activities,
            'filters' => $filters,
            'preferredCities' => $preferredFilters['cities'],
            'preferredPeriodNames' => $preferredFilters['period_names'],
            'preferredMinAge' => $preferredFilters['min_age'] ?? 3,
            'preferredMaxAge' => $preferredFilters['max_age'] ?? 18,
        ];

        if ($request->boolean('results_only')) {
            return new JsonResponse([
                'html' => view('admin.users.partials.invite-activities-results', $viewData)->render(),
            ]);
        }

        return new JsonResponse([
            'html' => view('admin.users.partials.invite-activities-modal', [
                ...$viewData,
                'cities' => $availableCities,
                'periods' => $availablePeriods,
            ])->render(),
        ]);
    }
}
