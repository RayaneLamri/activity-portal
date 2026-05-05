<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminInviteRegistrationRequest;
use App\Models\Registration;

class RegistrationDecisionController extends Controller
{
    public function invite(AdminInviteRegistrationRequest $request)
    {
        //
    }

    public function accept(Registration $registration)
    {
        // 
    }

    public function reject(Registration $registration)
    {
        // 
    }
}
