<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\RegistrationAcceptedNotification;
use App\Notifications\RegistrationRejectedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    public function createRequest(User $user, Activity $activity)
    {
        $exists = Registration::where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'activity_id' => 'You already have a registration for this activity.',
            ]);
        }

        return DB::transaction(function () use ($user, $activity) {
            $registration = Registration::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => Registration::REQUESTED,
            ]);

            $this->recordTransition($registration, $user, null, Registration::REQUESTED);

            return $registration;
        });
    }

    public function createInvite(User $user, Activity $activity, ?User $actor = null)
    {
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

        return DB::transaction(function () use ($user, $activity, $actor) {
            $registration = Registration::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => Registration::INVITED,
            ]);

            $this->recordTransition($registration, $actor ?? $user, null, Registration::INVITED);

            return $registration;
        });
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

        $this->transitionTo($registration, $user, Registration::ACCEPTED);

        $registration->user->notify(
            new RegistrationAcceptedNotification($registration)
        );

        return $registration;
    }

    public function acceptInvite(Registration $registration, User $user)
    {
        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be accepted.',
            ]);
        }

        if (! $user->is_visible) {
            throw ValidationException::withMessages([
                'registration' => 'Hidden users cannot accept invitations.',
            ]);
        }

        if ($registration->activity->remainingCapacity() <= 0) {
            throw ValidationException::withMessages([
                'registration' => 'This activity is full.',
            ]);
        }

        $this->transitionTo($registration, $user, Registration::ACCEPTED);

        return $registration;
    }

    public function rejectInvite(Registration $registration, User $user)
    {
        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be rejected.',
            ]);
        }

        $this->transitionTo($registration, $user, Registration::REJECTED);

        return $registration;
    }

    public function reject(Registration $registration, User $user)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be rejected.',
            ]);
        }

        $this->transitionTo($registration, $user, Registration::REJECTED);

        $registration->user->notify(
            new RegistrationRejectedNotification($registration)
        );

        return $registration;
    }

    private function transitionTo(Registration $registration, User $actor, string $toStatus): Registration
    {
        return DB::transaction(function () use ($registration, $actor, $toStatus) {
            $fromStatus = $registration->status;

            $registration->update([
                'status' => $toStatus,
            ]);

            $this->recordTransition($registration, $actor, $fromStatus, $toStatus);

            return $registration;
        });
    }

    private function recordTransition(Registration $registration, User $actor, ?string $fromStatus, string $toStatus): void
    {
        $registration->events()->create([
            'user_id' => $actor->id,
            'action' => $toStatus,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'date' => now(),
        ]);
    }
}
