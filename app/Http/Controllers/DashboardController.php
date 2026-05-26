<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', $this->adminData());
        }

        return view('dashboard.user', $this->userData($user));
    }

    private function userData(User $user): array
    {
        $preference = $user->preference;
        $matchedActivitiesQuery = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id));

        if ($preference) {
            $preference->applyToActivityQuery($matchedActivitiesQuery);
        }

        $activeRegistrationsQuery = Registration::query()
            ->with('activity')
            ->where('user_id', $user->id)
            ->whereIn('status', [
                Registration::REQUESTED,
                Registration::INVITED,
                Registration::ACCEPTED,
            ])
            ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', now()->toDateString()));

        return [
            'matchedActivities' => (clone $matchedActivitiesQuery)
                ->orderBy('starts_on')
                ->limit(5)
                ->get(),
            'activeRegistrations' => (clone $activeRegistrationsQuery)
                ->latest('date')
                ->limit(5)
                ->get(),
        ];
    }

    private function adminData(): array
    {
        $upcomingActivitiesQuery = Activity::query()
            ->whereDate('starts_on', '>=', now()->toDateString());

        return [
            'upcomingActivities' => (clone $upcomingActivitiesQuery)
                ->withCount([
                    'registrations as requested_count' => fn ($query) => $query->where('status', Registration::REQUESTED),
                    'registrations as invited_count' => fn ($query) => $query->where('status', Registration::INVITED),
                    'registrations as accepted_count' => fn ($query) => $query->where('status', Registration::ACCEPTED),
                ])
                ->orderBy('starts_on')
                ->limit(5)
                ->get(),
            'recentRegistrations' => Registration::query()
                ->with(['activity', 'user'])
                ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', now()->toDateString()))
                ->latest('date')
                ->limit(5)
                ->get(),
        ];
    }
}
