<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\RegistrationAcceptedNotification;
use App\Notifications\RegistrationInvitationAcceptedNotification;
use App\Notifications\RegistrationInvitationRejectedNotification;
use App\Notifications\RegistrationInvitedNotification;
use App\Notifications\RegistrationRejectedNotification;
use App\Notifications\RegistrationRequestedNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    public function createRequest(User $user, Activity $activity)
    {
        $this->assertActivityHasNotStarted($activity);
        $this->assertCapacityAvailable($activity, 'activity_id');
        $this->assertUniqueRegistration($user, $activity);

        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::REQUESTED,
        ]);

        $registration->events()->create([
            'action' => Registration::REQUESTED,
        ]);

        $this->notifyPrimaryAdmin(new RegistrationRequestedNotification($registration));

        return $registration;
    }

    public function createInvite(User $user, Activity $activity)
    {
        $this->assertActivityHasNotStarted($activity);
        $this->assertCapacityAvailable($activity, 'activity_id');
        $this->assertUniqueRegistration($user, $activity, 'user_id');
        $this->assertUserIsActive($user, 'user_id');

        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::INVITED,
        ]);

        $registration->events()->create([
            'action' => Registration::INVITED,
        ]);

        $user->notify(new RegistrationInvitedNotification($registration));

        return $registration;
    }

    public function accept(Registration $registration)
    {
        $registration->loadMissing(['activity', 'user']);

        $this->assertProcessableTransition($registration);
        $this->assertUserIsActive($registration->user);
        $this->assertCapacityAvailable($registration->activity);
        $this->assertActivityHasNotStarted($registration->activity, 'registration');

        $registration->update([
            'status' => Registration::ACCEPTED,
        ]);

        $registration->events()->create([
            'action' => Registration::ACCEPTED,
        ]);

        $registration->user->notify(new RegistrationAcceptedNotification($registration));

        return $registration;
    }

    public function acceptInvite(Registration $registration, User $user)
    {
        $registration->loadMissing(['activity', 'user']);

        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be processed.',
            ]);
        }

        $this->assertUserIsActive($registration->user);
        $this->assertCapacityAvailable($registration->activity);
        $this->assertActivityHasNotStarted($registration->activity, 'registration');

        $registration->update([
            'status' => Registration::ACCEPTED,
        ]);

        $registration->events()->create([
            'action' => Registration::ACCEPTED,
        ]);

        $this->notifyPrimaryAdmin(new RegistrationInvitationAcceptedNotification($registration));

        return $registration;
    }

    public function rejectInvite(Registration $registration, User $user)
    {
        $registration->loadMissing(['activity', 'user']);

        if (! $registration->isInvited() || $registration->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'registration' => 'Only your pending invitations can be processed.',
            ]);
        }

        $registration->update([
            'status' => Registration::REJECTED,
        ]);

        $registration->events()->create([
            'action' => Registration::REJECTED,
        ]);

        $this->notifyPrimaryAdmin(new RegistrationInvitationRejectedNotification($registration));

        return $registration;
    }

    public function reject(Registration $registration)
    {
        $registration->loadMissing('user');

        $this->assertProcessableTransition($registration);

        $registration->update([
            'status' => Registration::REJECTED,
        ]);

        $registration->events()->create([
            'action' => Registration::REJECTED,
        ]);

        $registration->user->notify(new RegistrationRejectedNotification($registration));

        return $registration;
    }

    protected function assertUniqueRegistration(User $user, Activity $activity, string $field = 'activity_id')
    {
        $exists = Registration::query()
            ->where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                $field => 'A registration already exists for this activity.',
            ]);
        }
    }

    protected function assertActivityHasNotStarted(Activity $activity, string $field = 'activity_id')
    {
        if ($activity->starts_on?->lt(now()->startOfDay()) ?? true) {
            throw ValidationException::withMessages([
                $field => 'This activity has already started.',
            ]);
        }
    }

    protected function assertUserIsActive(User $user, string $field = 'registration')
    {
        if (! $user->is_active) {
            throw ValidationException::withMessages([
                $field => 'This user is inactive.',
            ]);
        }
    }

    protected function assertProcessableTransition(Registration $registration)
    {
        if (! $registration->isRequested() && ! $registration->isInvited()) {
            throw ValidationException::withMessages([
                'registration' => 'Only requested or invited registrations can be processed.',
            ]);
        }
    }

    protected function assertCapacityAvailable(Activity $activity, string $field = 'registration')
    {
        if ($activity->isFull()) {
            throw ValidationException::withMessages([
                $field => 'This activity is full.',
            ]);
        }
    }

    protected function notifyPrimaryAdmin(Notification $notification)
    {
        User::query()->where('role', 'admin')->first()?->notify($notification);
    }
}
