<?php

namespace App\Livewire;

use App\Models\Story;
use Livewire\Component;

class UpdateStoryStatus extends Component
{
    public Story $story;
    public string $status;

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->status = $story->status; // 'draft' atau 'published'
    }

    public function submitForReview()
    {
        $chapterCount = $this->story->chapters()->where('status', 'published')->count();
        if ($chapterCount < 5) {
            session()->flash('error', 'Cerita minimal harus memiliki 5 bab sebelum diajukan untuk publish!');
            return;
        }

        if (in_array($this->story->status, ['draft', 'rejected'])) {
            $this->story->update([
                'status' => 'pending_review',
                'rejection_reason' => null
            ]);

            session()->flash('succcess', 'Pengajuan publish berhasil dikirim! Menunggu review Admin.');
            $this->dispatch('storyStatusUpdated');
        }
    }

    public function setStatus(string $newStatus)
    {
        if (!in_array($newStatus, ['draft', 'published'])) {
            return;
        }

        // Opsi validasi: Cegah publish jika cerita belum punya bab yang terbit
        if ($newStatus === 'published') {
            $hasPublishedChapter = $this->story->chapters()->where('status', 'published')->exists();

            if (!$hasPublishedChapter) {
                $this->dispatch('show-toast', type: 'error', message: 'Cerita belum bisa dipublikasikan! Terbitkan minimal 1 bab terlebih dahulu.');
                return;
            }
        }

        $oldStatus = $this->story->status;

        $this->story->update([
            'status' => $newStatus
        ]);

        $this->status = $newStatus;

        if ($oldStatus !== 'published' && $newStatus === 'published') {
            $penName = $this->story->penName ?? null;
            if ($penName) {
                $followers = $penName->followers()->get();
                if ($followers->isNotEmpty()) {
                    $authorUser = $this->story->user;
                    \Illuminate\Support\Facades\Notification::send(
                        $followers,
                        new \App\Notifications\NewContentPublishedNotification($authorUser, $this->story)
                    );
                }
            }
        }

        $message = $newStatus === 'published'
            ? 'Cerita berhasil dipublikasikan dan sekarang dapat dibaca publik!'
            : 'Status cerita diubah menjadi Draf (tersembunyi dari publik).';

        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function render()
    {
        $chapterCount = $this->story->chapters_count ?? $this->story->chapters()->where('status', 'published')->count();
        return view('livewire.stories.update-story-status', [
            'chapterCount' => $chapterCount,
            'canSubmit' => $chapterCount >= 5,
        ]);
    }
}
