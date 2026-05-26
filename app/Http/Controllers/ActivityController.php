<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityIndexRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    public function index(ActivityIndexRequest $request)
    {
        $user = $request->user();
        $filters = $request->validated();
        $preference = $user->preference;
        $hasExplicitFilters = false;

        foreach (['cities', 'age_groups', 'period_names', 'match_preferences'] as $key) {
            if ($request->query->has($key)) {
                $hasExplicitFilters = true;
                break;
            }
        }
        $matchPreferences = $hasExplicitFilters
            ? (bool) ($filters['match_preferences'] ?? false)
            : true;

        $filters['match_preferences'] = $matchPreferences;

        $activities = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id))
            ->when(($filters['cities'] ?? []) !== [], fn ($query) => $query->whereIn('city', $filters['cities']))
            ->when(($filters['age_groups'] ?? []) !== [], fn ($query) => $query->whereIn('age_group', $filters['age_groups']))
            ->when(($filters['period_names'] ?? []) !== [], fn ($query) => $query->whereIn('period_name', $filters['period_names']))
            ->when($matchPreferences && $preference, function ($query) use ($preference) {
                $preference->applyToActivityQuery($query);
            })
            ->orderBy('starts_on')
            ->paginate(10)
            ->withQueryString();

        $cities = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id))
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

        $viewData = [
            'activities' => $activities,
            'cities' => $cities,
            'periods' => $periods,
            'ageGroups' => Activity::ageGroups(),
            'filters' => $filters,
            'preference' => $preference,
        ];

        if ($request->expectsJson()) {
            return new JsonResponse([
                'html' => view('activities.partials.results', $viewData)->render(),
            ]);
        }

        return view('activities.index', $viewData);
    }
}
