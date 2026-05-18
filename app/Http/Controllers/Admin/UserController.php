<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Models\UserPreference;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('role', 'user')
            ->with('preference')
            ->withCount('registrations')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function toggleVisibility(User $user)
    {
        abort_unless($user->isUser(), 404);

        $user->update([
            'is_visible' => ! $user->is_visible,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User visibility updated.');
    }

    public function inviteOptions(User $user, Request $request)
    {
        abort_unless($user->isUser(), 404);

        $user->load('preference');
        $preference = $user->preference;
        $today = Carbon::today();
        $filters = $this->inviteFilters($request, $preference);

        $activities = Activity::query()
            ->withCount([
                'registrations as accepted_registrations_count' => fn ($query) => $query
                    ->where('status', Registration::ACCEPTED),
            ])
            ->where('is_active', true)
            ->whereDate('starts_on', '>=', $today)
            ->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id))
            ->when($filters['search'], function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('external_reference', 'like', "%{$search}%")
                        ->orWhere('location_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['city'], fn ($query, $city) => $query->where('city', $city))
            ->when($filters['min_age'] !== null, function ($query) use ($filters): void {
                $query->where(function ($query) use ($filters): void {
                    $query
                        ->whereNull('max_age')
                        ->orWhere('max_age', '>=', $filters['min_age']);
                });
            })
            ->when($filters['max_age'] !== null, function ($query) use ($filters): void {
                $query->where(function ($query) use ($filters): void {
                    $query
                        ->whereNull('min_age')
                        ->orWhere('min_age', '<=', $filters['max_age']);
                });
            })
            ->when($filters['starts_on'], fn ($query, $date) => $query->whereDate('starts_on', '>=', $date))
            ->when($filters['ends_on'], fn ($query, $date) => $query->whereDate('ends_on', '<=', $date))
            ->orderBy('starts_on')
            ->orderBy('title')
            ->limit(20)
            ->get();

        $viewData = [
            'user' => $user,
            'preference' => $preference,
            'activities' => $activities,
            'filters' => $filters,
        ];

        if ($request->boolean('results_only')) {
            return new JsonResponse([
                'html' => view('admin.users.partials.invite-activities-results', $viewData)->render(),
            ]);
        }

        return new JsonResponse([
            'html' => view('admin.users.partials.invite-activities-modal', [
                ...$viewData,
                'cities' => Activity::query()
                    ->where('is_active', true)
                    ->whereDate('starts_on', '>=', $today)
                    ->whereNotNull('city')
                    ->distinct()
                    ->orderBy('city')
                    ->pluck('city'),
            ])->render(),
        ]);
    }

    protected function inviteFilters(Request $request, ?UserPreference $preference): array
    {
        if (! $request->boolean('filtered')) {
            return [
                'search' => null,
                'city' => $preference?->city,
                'min_age' => $preference?->min_age,
                'max_age' => $preference?->max_age,
                'starts_on' => $preference?->starts_on?->format('Y-m-d'),
                'ends_on' => $preference?->ends_on?->format('Y-m-d'),
            ];
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:99'],
            'max_age' => ['nullable', 'integer', 'min:0', 'max:99'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
        ]);

        return [
            'search' => $validated['search'] ?? null,
            'city' => $validated['city'] ?? null,
            'min_age' => $validated['min_age'] ?? null,
            'max_age' => $validated['max_age'] ?? null,
            'starts_on' => $validated['starts_on'] ?? null,
            'ends_on' => $validated['ends_on'] ?? null,
        ];
    }
}
