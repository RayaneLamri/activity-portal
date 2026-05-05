<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminInviteRegistrationRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;

class RegistrationDecisionController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService,
    ) {}

    public function invite(AdminInviteRegistrationRequest $request)
    {
        $user = User::findOrFail($request->integer('user_id'));
        $activity = Activity::findOrFail($request->integer('activity_id'));

        $this->registrationService->createInvite(
            $user,
            $activity,
            $request->user(),
            $request->input('comment') ?? null,
        );

        return redirect()
        ->route('admin.registrations.index')
        ->with('status', 'User invited to activity.');
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
