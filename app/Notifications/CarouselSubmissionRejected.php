<?php

namespace App\Notifications;

use App\Models\CarouselSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarouselSubmissionRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CarouselSubmission $submission,
        public string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Carousel Ditolak')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Mohon maaf, pengajuan banner carousel kamu ditolak.')
            ->line('Alasan: ' . $this->reason)
            ->line($this->submission->cost_in_beans . ' Kisa Bean telah dikembalikan ke saldo kamu.')
            ->action('Ajukan Ulang', url('promotions'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'carousel_rejected',
            'submission_id' => $this->submission->id,
            'reason'        => $this->reason,
            'message'       => 'Pengajuan carousel ditolak. ' . $this->submission->cost_in_beans
                . ' Kisa Bean dikembalikan.',
        ];
    }
}
