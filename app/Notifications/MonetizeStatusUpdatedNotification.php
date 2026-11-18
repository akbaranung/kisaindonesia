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
        $subject = match ($this->status) {
            'approved' => 'Selamat! Monetisasi Disetujui',
            'revisi' => 'Perlu Revisi: Pengajuan Monetisasi Cerita',
            default => 'Pemberitahuan: Pengajuan Monetisasi Ditolak',
        };

        $mail = (new MailMessage)->subject($subject)->greeting('Halo, ' . $notifiable->name . '!');

        if ($this->status === 'approved') {
            $mail->line('Pengajuan monetisasi anda telah **DISETUJUI**.')
                ->line('**PENTING : ** Harap diperhatikan bahwa persetujuan monetisasi bersifat mengikat dan harus dilakukan sesuai dengan seluruh aturan serta regulasi internal KISA Indonesia yang berlaku.')
                ->action('Buka Dashboard', route('my-stories'));
        } elseif ($this->status === 'revisi') {
            $mail->line('Pengajuan monetisasi cerita Anda memerlukan **REVISI** sebelum dapat disetujui.')
                ->line('**Catatan Revisi dari Admin:** ' . ($this->reason ?? 'Harap tinjau kembali ketentuan bab.'))
                ->line('Anda dapat memperbaiki naskah cerita dan mengajukan ulang monetisasi melalui menu Pengajuan Premium.')
                ->action('Perbaiki Pengajuan', route('monetization.apply'));
        } else {
            $mail->line('Pengajuan monetisasi cerita Anda telah **DITOLAK** dan tidak dapat diajukan kembali.')
                ->line('**Alasan Penolakan:** ' . ($this->reason ?? 'Belum memenuhi kriteria internal.'))
                ->action('Buka Dashboard', route('my-stories'));
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        $title = match ($this->status) {
            'approved' => 'Monetisasi Disetujui! 🎉',
            'revisi' => 'Monetisasi Perlu Revisi 📝',
            default => 'Monetisasi Ditolak ⚠️',
        };

        $message = match ($this->status) {
            'approved' => 'Selamat! Pengajuan monetisasi cerita Anda telah disetujui.',
            'revisi' => 'Pengajuan monetisasi Anda memerlukan revisi: ' . ($this->reason ?? 'Periksa catatan admin.'),
            default => 'Pengajuan monetisasi Anda telah ditolak: ' . ($this->reason ?? 'Belum memenuhi kriteria.'),
        };

        return [
            'type' => 'monetize_status',
            'title' => $title,
            'message' => $message,
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }
}
