<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\RegistrationDecisionController;
use App\Http\Controllers\Admin\UpcomingActivityExportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\MyRegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_user'])->group(function () {
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');

    Route::get('/my/registrations', [MyRegistrationController::class, 'index'])->name('my-registrations.index');
    Route::post('/my/registrations', [MyRegistrationController::class, 'store'])->name('my-registrations.store');

    Route::get('/my/preferences', [UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::put('/my/preferences', [UserPreferenceController::class, 'update'])->name('preferences.update');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/activities/{activity}/registrations/{status}', [AdminRegistrationController::class, 'activityRegistrations'])->name('activities.registrations');
    Route::get('/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');

    Route::post('/registrations/{registration}/accept', [RegistrationDecisionController::class, 'accept'])->name('registrations.accept');
    Route::post('/registrations/{registration}/reject', [RegistrationDecisionController::class, 'reject'])->name('registrations.reject');
    Route::post('/registrations/invite', [RegistrationDecisionController::class, 'invite'])->name('registrations.invite');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/invite-options', [AdminUserController::class, 'inviteOptions'])->name('users.invite-options');
    Route::patch('/users/{user}/visibility', [AdminUserController::class, 'toggleVisibility'])->name('users.toggle-visibility');

    Route::get('/exports/upcoming-activities', UpcomingActivityExportController::class)->name('exports.upcoming-activities');
});

require __DIR__.'/auth.php';
