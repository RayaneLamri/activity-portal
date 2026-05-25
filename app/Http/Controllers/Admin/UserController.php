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
            ->when($filters['match_preferences'] && $preference, function ($query) use ($preference): void {
                $preferredCities = $preference->cityList();
                $preferredPeriodNames = $preference->periodNameList();
                $preferredAgeGroups = $preference->ageGroupList();

                if ($preferredCities !== []) {
                    $query->whereIn('city', $preferredCities);
                }

                if ($preferredPeriodNames !== []) {
                    $query->whereIn('period_name', $preferredPeriodNames);
                }

                if ($preferredAgeGroups !== []) {
                    $query->whereIn('age_group', $preferredAgeGroups);
                }
            })
            ->when($filters['cities'] !== [], fn ($query) => $query->whereIn('city', $filters['cities']))
            ->when($filters['period_names'] !== [], fn ($query) => $query->whereIn('period_name', $filters['period_names']))
            ->when($filters['age_groups'] !== [], fn ($query) => $query->whereIn('age_group', $filters['age_groups']))
            ->orderBy('starts_on')
            ->orderBy('title')
            ->limit(20)
            ->get();

        $viewData = [
            'user' => $user,
            'preference' => $preference,
            'activities' => $activities,
            'filters' => $filters,
            'ageGroups' => Activity::ageGroups(),
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
                    ->whereDate('starts_on', '>=', $today)
                    ->whereNotNull('city')
                    ->distinct()
                    ->orderBy('city')
                    ->pluck('city'),
                'periods' => Activity::query()
                    ->whereDate('starts_on', '>=', $today)
                    ->orderBy('starts_on')
                    ->pluck('period_name')
                    ->filter()
                    ->unique()
                    ->values(),
            ])->render(),
        ]);
    }

    protected function inviteFilters(Request $request, ?UserPreference $preference): array
    {
        if (! $request->boolean('filtered')) {
            return [
                'search' => null,
                'cities' => $preference?->cityList() ?? [],
                'period_names' => $preference?->periodNameList() ?? [],
                'age_groups' => $preference?->ageGroupList() ?? [],
                'match_preferences' => true,
            ];
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'cities' => ['nullable', 'array'],
            'cities.*' => ['string', 'max:255'],
            'period_names' => ['nullable', 'array'],
            'period_names.*' => ['string', 'max:255'],
            'age_groups' => ['nullable', 'array'],
            'age_groups.*' => ['string', 'in:'.implode(',', Activity::ageGroupKeys())],
            'match_preferences' => ['nullable', 'boolean'],
        ]);

        return [
            'search' => $validated['search'] ?? null,
            'cities' => array_values(array_filter($validated['cities'] ?? [])),
            'period_names' => array_values(array_filter($validated['period_names'] ?? [])),
            'age_groups' => array_values(array_filter($validated['age_groups'] ?? [])),
            'match_preferences' => $request->boolean('match_preferences'),
        ];
    }
}
