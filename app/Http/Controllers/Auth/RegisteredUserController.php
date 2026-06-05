<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $today = now()->toDateString();

        $cities = Activity::cityOptions(Activity::query());

        $periods = Activity::periodOptions(
            Activity::query()->upcoming($today)
        );

        return view('auth.register', [
            'cities' => $cities,
            'periods' => $periods,
            'ageGroups' => UserPreference::ageGroups(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cities' => ['nullable', 'array'],
            'cities.*' => ['string', 'max:255'],
            'age_groups' => ['nullable', 'array'],
            'age_groups.*' => ['string', 'in:'.implode(',', UserPreference::ageGroupKeys())],
            'period_names' => ['nullable', 'array'],
            'period_names.*' => ['string', 'max:255'],
        ]);

        $cities = $validated['cities'] ?? [];
        $ageGroups = $validated['age_groups'] ?? [];
        $periodNames = $validated['period_names'] ?? [];

        [$minAge, $maxAge] = UserPreference::ageRangeForGroups($ageGroups);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($cities || $ageGroups || $periodNames) {
            $user->preference()->create([
                'cities' => $cities,
                'age_groups' => $ageGroups,
                'period_names' => $periodNames,
                'min_age' => $minAge,
                'max_age' => $maxAge,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route($this->homeRouteName($user), absolute: false));
    }
}
