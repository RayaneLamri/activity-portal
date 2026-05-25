<?php

namespace App\Support;

use App\Models\User;

class HomeRoute
{
    public static function nameFor(?User $user): string
    {
        if ($user?->isAdmin()) {
            return 'admin.registrations.index';
        }

        if ($user?->isUser()) {
            return 'activities.index';
        }

        return 'login';
    }
}
