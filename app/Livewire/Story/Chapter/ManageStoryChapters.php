<?php

namespace App\Livewire\Story\Chapter;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\StoryCharacter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;


class ManageStoryChapters extends Component
{
    public Story $story;

    public $isCreateModalOpen = false;

    public $title = '';

    public $type = 'regular';

    public $status = 'draft';

    public function mount(Story $story)
    {
        $this->$story = $story;
    }

    public function openCreateModal()
    {
        $this->resetErrorBag();
        $this->title = '';
        $this->status = 'draft';
        $this->type = strtolower($this->story->type === 'puisi' ? 'regular' : $this->type);
        $this->isCreateModalOpen = true;
    }

    public function closeCreateModal()
    {
        $this->isCreateModalOpen = false;
    }

    public function createAndRedirect()
    {
        $this->validate(
            [
                'title' => 'required|string|max:255',
                'type' => 'required|in:regular,chat',
                'status' => 'required|in:draft,published',
            ],
            [
                'title.required' => 'Judul bab wajib diisi!',
                'type.required' => 'Tipe bab wajib dipilih!'
            ]
        );


        $filePath = null;

        try {
            $chapter = DB::transaction(function () use (&$filePath) {
                $nextOrder = ($this->story->chapters()->max('order_number') ?? 0) + 1;
                $folder = "chapters/story_{$this->story->id}";
                $filePath = "{$folder}/chap_{$nextOrder}_" . time() . ".json";

                if (!Storage::disk('local')->exists($folder)) {
                    Storage::disk('local')->makeDirectory($folder);
                }

                $initialContent = [
                    'type' => strtolower($this->type),
                    'bubbles' => [],
                    'content' => '',
                    'created_at' => now()->toDateTimeString()
                ];

                $saved = Storage::disk('local')->put($filePath, json_encode($initialContent, JSON_PRETTY_PRINT));

                if (!$saved) {
                    throw new \Exception("Sistem gagal menulis file json bab ke storage.");
                }

                return Chapter::create([
                    'story_id' => $this->story->id,
                    'title' => $this->title,
                    'slug' => Str::slug($this->title),
                    'file_path' => $filePath,
                    'word_count' => 0,
                    'order_number' => $nextOrder,
                    'is_premium' => ($this->story->monetization_type === 'premium' && $nextOrder > 5),
                    'bean_price' => 0,
                    'status' => $this->status,
                    'type' => $this->type
                ]);
            });

            return redirect()->route(
                'chapters.editor',
                [
                    'story' => $this->story->id,
                    'chapter' => $chapter->id
                ]
            );
        } catch (\Throwable $th) {
            if ($filePath && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }

            Log::error(
                'Gagal membuat bab baru: ' . $th->getMessage(),
                ['story_id' => $this->story->id ?? null, 'user_id' => auth()->id(), 'trace' => $th->getTraceAsString()]
            );

            $this->dispatch('show-toast', type: 'error', message: $th->getMessage());
            $this->closeCreateModal();
        }
    }

    public function render()
    {
        return view('livewire.stories.chapters.manage-story-chapters', [
            'chapters' => $this->story->chapters()->orderBy('order_number', 'asc')->get(),
        ])->layout('layouts.app');
    }
}
