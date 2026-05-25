<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\HomeRoute;

abstract class Controller
{
    protected function homeRouteName(?User $user): string
    {
        return HomeRoute::nameFor($user);
    }
}
