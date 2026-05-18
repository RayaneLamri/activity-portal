<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Activity;
use App\Services\RegistrationService;

class MyRegistrationController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index()
    {
        $registrations = auth()->user()
            ->registrations()
            ->with('activity')
            ->latest('date') // ou created_at selon ton nom de colonne
            ->paginate(12);

        return view('my-registrations.index', [
            'registrations' => $registrations,
        ]);
    }

    public function store(StoreRegistrationRequest $request)
    {
        $activity = Activity::findOrFail($request->integer('activity_id'));

        $this->registrationService->createRequest($request->user(), $activity);

        return redirect()
            ->route('my-registrations.index')
            ->with('status', 'Registration request sent.');
    }
}
