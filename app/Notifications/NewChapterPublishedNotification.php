<?php

namespace App\Notifications;

use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChapterPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter->loadMissing(['story', 'story.user']);
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $authorName = $this->chapter->story->user->name ?? 'Penulis';
        $storyTitle = $this->chapter->story->title ?? 'Cerita';
        $chapterTitle = $this->chapter->title;

        $readUrl = route('stories.chapter.read', [
            'story' => $this->chapter->story->slug,
            'chapter' => $this->chapter->slug
        ]);

        return (new MailMessage)
            ->subject("Bab Baru Rilis: {$storyTitle} - {$chapterTitle}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("**{$authorName}** baru saja menerbitkan bab terbaru.")
            ->line("**{$storyTitle}**")
            ->line("**{$chapterTitle}**")
            ->action('Baca Bab Sekarang', $readUrl)
            ->line('Selamat membaca dan dukung terus karya favorit Anda!')
            ->salutation('Salam, ' . config('app.name'));
    }

    public function toArray($notifiable): array
    {
        $authorName = $this->chapter->story->user->name ?? 'Penulis';
        $storyTitle = $this->chapter->story->title ?? 'Cerita';

        return [
            'chapter_id'    => $this->chapter->id,
            'chapter_title' => $this->chapter->title,
            'chapter_slug'  => $this->chapter->slug,
            'story_id'      => $this->chapter->story->id,
            'story_title'   => $storyTitle,
            'story_slug'    => $this->chapter->story->slug,
            'author_name'   => $authorName,
            'message'       => "{$authorName} menerbitkan bab baru: {$this->chapter->title}",
        ];
    }
}
