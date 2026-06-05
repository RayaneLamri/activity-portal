<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationEvent;
use Illuminate\Http\Request;

class RegistrationEventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search') ?: null;
        $action = $request->query('action');
        $action = in_array($action, Registration::statuses(), true) ? $action : null;
        $from = $request->query('from') ?: null;
        $until = $request->query('until') ?: null;

        $events = RegistrationEvent::query()
            ->with([
                'registration.user',
                'registration.activity',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('action', 'like', "%{$search}%")
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
            ->when($action, function ($query) use ($action) {
                $query->where('action', $action);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('date', '>=', $from);
            })
            ->when($until, function ($query) use ($until) {
                $query->whereDate('date', '<=', $until);
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.registration-events.index', [
            'events' => $events,
            'filters' => [
                'search' => $search,
                'action' => $action,
                'from' => $from,
                'until' => $until,
            ],
            'actions' => Registration::statuses(),
        ]);
    }
}
