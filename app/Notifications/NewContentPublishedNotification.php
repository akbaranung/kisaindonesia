<?php

namespace App\Notifications;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContentPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $author,
        public Story $story,
        public ?Chapter $chapter = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $authorName = $this->author->pen_name ?? $this->author->name;
        $isChapter = !is_null($this->chapter);

        $subject = $isChapter
            ? "{$authorName} merilis bab baru: {$this->chapter->title}"
            : "{$authorName} menerbitkan cerita baru: {$this->story->title}";

        $url = $isChapter
            ? route('stories.chapter.read', [$this->story->slug, $this->chapter->slug])
            : route('stories.read', $this->story->slug);

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($isChapter
                ? "Penulis **{$authorName}** baru saja menambahkan bab **{$this->chapter->title}** pada cerita **{$this->story->title}**."
                : "Penulis **{$authorName}** baru saja memublikasikan cerita baru **{$this->story->title}**.")
            ->action('Baca Sekarang', $url)
            ->line('Selamat membaca!');
    }

    public function toArray(object $notifiable): array
    {
        $authorName = $this->author->pen_name ?? $this->author->name;
        $isChapter = !is_null($this->chapter);

        return [
            'type' => $isChapter ? 'new_chapter' : 'new_story',
            'title' => $isChapter ? $this->chapter->title : $this->story->title,
            'message' => $isChapter
                ? "{$authorName} memperbarui cerita \"{$this->story->title}\""
                : "{$authorName} merilis cerita baru!",
            'story_slug' => $this->story->slug ?? '',
            'chapter_slug' => $this->chapter->slug ?? '',
        ];
    }
}
