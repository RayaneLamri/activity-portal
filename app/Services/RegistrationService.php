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
        if (! $activity->is_active) {
            throw ValidationException::withMessages([
                'activity_id' => 'This activity is inactive.',
            ]);
        }

        if (! $user->is_visible) {
            throw ValidationException::withMessages([
                'user_id' => 'Hidden users cannot be invited.',
            ]);
        }

        $exists = Registration::query()
        ->where('user_id', $user->id)
        ->where('activity_id', $activity->id)
        ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'user_id' => 'This user already has a registration for this activity.',
            ]);
        }

        $registration = Registration::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::INVITED,
        ]);

        $registration->events()->create([
            'user_id' => $user->id,
            'action' => Registration::INVITED,
            'comment' => $comment,
            'date' => now(),
        ]);

        return $registration;
    }

    public function accept(Registration $registration, User $user)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be accepted.',
            ]);
        }

        if (! $registration->user->is_visible) {
            throw ValidationException::withMessages([
                'registration' => 'Hidden users cannot be accepted.',
            ]);
        }

        if ($registration->activity->remainingCapacity() <= 0) {
            throw ValidationException::withMessages([
                'registration' => 'This activity is full.',
            ]);
        }

        $registration->update([
            'status' => Registration::ACCEPTED,
        ]);

        $registration->events()->create([
            'user_id' => $user->id,
            'action' => Registration::ACCEPTED,
            'date' => now(),
        ]);

        return $registration;
    }

    public function reject(Registration $registration, User $user)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be rejected.',
            ]);
        }

        $registration->update([
            'status' => Registration::REJECTED,
        ]);

        $registration->events()->create([
            'user_id' => $user->id,
            'action' => Registration::REJECTED,
            'date' => now(),
        ]);

        return $registration;
    }
}
