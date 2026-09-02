<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMonetizeRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $applicant) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan monetisasi baru: ' . $this->applicant->name)
            ->greeting('Halo Admin!')
            ->line($this->applicant->name . ' (' . $this->applicant->email . ') mengajukan monetisasi.')
            ->action('Tinjau Pengajuan', url('/admin/premium-requests'))
            ->line('Silakan periksa kelayakan akun tersebut.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'monetize_request',
            'title' => 'Pengajuan Monetisasi',
            'message' => $this->applicant->name . ' mengajuan monetisasi.',
            'applicant_id' => $this->applicant->id,
        ];
    }
}
