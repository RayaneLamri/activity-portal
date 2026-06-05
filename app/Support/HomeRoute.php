<?php

namespace App\Support;

use App\Models\User;

class HomeRoute
{
    public static function nameFor(?User $user): string
    {
        if ($user?->isAdmin() || $user?->isUser()) {
            return 'dashboard';
        }

        return 'login';
    }
}
