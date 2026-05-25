<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
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
        return view('auth.register', [
            'cities' => Activity::query()
                ->distinct()
                ->orderBy('city')
                ->pluck('city'),
            'periods' => Activity::query()
                ->whereDate('starts_on', '>=', now()->toDateString())
                ->orderBy('starts_on')
                ->pluck('period_name')
                ->filter()
                ->unique()
                ->values(),
            'ageGroups' => Activity::ageGroups(),
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
            'age_groups.*' => ['string', 'in:'.implode(',', Activity::ageGroupKeys())],
            'period_names' => ['nullable', 'array'],
            'period_names.*' => ['string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $preferences = [
            'cities' => array_values(array_filter($validated['cities'] ?? [])),
            'age_groups' => array_values(array_filter($validated['age_groups'] ?? [])),
            'period_names' => array_values(array_filter($validated['period_names'] ?? [])),
        ];

        if (array_filter($preferences) !== []) {
            $user->preference()->create($preferences);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route($this->homeRouteName($user), absolute: false));
    }
}
