<?php

namespace App\Livewire\Story;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\StoryCharacter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StoryChapters extends Component
{
    use WithFileUploads;

    public $storyId;

    public $story;

    public $chapterAction = 'list';

    public $selectedChapterId = null;

    public $type = 'regular';

    public $content = '';

    public $status = 'draft';

    public $inputType = 'chat';

    public $inputCharacterId = '';

    public $inputPosition = 'left';

    public $inputMessage = '';

    public $inputDuration = '';

    public $inputImage = null;

    public $chatRows = [];

    public $chapterTitle = '';

    public $charName = '';

    public $charPosition = 'left';

    public $editingIndex = null;

    public $deletedImages = [];

    public $isCreateModalOpen = false;

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->storyId = $story->id;
        $this->loadStoryData();
    }

    public function loadStoryData()
    {
        $this->story->load(['chapters', 'characters']);
    }

    public function switchChapterAction($target)
    {
        $this->chapterAction = $target;
        if ($target === 'editor' && ! $this->selectedChapterId) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->selectedChapterId = null;
        $this->chapterTitle = '';
        $this->type = 'regular';
        $this->status = 'draft';
        $this->content = '';
        $this->chatRows = [];
        $this->resetInputBar();
    }

    public function resetInputBar()
    {
        $this->inputType = 'chat';
        $this->inputMessage = '';
        $this->inputDuration = '';
        $this->editingIndex = null;
        $this->inputImage = null;
    }

    public function openCreateModal()
    {
        $this->isCreateModalOpen = true;
    }

    public function closeCreateModal()
    {
        $this->isCreateModalOpen = false;
    }

    public function submitNewRow()
    {
        // Validasi minimal jika tipenya bukan narasi, wajib pilih tokoh
        if ($this->inputType !== 'description' && empty($this->inputCharacterId)) {
            $this->addError('inputCharacterId', 'Pilih tokoh dulu.');

            return;
        }

        if ($this->inputType === 'description' && empty($this->inputMessage)) {
            $this->addError('inputMessage', 'Isi narasi tidak boleh kosong');

            return;
        }

        if ($this->inputType === 'image') {
            $imageRule = ($this->editingIndex !== null) ? 'nullable|image|max:2048' : 'required|image|max:2048';

            $this->validate([
                'inputCharacterId' => 'required',
                'inputImage' => $imageRule,
            ], [
                'inputCharacterId.required' => 'Pilih tokoh pengirim gambar!',
                'inputImage.required' => 'Pilih gambar yang ingin dikirim!',
                'inputImage.image' => 'File harus berupa gambar (png, jpg, jpeg, webp)!',
                'inputImage.max' => 'Ukuran gambar maksimal 2MB!',
            ]);

            $rowData = [
                'type' => 'image',
                'character_id' => $this->inputCharacterId,
                'position' => $this->inputPosition,
                'message' => $this->inputMessage,
            ];

            if ($this->editingIndex !== null) {
                $oldRow = $this->chatRows[$this->editingIndex];

                $rowData['image_temp'] = $oldRow['image_temp'] ?? null;
                $rowData['image_path'] = $oldRow['image_path'] ?? null;

                // Jika user mengunggah gambar BARU saat edit
                if ($this->inputImage) {
                    $rowData['image_temp'] = $this->inputImage;
                }

                $this->chatRows[$this->editingIndex] = $rowData;
            } else {
                $rowData['image_temp'] = $this->inputImage;
                $rowData['image_path'] = null;
                $this->chatRows[] = $rowData;
            }

            // Reset input gambar
            $this->resetInputBar();

            return;
        }

        $rowData = [
            'type' => $this->inputType,
            'character_id' => $this->inputType !== 'description' ? $this->inputCharacterId : null,
            'position' => $this->inputType === 'chat' ? $this->inputPosition : 'left',
            'message' => $this->inputType !== 'call_incoming' && $this->inputType !== 'call_outgoing' && $this->inputType !== 'call_missed' ? $this->inputMessage : '',
            'duration' => in_array($this->inputType, ['call_incoming', 'call_outgoing']) ? $this->inputDuration : '',
        ];

        if ($this->editingIndex !== null) {
            // UPDATE INDEX LAMA
            $this->chatRows[$this->editingIndex] = $rowData;
        } else {
            // TAMBAH INDEX BARU
            $this->chatRows[] = $rowData;
        }

        // Bersihkan kotak input pesan teks kembali kosong
        $this->resetInputBar();
        $this->resetErrorBag();
    }

    public function removeRowFromPreview($index)
    {

        if (! isset($this->chatRows[$index])) {
            return;
        }

        $rowToBeDeleted = $this->chatRows[$index];

        if (! empty($rowToBeDeleted['image_path'])) {
            $this->deletedImages[] = $rowToBeDeleted['image_path'];
        }

        if ($this->editingIndex === $index) {
            $this->cancelEdit();
        } elseif ($this->editingIndex !== null && $this->editingIndex > $index) {
            $this->editingIndex--;
        }

        unset($this->chatRows[$index]);

        $this->chatRows = array_values($this->chatRows);
    }

    public function editRowFromPreview($index)
    {

        if (! isset($this->chatRows[$index])) {
            return;
        }

        $row = $this->chatRows[$index];

        // set index yang sedang diedit
        $this->editingIndex = $index;

        // Kembalikan ke variabel input bar tunggal
        $this->inputType = $row['type'] ?? 'chat';
        $this->inputCharacterId = $row['character_id'] ?? '';
        $this->inputPosition = $row['position'] ?? 'left';
        $this->inputMessage = $row['message'] ?? '';
        $this->inputDuration = $row['duration'] ?? '';

        $this->inputImage = null;
    }

    public function cancelEdit()
    {
        $this->resetInputBar();
    }

    public function addCharacter()
    {
        $this->validate(['charName' => 'required|string|max:50']);

        StoryCharacter::create([
            'story_id' => $this->storyId,
            'name' => $this->charName,
            'avatar_path' => null, // Sederhanakan tanpa file upload dulu untuk minimalisir bug lag
            'default_position' => $this->charPosition,
        ]);

        $this->charName = '';
        $this->loadStoryData(); // Reload master relasi tokoh
    }

    public function addChatRow()
    {
        $this->chatRows[] = [
            'type' => 'chat',
            'character_id' => '',
            'position' => 'left',
            'message' => '',
            'duration' => '',
        ];
    }

    public function removeChatRow($index)
    {
        unset($this->chatRows[$index]);
        $this->chatRows = array_values($this->chatRows); // Reset index array
    }

    public function viewChapter($id)
    {
        $chapter = Chapter::findOrFail($id);
        $this->selectedChapterId = $chapter->id;
        $this->chapterTitle = $chapter->title;
        $this->type = $chapter->type;
        $this->status = $chapter->status;

        if ($chapter->type === 'chat') {
            // Ambil data JSON chat, jika kosong berikan array kosong
            $this->chatRows = is_array($chapter->content) ? $chapter->content : [];
        } else {
            $this->content = $chapter->content;
        }

        // Alihkan action ke halaman view detail
        $this->chapterAction = 'view';
    }

    public function saveChapter()
    {
        $this->validate([
            'chapterTitle' => 'required|string|max:255',
            'type' => 'required',
            'status' => 'required',
        ]);

        if (! empty($this->deletedImages)) {
            foreach ($this->deletedImages as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            // Reset daftar hapus
            $this->deletedImages = [];
        }

        foreach ($this->chatRows as $key => $row) {
            if (($row['type'] ?? '') === 'image') {
                if (isset($row['image_temp']) && is_object($row['image_temp'])) {
                    $this->chatRows[$key]['image_path'] = $row['image_temp']->store('chat-images', 'public');
                    unset($this->chatRows[$key]['image_temp']);
                }
            }
        }

        $isPremium = false;
        $price = 0;
        $orderNumber = $this->selectedChapterId ? Chapter::find($this->selectedChapterId)->order_number : (Chapter::where('story_id', $this->storyId)->max('order_number') + 1);

        if ($this->story->monetization_type === 'premium' && $orderNumber > 5) {
            $isPremium = true;
            $type = strtolower($this->type ?? 'regular');
            $minWords = ($type === 'puisi') ? 700 : 1000;
            $maxWords = 1500;

            $chapterTemp = new Chapter(['content' => ($this->type === 'chat') ? $this->chatRows : $this->content]);
            $price = $chapterTemp->calculateKisaBean();
            $wordCount = $chapterTemp->calculateWordCount();

            if ($wordCount < $minWords) {
                $this->addError('content', "Karena cerita ini berstatus Premium, Bab 6 ke atas wajib memiliki minimal {$minWords} kata murni. (Saat ini: {$wordCount} kata)");

                return;
            }

            if ($wordCount > $maxWords) {
                $this->addError('content', "Batas maksimal kata per bab premium adalah {$maxWords} kata. (Saat ini: {$wordCount} kata)");

                return;
            }
        }

        Chapter::updateOrCreate(
            ['id' => $this->selectedChapterId],
            [
                'story_id' => $this->storyId,
                'title' => $this->chapterTitle,
                'slug' => \Illuminate\Support\Str::slug($this->chapterTitle),
                'type' => $this->type,
                'content' => ($this->type === 'chat') ? $this->chatRows : $this->content,
                'status' => $this->status,
                'order_number' => $orderNumber,
                'is_premium' => $isPremium,
                'bean_price' => $price,
            ]
        );

        session()->flash('success', 'Bab berhasil diamankan! ✨');
        $this->loadStoryData();
        $this->chapterAction = 'list';
        $this->resetForm();
    }

    public function editChapter($id)
    {
        $chapter = Chapter::findOrFail($id);
        $this->selectedChapterId = $chapter->id;
        $this->chapterTitle = $chapter->title;
        $this->type = $chapter->type;
        $this->status = $chapter->status;

        if ($chapter->type === 'chat') {
            $this->chatRows = is_array($chapter->content) ? $chapter->content : [];
        } else {
            $this->content = $chapter->content;
        }
        $this->chapterAction = 'editor';
    }

    public function render()
    {
        $this->story->load('characters');

        return view('livewire.stories.chapters.story-chapters');
    }
}
