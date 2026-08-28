<?php

namespace App\Livewire\Story\Chapter;

use App\Models\Chapter;
use App\Models\Comment;
use Livewire\Component;

class ChapterComments extends Component
{
    public Chapter $chapter;
    public string $body = '';
    public ?int $replyToId = null;
    public string $replyBody = '';

    protected function rules()
    {
        return [
            'body' => 'required|string|min:2|max:1000',
        ];
    }

    public function postComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->chapter->comments()->create([
            'user_id' => auth()->id(),
            'body' => trim($this->body),
        ]);

        $this->reset('body');
        session()->flash('comment_status', 'Komentar berhasil dikirim!');
    }

    public function setReply(int $commentId)
    {
        $this->replyToId = $this->replyToId === $commentId ? null : $commentId;
        $this->reset('replyBody');
    }

    public function postReply(int $parentId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'replyBody' => 'required|string|min:2|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'chapter_id' => $this->chapter->id,
            'body' => trim($this->replyBody),
            'parent_id' => $parentId,
        ]);

        $this->reset(['replyToId', 'replyBody']);
    }

    public function deleteComment(int $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id === auth()->id()) {
            $comment->delete();
        }
    }

    public function render()
    {
        $comments = $this->chapter->comments()
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return view('livewire.stories.chapters.chapter-comments', [
            'comments' => $comments,
        ]);
    }
}
