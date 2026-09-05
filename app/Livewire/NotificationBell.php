<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadUnreadCount();
    }

    public function loadUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = Auth::user()->unreadNotifications->count();
        }
    }

    public function markAsRead($notificationId, $storySlug = null, $chapterSlug = null, $type = 'story')
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->loadUnreadCount();
            }

            $data = $notification->data;
            $type = $data['type'] ?? 'story';
            $storySlug = $data['story_slug'] ?? null;
            $chapterSlug = $data['chapter_slug'] ?? null;

            // Navigasi URL berdasarkan tipe notifikasi
            return match ($type) {
                'follow' => null,
                'monetize_request' => redirect()->to('/admin/premium-requests'),
                'monetize_status' => redirect()->route('my-stories'),
                'new_chapter' => redirect()->route('stories.chapter.read', [$storySlug, $chapterSlug]),
                'new_story' => redirect()->route('stories.read', [$storySlug]),
                'comment' => !empty($chapterSlug)
                    ? redirect()->route('stories.chapter.read', [$storySlug, $chapterSlug])
                    : redirect()->route('stories.read', [$storySlug]),
                default => match (true) {
                    !empty($storySlug) && !empty($chapterSlug) => redirect()->route('stories.chapter.read', [$storySlug, $chapterSlug]),
                    !empty($storySlug) => redirect()->route('stories.read', [$storySlug]),
                    default => null,
                },
            };
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications()->markAsRead();
            $this->loadUnreadCount();
        }
    }

    public function render()
    {
        $notifications = Auth::check()
            ? Auth::user()->notifications()->latest()->take(5)->get()
            : collect();

        return view('livewire.notification-bell', [
            'notifications' => $notifications
        ]);
    }
}
