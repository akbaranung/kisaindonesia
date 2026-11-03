<?php

namespace App\Notifications;

use App\Models\CarouselSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarouselSubmissionApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public CarouselSubmission $submission) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Carousel Disetujui')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Pengajuan banner carousel kamu telah disetujui dan sudah tayang.')
            ->line('Tayang hingga: ' . $this->submission->expires_at->format('d M Y H:i'))
            ->action('Lihat Pengajuan', url('promotions'))
            ->line('Terima kasih telah menggunakan Kisa Indonesia.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'carousel_approved',
            'submission_id' => $this->submission->id,
            'message'       => 'Pengajuan carousel kamu disetujui dan tayang hingga '
                . $this->submission->expires_at->format('d M Y H:i'),
        ];
    }
}
