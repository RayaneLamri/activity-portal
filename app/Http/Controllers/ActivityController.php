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

        $activities = Activity::query()
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->input('city'))
            )
            ->when($request->filled('age'), function ($query) use ($request) {
                $age = (int) $request->input('age');

                $query
                    ->where('min_age', '<=', $age)
                    ->where('max_age', '>=', $age);
            })
            ->when($request->filled('from'), fn ($query) => $query->whereDate('ends_on', '>=', $request->date('from'))
            )
            ->when($request->filled('until'), fn ($query) => $query->whereDate('starts_on', '<=', $request->date('until'))
            )
            ->when($request->boolean('match_preferences') && $preference, function ($query) use ($preference) {
                if ($preference->city) {
                    $query->where('city', $preference->city);
                }

                if ($preference->min_age !== null) {
                    $query->where('max_age', '>=', $preference->min_age);
                }

                if ($preference->max_age !== null) {
                    $query->where('min_age', '<=', $preference->max_age);
                }

                if ($preference->starts_on) {
                    $query->whereDate('ends_on', '>=', $preference->starts_on);
                }

                if ($preference->ends_on) {
                    $query->whereDate('starts_on', '<=', $preference->ends_on);
                }
            })
            ->orderBy('starts_on')
            ->paginate(10)
            ->withQueryString();

        $cities = Activity::query()
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id))
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $viewData = [
            'activities' => $activities,
            'cities' => $cities,
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

    public function show(Activity $activity)
    {
        $existingRegistration = request()->user()
            ->registrations()
            ->where('activity_id', $activity->id)
            ->first();

        return view('activities.show', [
            'activity' => $activity,
            'existingRegistration' => $existingRegistration,
        ]);
    }
}
