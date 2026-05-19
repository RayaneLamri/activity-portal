<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationEventIndexRequest;
use App\Models\Registration;
use App\Models\RegistrationEvent;

class RegistrationEventController extends Controller
{
    public function index(AdminRegistrationEventIndexRequest $request)
    {
        $events = RegistrationEvent::query()
            ->whereIn('id', RegistrationEvent::query()
                ->selectRaw('MAX(id)')
                ->groupBy('registration_id')
            )
            ->with([
                'user',
                'registration.user',
                'registration.activity',
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('action', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('registration.user', function ($query) use ($search) {
                            $query
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('registration.activity', function ($query) use ($search) {
                            $query
                                ->where('title', 'like', "%{$search}%")
                                ->orWhere('external_reference', 'like', "%{$search}%")
                                ->orWhere('location_name', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('action'), function ($query) use ($request) {
                $query->where('action', $request->input('action'));
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->date('from'));
            })
            ->when($request->filled('until'), function ($query) use ($request) {
                $query->whereDate('date', '<=', $request->date('until'));
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.registration-events.index', [
            'events' => $events,
            'filters' => $request->validated(),
            'actions' => [
                Registration::REQUESTED,
                Registration::INVITED,
                Registration::ACCEPTED,
                Registration::REJECTED,
            ],
        ]);
    }
}
