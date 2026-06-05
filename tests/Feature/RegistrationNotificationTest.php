<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\RegistrationInvitationAcceptedNotification;
use App\Notifications\RegistrationInvitationRejectedNotification;
use App\Notifications\RegistrationRequestedNotification;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_request_notifies_first_admin(): void
    {
        Notification::fake();

        $firstAdmin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $activity = $this->createActivity();

        app(RegistrationService::class)->createRequest($user, $activity);

        Notification::assertSentTo($firstAdmin, RegistrationRequestedNotification::class);
        Notification::assertNotSentTo($user, RegistrationRequestedNotification::class);
    }

    public function test_accepting_an_invitation_notifies_first_admin(): void
    {
        Notification::fake();

        $firstAdmin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $activity = $this->createActivity();
        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::INVITED,
        ]);

        app(RegistrationService::class)->acceptInvite($registration, $user);

        Notification::assertSentTo($firstAdmin, RegistrationInvitationAcceptedNotification::class);
    }

    public function test_rejecting_an_invitation_notifies_first_admin(): void
    {
        Notification::fake();

        $firstAdmin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $activity = $this->createActivity();
        $registration = Registration::query()->create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'status' => Registration::INVITED,
        ]);

        app(RegistrationService::class)->rejectInvite($registration, $user);

        Notification::assertSentTo($firstAdmin, RegistrationInvitationRejectedNotification::class);
    }

    private function createActivity(): Activity
    {
        return Activity::query()->create([
            'title' => 'Test Activity',
            'external_reference' => fake()->unique()->uuid(),
            'location_name' => 'Test Location',
            'city' => 'Brussels',
            'period_name' => 'Summer',
            'min_age' => 8,
            'max_age' => 12,
            'starts_on' => now()->addWeek()->toDateString(),
            'ends_on' => now()->addWeeks(2)->toDateString(),
            'capacity' => 10,
        ]);
    }
}
