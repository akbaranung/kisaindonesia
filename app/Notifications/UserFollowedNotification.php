<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $follower) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->follower->name . ' mulai mengikuti anda.')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($this->follower->name . ' sekarang mengikuti profil anda.')
            ->line('Terima kasih telah berinteraksi di platform kami!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'follow',
            'title' => 'Pengikut Baru',
            'message' => $this->follower->name . ' mulai mengikuti anda.',
            'follower_id' => $this->follower->id
        ];
    }
}
