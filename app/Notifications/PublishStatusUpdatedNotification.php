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
        $title = match ($this->status) {
            'approved' => 'Publikasi Disetujui! 🎉',
            'revisi' => 'Publikasi Perlu Revisi 📝',
            default => 'Pengajuan Publish Ditolak ⚠️',
        };

        $message = match ($this->status) {
            'approved' => 'Hore! Pengajuan publikasi cerita Anda telah disetujui dan resmi dipublikasikan.',
            'revisi' => 'Pengajuan publikasi cerita Anda memerlukan revisi: ' . ($this->rejectionReason ?? 'Periksa catatan admin.'),
            default => 'Pengajuan publikasi cerita Anda telah ditolak: ' . ($this->rejectionReason ?? 'Belum memenuhi kriteria.'),
        };

        return [
            'type' => 'publish_status',
            'title' => $title,
            'message' => $message,
            'status' => $this->status,
            'rejection_reason' => $this->rejectionReason,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->status) {
            'approved' => 'Selamat! Pengajuan Publish Cerita Anda Disetujui',
            'revisi' => 'Perlu Revisi: Pengajuan Publish Cerita',
            default => 'Pemberitahuan: Pengajuan Publish Cerita Ditolak',
        };

        $mail = (new MailMessage)->subject($subject)->greeting('Halo, ' . $notifiable->name . '!');

        if ($this->status === 'approved') {
            $mail->line('Pengajuan publish cerita anda telah **DISETUJUI**.')
                ->line('**PENTING : ** Harap diperhatikan bahwa persetujuan publish bersifat mengikat dan harus dilakukan sesuai dengan seluruh aturan serta regulasi internal KISA Indonesia yang berlaku.')
                ->action('Buka Dashboard', route('my-stories'));
        } elseif ($this->status === 'revisi') {
            $mail->line('Pengajuan publish cerita Anda memerlukan **REVISI** sebelum dapat dipublikasikan.')
                ->line('**Catatan Revisi dari Admin:** ' . ($this->rejectionReason ?? 'Harap tinjau kembali naskah cerita.'))
                ->line('Anda dapat memperbaiki bab cerita dan mengajukan ulang melalui halaman kelola bab cerita.')
                ->action('Buka Studio Cerita', route('my-stories'));
        } else {
            $mail->line('Pengajuan publish cerita Anda telah **DITOLAK** dan tidak dapat diajukan kembali.')
                ->line('**Alasan Penolakan:** ' . ($this->rejectionReason ?? 'Belum memenuhi syarat.'))
                ->action('Buka Dashboard', route('my-stories'));
        }

        return $mail;
    }
}
