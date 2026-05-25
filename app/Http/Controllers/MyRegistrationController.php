<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Services\RegistrationService;

class MyRegistrationController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index()
    {
        $registrationsForStatus = function (string $status, string $pageName) {
            return auth()->user()
                ->registrations()
                ->with('activity')
                ->where('status', $status)
                ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', now()->toDateString()))
                ->latest('date')
                ->paginate(8, ['*'], $pageName);
        };

        return view('my-registrations.index', [
            'invitedRegistrations' => $registrationsForStatus(Registration::INVITED, 'invited_page'),
            'requestedRegistrations' => $registrationsForStatus(Registration::REQUESTED, 'requested_page'),
            'acceptedRegistrations' => $registrationsForStatus(Registration::ACCEPTED, 'accepted_page'),
            'rejectedRegistrations' => $registrationsForStatus(Registration::REJECTED, 'rejected_page'),
        ]);
    }

    public function store(StoreRegistrationRequest $request)
    {
        $activity = Activity::findOrFail($request->integer('activity_id'));

        $this->registrationService->createRequest($request->user(), $activity);

        return back()->with('status', 'Registration request sent.');
    }

    public function acceptInvitation(Registration $registration)
    {
        $this->registrationService->acceptInvite($registration, request()->user());

        return redirect()
            ->route('my-registrations.index')
            ->with('status', 'Invitation accepted.');
    }

    public function rejectInvitation(Registration $registration)
    {
        $this->registrationService->rejectInvite($registration, request()->user());

        return redirect()
            ->route('my-registrations.index')
            ->with('status', 'Invitation declined.');
    }
}
