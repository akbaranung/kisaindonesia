<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class FullNotificationList extends Component
{
    use WithPagination;

    public $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead($notificationId, $storySlug = null, $chapterSlug = null)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        if ($storySlug && $chapterSlug) {
            return redirect()->route('stories.chapter.read', [$storySlug, $chapterSlug]);
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }

    public function render()
    {
        $query = Auth::user()->notifications();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(10);

        return view('livewire.full-notification-list', [
            'notifications' => $notifications
        ]);
    }
}
