<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationAcceptedNotification extends Notification
{
    use Queueable;

    public Registration $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
        // return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $activity = $this->registration->activity;

        return (new MailMessage)
            ->subject('Your registration has been accepted')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your registration for {$activity->title} has been accepted.")
            ->line("Location: {$activity->location_name}")
            ->line("Start date: {$activity->starts_on?->format('Y-m-d')}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
