<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPreferenceRequest;
use App\Models\Activity;

class UserPreferenceController extends Controller
{
    public function edit()
    {
        return view('preferences.edit', [
            'preference' => request()->user()->preference,
            'cities' => $cities = Activity::query()
                ->distinct()
                ->orderBy('city')
                ->pluck('city')
        ]);
    }

    public function update(UpdateUserPreferenceRequest $request)
    {
        $request->user()->preference()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated(),
        );

        return redirect()
            ->route('preferences.edit')
            ->with('status', 'Preferences updated.');
    }
}
