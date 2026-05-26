<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\UserVerifiedNotification;
use Illuminate\Auth\Events\Verified;

class NotifyAdminsOfVerifiedUser
{
    public function handle(Verified $event): void
    {
        $user = $event->user;

        if (! $user instanceof User || ! $user->isUser()) {
            return;
        }

        User::query()
            ->where('role', 'admin')
            ->get()
            ->each(fn (User $admin) => $admin->notify(new UserVerifiedNotification($user)));
    }
}
