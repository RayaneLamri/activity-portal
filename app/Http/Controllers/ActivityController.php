<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();
        $search = $request->query('search') ?: null;
        $cities = (array) $request->query('cities', []);
        $periodNames = (array) $request->query('period_names', []);
        $minAge = $request->filled('min_age') ? (int) $request->query('min_age') : null;
        $maxAge = $request->filled('max_age') ? (int) $request->query('max_age') : null;

        $preference = $user->preference;
        $preferredFilters = Activity::filtersForPreference($preference);

        $matchPreferences = $request->hasAny([
            'cities',
            'period_names',
            'min_age',
            'max_age',
            'search',
            'match_preferences',
        ]) ? $request->boolean('match_preferences') : true;

        $filters = [
            'search' => $search,
            'cities' => $cities,
            'period_names' => $periodNames,
            'min_age' => $minAge,
            'max_age' => $maxAge,
            'match_preferences' => $matchPreferences,
        ];

        $activities = Activity::query()
            ->upcoming($today)
            ->available()
            ->withoutUserRegistration($user)
            ->applyFilters($filters)
            ->when($matchPreferences, fn ($query) => $query->applyPreference($preference))
            ->orderBy('starts_on')
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        $cityOptions = Activity::cityOptions(
            Activity::query()
                ->upcoming($today)
                ->available()
                ->withoutUserRegistration($user)
        );

        $periodOptions = Activity::periodOptions(
            Activity::query()
                ->upcoming($today)
                ->available()
                ->withoutUserRegistration($user)
        );

        $data = [
            'activities' => $activities,
            'cities' => $cityOptions,
            'periods' => $periodOptions,
            'filters' => $filters,
            'preferredCities' => $preferredFilters['cities'],
            'preferredPeriodNames' => $preferredFilters['period_names'],
            'preferredMinAge' => $preferredFilters['min_age'] ?? 3,
            'preferredMaxAge' => $preferredFilters['max_age'] ?? 18,
        ];

        if ($request->expectsJson()) {
            return new JsonResponse([
                'html' => view('activities.partials.results', $data)->render(),
            ]);
        }

        return view('activities.index', $data);
    }
}
