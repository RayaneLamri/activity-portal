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
        $this->registration->loadMissing('activity');
        $activity = $this->registration->activity;

        return (new MailMessage)
            ->subject('Your registration request has been received')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your request for {$activity->title} has been received.")
            ->line("Location: {$activity->location_name}")
            ->line("Period: {$activity->period_name}")
            ->line("Dates: {$activity->starts_on?->format('Y-m-d')} - {$activity->ends_on?->format('Y-m-d')}");
    }
}
