<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $commenter;
    public $story;
    public $commentText;

    public function __construct(User $commenter, $story, string $commentText)
    {
        $this->commenter = $commenter;
        $this->story = $story;
        $this->commentText = $commentText;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Komentar baru pada: ' . $this->story->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($this->commenter->name . ' berkomentar pada karya anda:')
            ->line('"' . $this->commentText . '"')
            ->action('Baca komentar', route('stories.chapter.read', [$this->story->slug, $this->story->chapter->slug]))
            ->line('Terima kasih!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'comment',
            'title' => $this->story->title,
            'message' => $this->commenter->name . ' mengomentari karya anda: "' . $this->story->title . '"',
            'story_slug' => $this->story->slug ?? '',
            'chapter_slug' => '',
        ];
    }
}
