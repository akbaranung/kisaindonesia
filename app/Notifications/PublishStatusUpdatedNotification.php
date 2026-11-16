<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublishStatusUpdatedNotification extends Notification
{
    use Queueable;

    public string $status;
    public ?string $rejectionReason;

    public function __construct(string $status, ?string $rejectionReason = null)
    {
        $this->status = $status;
        $this->rejectionReason = $rejectionReason;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail']; // Menyimpan notifikasi ke database agar bisa dibaca di dashboard user
    }

    public function toArray($notifiable): array
    {
        $isApproved = $this->status === 'approved';
        return [
            'type' => 'publish_status',
            'title' => $isApproved ? 'Publikasi Disetujui! 🎉' : 'Pengajuan Ditolak ⚠️',
            'message' => $isApproved
                ? 'Hore! Pengajuan publikasi cerita Anda telah disetujui dan resmi dipublikasikan.'
                : 'Pengajuan publikasi cerita Anda ditolak.',
            'rejection_reason' => $this->rejectionReason,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isApproved = $this->status === 'approved';
        $subject = $isApproved ? 'Selamat! Pengajuan Publish Cerita Anda Disetujui' : 'Update Pengajuan Publish Cerita';
        $mail = (new MailMessage)->subject($subject)->greeting('Halo, ' . $notifiable->name . '!');
        if ($isApproved) {
            $mail->line('Pengajuan publish cerita anda telah **DISETUJUI**.')
                ->line('**PENTING : ** Harap diperhatikan bahwa persetujuan publish bersifat mengikat dan harus dilakukan sesuai dengan seluruh aturan serta regulasi internal KISA Indonesia yang berlaku.')
                ->action('Buka Dashboard', route('my-stories'));
        } else {
            $mail->line('Pengajuan publish cerita Anda saat ini **BELUM DISETUJUI**.')
                ->line('Alasan: ' . ($this->rejectionReason ?? 'Belum memenuhi syarat.'));
        }

        return $mail;
    }
}
