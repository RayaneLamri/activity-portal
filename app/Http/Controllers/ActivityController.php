<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::query()
        ->where('is_active', true)
        ->orderBy('starts_on')
        ->paginate(10);

        return view('activities.index', [
            'activities' => $activities,
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
