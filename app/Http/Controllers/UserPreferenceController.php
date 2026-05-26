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
        $preferences = [
            'cities' => UserPreference::cleanList($request->validated('cities') ?? []),
            'period_names' => UserPreference::cleanList($request->validated('period_names') ?? []),
            'age_groups' => UserPreference::cleanList($request->validated('age_groups') ?? []),
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
