<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminInviteRegistrationRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;

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
        );

        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => 'Invitation sent.',
                'activity_id' => $activity->id,
                'user_id' => $user->id,
            ]);
        }

        return redirect()
            ->back()
            ->with('status', 'User invited to activity.');
    }

    public function accept(Registration $registration)
    {
        $this->registrationService->accept($registration);

        if (request()->expectsJson()) {
            return new JsonResponse([
                'message' => 'Registration accepted.',
                'registration_id' => $registration->id,
                'activity_id' => $registration->activity_id,
                'status' => $registration->status,
            ]);
        }

        return redirect()
            ->route('admin.registrations.index')
            ->with('status', 'Registration accepted.');
    }

    public function reject(Registration $registration)
    {
        $this->registrationService->reject($registration);

        if (request()->expectsJson()) {
            return new JsonResponse([
                'message' => 'Registration rejected.',
                'registration_id' => $registration->id,
                'activity_id' => $registration->activity_id,
                'status' => $registration->status,
            ]);
        }

        return redirect()
            ->route('admin.registrations.index')
            ->with('status', 'Registration rejected.');
    }
}
