<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    public function createRequest(User $user, Activity $activity)
    {
        if (! $activity->is_active) {
            throw ValidationException::withMessages([
                'activity_id' => 'This activity is inactive.',
            ]);
        }

        $exists = Registration::where('user_id', $user->id)
        ->where('activity_id', $activity->id)
        ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'activity_id' => 'You already have a registration for this activity.',
            ]);
        }

        return Registration::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::REQUESTED,
        ]);
    }

    public function createInvite(User $user, Activity $activity)
    {
        //
    }

    public function accept(Registration $registration, User $user)
    {
        //
    }

    public function refuse(Registration $registration, User $user)
    {
        //
    }
}
