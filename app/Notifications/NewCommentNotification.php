<?php

namespace App\Notifications;

use App\Models\Chapter;
use App\Models\Story;
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
    public $chapter;
    public bool $isReply;

    public function __construct(User $commenter, Story $story, string $commentText, ?Chapter $chapter = null, bool $isReply = false)
    {
        $this->commenter = $commenter;
        $this->story = $story;
        $this->commentText = $commentText;
        $this->chapter = $chapter;
        $this->isReply = $isReply;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = ($this->chapter && !empty($this->chapter->slug))
            ? route('stories.chapter.read', [$this->story->slug, $this->chapter->slug])
            : route('stories.read', $this->story->slug);

        $subject = $this->isReply
            ? 'Balasan komentar baru pada: ' . $this->story->title
            : 'Komentar baru pada: ' . $this->story->title;

        $actionText = $this->isReply
            ? $this->commenter->name . ' membalas komentar anda:'
            : $this->commenter->name . ' berkomentar pada karya anda:';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($actionText)
            ->line('"' . $this->commentText . '"')
            ->action('Baca komentar', $url)
            ->line('Terima kasih!');
    }

    public function toArray(object $notifiable): array
    {
        $message = $this->isReply
            ? $this->commenter->name . ' membalas komentar anda pada cerita "' . $this->story->title . '"'
            : $this->commenter->name . ' mengomentari karya anda: "' . $this->story->title . '"';

        return [
            'type' => 'comment',
            'title' => $this->isReply ? 'Balasan Komentar' : 'Komentar Baru',
            'message' => $message,
            'story_slug' => $this->story->slug ?? '',
            'chapter_slug' => $this->chapter->slug ?? '',
        ];
    }
}
