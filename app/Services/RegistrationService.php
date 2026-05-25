<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\RegistrationAcceptedNotification;
use App\Notifications\RegistrationInvitedNotification;
use App\Notifications\RegistrationRejectedNotification;
use App\Notifications\RegistrationRequestedNotification;
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

        $this->ensureActivityHasNotStarted($activity);

        $registration = DB::transaction(function () use ($user, $activity) {
            $registration = Registration::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => Registration::REQUESTED,
            ]);

            $this->recordTransition($registration, null, Registration::REQUESTED);

            return $registration;
        });

        $user->notify(new RegistrationRequestedNotification($registration));

        return $registration;
    }

    public function createInvite(User $user, Activity $activity)
    {
        if (! $user->is_visible) {
            throw ValidationException::withMessages([
                'user_id' => 'Inactive users cannot be invited.',
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

        $registration = DB::transaction(function () use ($user, $activity) {
            $registration = Registration::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => Registration::INVITED,
            ]);

            $this->recordTransition($registration, null, Registration::INVITED);

            return $registration;
        });

        $user->notify(new RegistrationInvitedNotification($registration));

        return $registration;
    }

    public function accept(Registration $registration)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be accepted.',
            ]);
        }

        if (! $registration->user->is_visible) {
            throw ValidationException::withMessages([
                'registration' => 'Inactive users cannot be accepted.',
            ]);
        }

        if ($registration->activity->isFull()) {
            throw ValidationException::withMessages([
                'registration' => 'This activity is full.',
            ]);
        }

        $this->ensureActivityHasNotStarted($registration->activity);

        $this->transitionTo($registration, Registration::ACCEPTED);

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
                'registration' => 'Inactive users cannot accept invitations.',
            ]);
        }

        if ($registration->activity->isFull()) {
            throw ValidationException::withMessages([
                'registration' => 'This activity is full.',
            ]);
        }

        $this->ensureActivityHasNotStarted($registration->activity);

        $this->transitionTo($registration, Registration::ACCEPTED);

        return $registration;
    }

    public function rejectInvite(Registration $registration, User $user)
    {
        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be rejected.',
            ]);
        }

        $this->transitionTo($registration, Registration::REJECTED);

        return $registration;
    }

    public function reject(Registration $registration)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be rejected.',
            ]);
        }

        $this->transitionTo($registration, Registration::REJECTED);

        $registration->user->notify(
            new RegistrationRejectedNotification($registration)
        );

        return $registration;
    }

    private function transitionTo(Registration $registration, string $toStatus): Registration
    {
        return DB::transaction(function () use ($registration, $toStatus) {
            $fromStatus = $registration->status;

            $registration->update([
                'status' => $toStatus,
            ]);

            $this->recordTransition($registration, $fromStatus, $toStatus);

            return $registration;
        });
    }

    private function recordTransition(Registration $registration, ?string $fromStatus, string $toStatus): void
    {
        $registration->events()->create([
            'action' => $toStatus,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'date' => now(),
        ]);
    }

    private function ensureActivityHasNotStarted(Activity $activity): void
    {
        if ($activity->starts_on?->lt(now()->startOfDay()) ?? true) {
            throw ValidationException::withMessages([
                'activity_id' => 'This activity has already started.',
            ]);
        }
    }
}
