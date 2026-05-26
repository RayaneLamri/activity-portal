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
    public function createRequest(User $user, Activity $activity): Registration
    {
        $this->assertActivityHasNotStarted($activity);
        $this->assertNoExistingRegistration(
            $user,
            $activity,
            'activity_id',
            'You already have a registration for this activity.',
        );

        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::REQUESTED,
        ]);

        $this->recordTransition($registration, null, Registration::REQUESTED);

        $user->notify(new RegistrationRequestedNotification($registration));

        return $registration;
    }

    public function createInvite(User $user, Activity $activity): Registration
    {
        $this->assertActivityHasNotStarted($activity);
        $this->assertCapacityAvailable($activity, 'activity_id');
        $this->assertNoExistingRegistration(
            $user,
            $activity,
            'user_id',
            'This user already has a registration for this activity.',
        );
        $this->assertUserIsVisible($user, 'user_id', 'Inactive users cannot be invited.');

        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::INVITED,
        ]);

        $this->recordTransition($registration, null, Registration::INVITED);

        $user->notify(new RegistrationInvitedNotification($registration));

        return $registration;
    }

    public function accept(Registration $registration): Registration
    {
        $registration->loadMissing(['activity', 'user']);

        $this->assertAcceptableTransition($registration);
        $this->assertUserIsVisible($registration->user, 'registration', 'Inactive users cannot be accepted.');
        $this->assertCapacityAvailable($registration->activity);
        $this->assertActivityHasNotStarted($registration->activity, 'registration');

        $this->transitionTo($registration, Registration::ACCEPTED);

        $registration->user->notify(new RegistrationAcceptedNotification($registration));

        return $registration;
    }

    public function acceptInvite(Registration $registration, User $user): Registration
    {
        $registration->loadMissing('activity');

        $this->assertPendingInvitationOwner($registration, $user);
        $this->assertUserIsVisible($user, 'registration', 'Inactive users cannot accept invitations.');
        $this->assertCapacityAvailable($registration->activity);
        $this->assertActivityHasNotStarted($registration->activity, 'registration');

        return $this->transitionTo($registration, Registration::ACCEPTED);
    }

    public function rejectInvite(Registration $registration, User $user): Registration
    {
        $this->assertPendingInvitationOwner($registration, $user);

        return $this->transitionTo($registration, Registration::REJECTED);
    }

    public function reject(Registration $registration): Registration
    {
        $this->assertRejectableTransition($registration);

        $this->transitionTo($registration, Registration::REJECTED);

        $registration->user->notify(new RegistrationRejectedNotification($registration));

        return $registration;
    }

    protected function assertNoExistingRegistration(
        User $user,
        Activity $activity,
        string $field,
        string $message
    ): void {
        $exists = Registration::query()
            ->where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                $field => $message,
            ]);
        }
    }

    protected function assertActivityHasNotStarted(Activity $activity, string $field = 'activity_id'): void
    {
        if ($activity->starts_on?->lt(now()->startOfDay()) ?? true) {
            throw ValidationException::withMessages([
                $field => 'This activity has already started.',
            ]);
        }
    }

    protected function assertUserIsVisible(User $user, string $field, string $message): void
    {
        if (! $user->is_visible) {
            throw ValidationException::withMessages([
                $field => $message,
            ]);
        }
    }

    protected function assertAcceptableTransition(Registration $registration): void
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be accepted.',
            ]);
        }
    }

    protected function assertRejectableTransition(Registration $registration): void
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be rejected.',
            ]);
        }
    }

    protected function assertCapacityAvailable(Activity $activity, string $field = 'registration'): void
    {
        if ($activity->isFull()) {
            throw ValidationException::withMessages([
                $field => 'This activity is full.',
            ]);
        }
    }

    protected function assertPendingInvitationOwner(Registration $registration, User $user): void
    {
        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be processed.',
            ]);
        }
    }

    protected function transitionTo(Registration $registration, string $status): Registration
    {
        DB::transaction(function () use ($registration, $status): void {
            $fromStatus = $registration->status;

            $registration->update([
                'status' => $status,
            ]);

            $this->recordTransition($registration, $fromStatus, $status);
        });

        return $registration;
    }

    protected function recordTransition(Registration $registration, ?string $fromStatus, string $toStatus): void
    {
        $registration->events()->create([
            'action' => $toStatus,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'date' => now(),
        ]);
    }
}
