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

    public function markAsRead($notificationId, $storySlug = null, $chapterSlug = null)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
            }
        }

        if ($storySlug && $chapterSlug) {
            return redirect()->route('stories.chapter.read', [$storySlug, $chapterSlug]);
        }

        $this->loadUnreadCount();
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
