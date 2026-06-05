<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationInvitedNotification extends Notification
{
    use Queueable;

    public Registration $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->registration->loadMissing('activity');
        $activity = $this->registration->activity;

        return (new MailMessage)
            ->subject('You have been invited to an activity')
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been invited to {$activity->title}.")
            ->line("Location: {$activity->location_name}")
            ->line("Period: {$activity->period_name}")
            ->line("Dates: {$activity->starts_on?->format('Y-m-d')} - {$activity->ends_on?->format('Y-m-d')}")
            ->action('View your registrations', route('my-registrations.index'));
    }
}
