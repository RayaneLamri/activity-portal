<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminInviteRegistrationRequest;
use App\Models\Registration;
use App\Services\RegistrationService;

class RegistrationDecisionController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService,
    ) {}

    public function invite(AdminInviteRegistrationRequest $request)
    {
        //
    }

    public function accept(Registration $registration)
    {
        $this->registrationService->accept($registration, request()->user());

        return redirect()
        ->route('admin.registrations.index')
        ->with('status', 'Registration accepted.');
    }

    public function reject(Registration $registration)
    {
        $validated = request()->validate([
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->registrationService->reject(
            $registration,
            request()->user(),
            $validated['comment'] ?? null,
        );

        return redirect()
        ->route('admin.registrations.index')
        ->with('status', 'Registration rejected.');
    }
}
