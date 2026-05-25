<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPreferenceRequest;

class UserPreferenceController extends Controller
{
    public function edit()
    {
        return redirect()->route('profile.edit');
    }

    public function update(UpdateUserPreferenceRequest $request)
    {
        $preferences = [
            'cities' => array_values(array_filter($request->validated('cities') ?? [])),
            'period_names' => array_values(array_filter($request->validated('period_names') ?? [])),
            'age_groups' => array_values(array_filter($request->validated('age_groups') ?? [])),
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
