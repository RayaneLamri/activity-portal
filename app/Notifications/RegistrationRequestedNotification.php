<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationRequestedNotification extends Notification
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
        $this->registration->loadMissing(['activity', 'user']);
        $activity = $this->registration->activity;
        $user = $this->registration->user;

        return (new MailMessage)
            ->subject('New registration request')
            ->greeting("Hello {$notifiable->name},")
            ->line("{$user->name} has requested registration for {$activity->title}.")
            ->line("User: {$user->name} ({$user->email})")
            ->line("Location: {$activity->location_name}")
            ->line("Period: {$activity->period_name}")
            ->line("Dates: {$activity->starts_on?->format('Y-m-d')} - {$activity->ends_on?->format('Y-m-d')}")
            ->action('Review registrations', route('admin.registrations.index'));
    }
}
