<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonetizeStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $status,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isApproved = $this->status === 'approved';
        $subject = $isApproved ? 'Selamat! Monetisasi Disetujui' : 'Update Pengajuan Monetisasi';
        $mail = (new MailMessage)->subject($subject)->greeting('Halo, ' . $notifiable->name . '!');
        if ($isApproved) {
            $mail->line('Pengajuan monetisasi akun anda telah **DISETUJUI**')
                ->action('Buka Dashboard', route('my-stories'));
        } else {
            $mail->line('Pengajuan monetisasi Anda saat ini **BELUM DISETUJUI**.')
                ->line('Alasan: ' . ($this->reason ?? 'Belum memenuhi kriteria minimum.'));
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        $isApproved = $this->status === 'approved';

        return [
            'type' => 'monetize_status',
            'title' => 'Status Monetisasi',
            'message' => $isApproved
                ? 'Selamat! Pengajuan monetisasi Anda disetujui.'
                : 'Pengajuan monetisasi Anda belum disetujui.',
            'status' => $this->status,
        ];
    }
}
