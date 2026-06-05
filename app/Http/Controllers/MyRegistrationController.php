<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MyRegistrationController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $registrationsByStatus = [
            Registration::INVITED => [],
            Registration::REQUESTED => [],
            Registration::ACCEPTED => [],
            Registration::REJECTED => [],
        ];

        $registrations = $user->registrations()
            ->with('activity')
            ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', $today))
            ->latest('date')
            ->get();

        foreach ($registrations as $registration) {
            $registrationsByStatus[$registration->status][] = $registration;
        }

        return view('my-registrations.index', [
            'invitedRegistrations' => $this->paginateRegistrations(
                $registrationsByStatus[Registration::INVITED],
                'invited_page'
            ),
            'requestedRegistrations' => $this->paginateRegistrations(
                $registrationsByStatus[Registration::REQUESTED],
                'requested_page'
            ),
            'acceptedRegistrations' => $this->paginateRegistrations(
                $registrationsByStatus[Registration::ACCEPTED],
                'accepted_page'
            ),
            'rejectedRegistrations' => $this->paginateRegistrations(
                $registrationsByStatus[Registration::REJECTED],
                'rejected_page'
            ),
        ]);
    }

    private function paginateRegistrations(array $registrations, string $pageName): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage($pageName);
        $perPage = 8;

        return new LengthAwarePaginator(
            array_slice($registrations, ($page - 1) * $perPage, $perPage),
            count($registrations),
            $perPage,
            $page,
            [
                'pageName' => $pageName,
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
        ]);

        $activity = Activity::findOrFail($validated['activity_id']);

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
