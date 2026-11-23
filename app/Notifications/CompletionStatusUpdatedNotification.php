<?php

namespace App\Notifications;

use App\Models\CarouselSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompletionStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $status;
    public ?string $completionRejectReason;

    public function __construct(string $status, ?string $reason = null)
    {
        $this->status = $status;
        $this->completionRejectReason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable): array
    {
        $title = match ($this->status) {
            'approved' => 'Tamat Disetujui!',
            default => 'Pengajuan Tamat Ditolak',
        };

        $message = match ($this->status) {
            'approved' => 'Hore! Pengajuan tamat cerita Anda telah disetujui.',
            default => 'Pengajuan tamat cerita Anda telah ditolak: ' . ($this->completionRejectReason ?? 'Belum memenuhi kriteria.'),
        };

        return [
            'type' => 'completion_status',
            'title' => $title,
            'message' => $message,
            'status' => $this->status,
            'rejection_reason' => $this->completionRejectReason,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->status) {
            'approved' => 'Selamat! Pengajuan Tamat Cerita Anda Disetujui',
            default => 'Pemberitahuan: Pengajuan Tamat Cerita Ditolak',
        };

        $mail = (new MailMessage)->subject($subject)->greeting('Halo, ' . $notifiable->name . '!');

        if ($this->status === 'approved') {
            $mail->line('Pengajuan tamat cerita anda telah **DISETUJUI**.')
                ->line('**PENTING : ** Harap diperhatikan bahwa persetujuan tamat bersifat mengikat dan harus dilakukan sesuai dengan seluruh aturan serta regulasi internal KISA Indonesia yang berlaku.')
                ->action('Buka Dashboard', route('my-stories'));
        } else {
            $mail->line('Pengajuan tamat cerita Anda **Ditolak** ')
                ->line('**Catatan dari Admin:** ' . ($this->completionRejectReason ?? 'Harap tinjau kembali naskah cerita.'))
                ->line('Anda dapat memperbaiki bab cerita dan mengajukan ulang melalui halaman kelola bab cerita.')
                ->action('Buka Studio Cerita', route('my-stories'));
        }
        return $mail;
    }
}
