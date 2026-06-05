<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->user;

        return (new MailMessage)
            ->subject('New user registration')
            ->greeting("Hello {$notifiable->name},")
            ->line('A new user has registered on Activity Portal.')
            ->line("User: {$user->name} ({$user->email})")
            ->action('Open users', route('admin.users.index'));
    }
}
