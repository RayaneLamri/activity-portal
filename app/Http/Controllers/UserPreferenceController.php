<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPreferenceRequest;
use App\Models\UserPreference;

class UserPreferenceController extends Controller
{
    public function edit()
    {
        return redirect()->route('profile.edit');
    }

    public function update(UpdateUserPreferenceRequest $request)
    {
        $ageGroups = $request->validated('age_groups') ?? [];
        [$minAge, $maxAge] = UserPreference::ageRangeForGroups($ageGroups);

        $preferences = [
            'cities' => $request->validated('cities') ?? [],
            'period_names' => $request->validated('period_names') ?? [],
            'age_groups' => $ageGroups,
            'min_age' => $minAge,
            'max_age' => $maxAge,
        ];

        $request->user()->preference()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $preferences,
        );

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Preferences updated.');
    }
}
