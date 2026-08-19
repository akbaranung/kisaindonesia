<?php

namespace App\Services;

use App\Models\Story;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StoryViewService
{
    public function incrementView(Chapter $chapter): void
    {
        $authorId = $chapter->story->user_id;

        // 1. ABAIKAN jika pengguna yang membuka adalah PENULIS CERITA ITU SENDIRI
        if (Auth::check() && Auth::id() === $authorId) {
            return;
        }

        $sessionKeyChapter = 'viewed_chapter_' . $chapter->id;
        $sessionKeyStory   = 'viewed_story_' . $chapter->story_id;

        if (!Session::has($sessionKeyChapter)) {
            $chapter->increment('views_count');

            Session::put($sessionKeyChapter, now()->timestamp);
        }

        if (!Session::has($sessionKeyStory)) {
            $chapter->story()->increment('views_count');

            Session::put($sessionKeyStory, now()->timestamp);
        }
    }

    public function incrementStoryView(Story $story): void
    {
        if (Auth::check() && Auth::id() === $story->user_id) {
            return;
        }

        $sessionKeyStory = 'viewed_story_' . $story->id;

        if (!Session::has($sessionKeyStory)) {
            $story->increment('views_count');
            Session::put($sessionKeyStory, now()->timestamp);
        }
    }
}
