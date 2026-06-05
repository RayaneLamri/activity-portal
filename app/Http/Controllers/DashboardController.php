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
        $today = now()->toDateString();
        $matchedActivities = Activity::query()
            ->upcoming($today)
            ->withoutUserRegistration($user)
            ->applyPreference($user->preference);

        $activeRegistrations = Registration::query()
            ->with('activity')
            ->where('user_id', $user->id)
            ->where('status', '!=', Registration::REJECTED)
            ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', $today));

        return [
            'matchedActivities' => $matchedActivities
                ->orderBy('starts_on')
                ->limit(5)
                ->get(),
            'activeRegistrations' => $activeRegistrations
                ->latest('date')
                ->limit(5)
                ->get(),
        ];
    }

    private function adminData(): array
    {
        $today = now()->toDateString();

        return [
            'upcomingActivities' => Activity::query()
                ->upcoming($today)
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
                ->whereHas('activity', fn ($query) => $query->whereDate('starts_on', '>=', $today))
                ->latest('date')
                ->limit(5)
                ->get(),
        ];
    }
}
