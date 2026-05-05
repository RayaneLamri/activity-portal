<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Http\Requests\ActivityIndexRequest;

class ActivityController extends Controller
{
    public function index(ActivityIndexRequest $request)
    {
        $user = $request->user();
        $preference = $user->preference;

        $activities = Activity::query()
        ->where('is_active', true)
        ->when($request->filled('city'), fn ($query) =>
            $query->where('city', $request->input('city'))
        )
        ->when($request->filled('age'), function ($query) use ($request) {
            $age = (int) $request->input('age');

            $query
            ->where('min_age', '<=', $age)
            ->where('max_age', '>=', $age);
        })
        ->when($request->filled('from'), fn ($query) =>
            $query->whereDate('ends_on', '>=', $request->date('from'))
        )
        ->when($request->filled('until'), fn ($query) =>
            $query->whereDate('starts_on', '<=', $request->date('until'))
        )
        ->when($request->boolean('match_preferences') && $preference, function ($query) use ($preference) {
            if ($preference->preferred_city) {
                $query->where('city', $preference->preferred_city);
            }

            if ($preference->preferred_min_age !== null) {
                $query->where('max_age', '>=', $preference->preferred_min_age);
            }

            if ($preference->preferred_max_age !== null) {
                $query->where('min_age', '<=', $preference->preferred_max_age);
            }

            if ($preference->available_from) {
                $query->whereDate('ends_on', '>=', $preference->available_from);
            }

            if ($preference->available_until) {
                $query->whereDate('starts_on', '<=', $preference->available_until);
            }
        })
        ->orderBy('starts_on')
        ->paginate(10)
        ->withQueryString();

        $cities = Activity::query()
        ->where('is_active', true)
        ->distinct()
        ->orderBy('city')
        ->pluck('city');

        return view('activities.index', [
            'activities' => $activities,
            'cities' => $cities,
            'preference' => $preference,
            'filters' => $request->validated(),
        ]);
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
