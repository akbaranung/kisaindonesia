<?php

namespace App\Livewire\Story\Chapter;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\StoryCharacter;
use App\Notifications\NewChapterPublishedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ChapterEditor extends Component
{
    use WithFileUploads;

    public Story $story;
    public Chapter $chapter;


    // form temporary state
    public $editingIndex = null;
    public $character_id = null;
    public $message_type = 'text'; // text|image|call
    public $message = '';
    public $call_type = 'incoming'; // incoming|outgoing|missed
    public $call_duration = '00:00';

    public $image_upload = null;
    public $existing_image_url = null;

    public array $imagesToDelete = [];

    // Menampung karakter
    public $characters = [];

    // form dan data chapter
    public string $title = '';
    public $slug;
    public $status = 'draft';
    public string $type = 'chat';
    public array $bubbles = [];
    public string $regularContent = '';
    public $orderNumber;

    // Kelola karakter cerita
    public bool $isCharacterModalOpen = false;
    public $editingCharacterId = null;

    public string $char_name = '';
    public string $char_position = 'left';
    public $char_avatar_upload = null;
    public $char_existing_avatar = null;


    public function mount(Story $story, Chapter $chapter)
    {
        $this->story = $story;

        $this->chapter = $chapter;
        $this->title = $chapter->title;
        $this->status = $chapter->status;
        $this->slug = $chapter->slug;
        $this->type = $chapter->type;
        $this->orderNumber = $chapter->order_number ?? $this->story->chapters()->max('order_number') + 1;

        // ambil isi file json dari model lewat acessor content
        $contentData = $chapter->parseJsonData();
        $this->bubbles = $contentData['bubbles'] ?? [];
        $this->regularContent = $contentData['content'] ?? '';

        // daftar karakter untuk cerita ini
        $this->characters = StoryCharacter::where('story_id', $this->story->id)->get();

        if ($this->characters->isNotEmpty()) {
            $this->character_id = $this->characters->first()->id;
        }
    }

    public function saveBubble()
    {

        $rules = [
            'character_id' => 'required_unless:message_type,center_text|nullable|exists:story_characters,id',
            'message_type' => 'required|in:text,image,call,center_text',
        ];

        if ($this->message_type === 'text' || $this->message_type === 'center_text') {
            $rules['message'] = 'required|string|max:1000';
        } elseif ($this->message_type === 'image') {
            if ($this->editingIndex === null && !$this->image_upload && empty($this->exisiting_image_url)) {
                $rules['image_upload'] = 'required|image|max:2048';
            } else {
                $rules['image_upload'] = 'nullable|image|max:2048';
            }
        } elseif ($this->message_type === 'call') {
            $rules['call_type'] = 'required|in:incoming,outgoing,missed';
            $rules['call_duration'] = 'required|string|max:20';
        }

        $messages = [
            'character_id.required_unless' => 'Silakan pilih tokoh/karakter terlebih dahulu!',
            'character_id.exists' => 'Tokoh/karakter tidak ditemukan!',
            'message.required' => 'Isi pesan tidak boleh kosong!'
        ];

        $validator = Validator::make([
            'character_id'       => $this->character_id,
            'message_type'       => $this->message_type,
            'message'            => $this->message,
            'image_upload'       => $this->image_upload,
            'existing_image_url' => $this->existing_image_url,
            'call_type'          => $this->call_type,
            'call_duration'      => $this->call_duration,
        ], $rules, $messages);

        // 4. Jika Validasi Gagal: Ambil 1 Error Pertama & Kirim Toast
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();

            // Dispatch event untuk ditangkap SweetAlert2 / Toastify / Alpine
            $this->dispatch('show-toast', type: 'error', message: $firstError);
            return;
        }

        try {
            $bubbleData = [
                'id' => $this->editingIndex !== null ? ($this->bubbles[$this->editingIndex]['id'] ?? uniqid()) : uniqid(),
                'character_id' => $this->message_type === 'center_text' ? null : $this->character_id,
                'message_type' => $this->message_type,
                'timestamp' => now()->format('H:i'),
            ];

            if ($this->message_type === 'text' || $this->message_type === 'center_text') {
                $bubbleData['message'] = $this->message;
            } elseif ($this->message_type === 'image') {
                $bubbleData['image_upload']       = $this->image_upload;
                $bubbleData['existing_image_url'] = $this->existing_image_url ?? null;
                $bubbleData['caption']            = $this->message;

                if ($this->editingIndex !== null && $this->image_upload && !empty($this->existing_image_url)) {
                    $this->imagesToDelete[] = $this->existing_image_url;
                    $bubbleData['existing_image_url'] = null; // Clear path lama
                }
            } elseif ($this->message_type === 'call') {
                $bubbleData['call_type'] = $this->call_type;
                $bubbleData['call_duration'] = $this->call_duration;
            }

            if ($this->editingIndex !== null) {
                $this->bubbles[$this->editingIndex] = $bubbleData;
            } else {
                $this->bubbles[] = $bubbleData;
            }

            // Reset Form State
            $this->resetBubbleForm();
        } catch (\Throwable $th) {
            Log::error('Error saveBubble: ' . $th->getMessage());
            session()->flash('warning', 'Gagal menyimpan gelembung chat: ' . $th->getMessage());
        }
    }

    public function editBubble(int $index)
    {
        if (!isset($this->bubbles[$index])) return;

        $bubble = $this->bubbles[$index];
        $this->editingIndex       = $index;
        $this->character_id       = $bubble['character_id'] ?? null;
        $this->message_type       = $bubble['message_type'] ?? 'text';
        $this->message            = $bubble['message'] ?? ($bubble['caption'] ?? '');
        $this->existing_image_url = $bubble['existing_image_url'] ?? $bubble['image_url'] ?? null;
        $this->call_type          = $bubble['call_type'] ?? 'incoming';
        $this->call_duration      = $bubble['call_duration'] ?? '00:00';
        $this->image_upload       = null;
    }


    public function deleteBubble(int $index)
    {
        if (!isset($this->bubbles[$index])) return;

        $bubble = $this->bubbles[$index];

        // Jika bubble gambar permanen dihapus, tandai filenya untuk dihapus saat Simpan Bab
        $imageUrl = $bubble['existing_image_url'] ?? ($bubble['image_url'] ?? null);
        if ($imageUrl) {
            $this->imagesToDelete[] = $imageUrl;
        }

        // Hapus dari array memory
        array_splice($this->bubbles, $index, 1);
    }

    public function openAddCharacterModal()
    {
        $this->resetCharacterForm();
        $this->isCharacterModalOpen = true;
    }

    public function openEditCharacterModal($characterId)
    {
        $char = StoryCharacter::find($characterId);
        if (!$char) return;

        $this->resetCharacterForm();
        $this->editingCharacterId = $char->id;
        $this->char_name = $char->name;
        $this->char_position = $char->default_position ?? 'left';
        $this->char_existing_avatar = $char->avatar;

        $this->isCharacterModalOpen = true;
    }

    public function closeCharacterModal()
    {
        $this->isCharacterModalOpen = false;
        $this->resetCharacterForm();
    }

    public function saveQuickCharacter()
    {
        $this->validate([
            'char_name' => 'required|string|max:50',
            'char_position' => 'required|in:left,right',
            'char_avatar_upload' => 'nullable|image|max:2048',
        ], [
            'char_name.required' => 'Nama karakter wajib diisi!',
            'char_avatar_upload.image' => 'Avatar harus berupa file gambar!',
            'char_avatar_upload.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            $avatarPath = $this->char_existing_avatar;

            if ($this->char_avatar_upload) {
                if ($this->char_existing_avatar && Storage::disk('public')->exists($this->char_avatar)) {
                    Storage::disk('public')->delete($this->char_existing_avatar);
                }

                $avatarPath = $this->char_avatar_upload->store("characters/story_{$this->story->id}", 'public');
            }

            if ($this->editingCharacterId) {
                $char = StoryCharacter::find($this->editingCharacterId);
                $char->update([
                    'name' => trim($this->char_name),
                    'default_position' => $this->char_position,
                    'avatar_path' => $avatarPath
                ]);
            } else {
                $char = StoryCharacter::create([
                    'story_id' => $this->story->id,
                    'name' => trim($this->char_name),
                    'default_position' => $this->char_position,
                    'avatar_path' => $avatarPath
                ]);
            }

            $this->characters = StoryCharacter::where('story_id', $this->story->id)->get();
            $this->character_id = $char->id;

            $this->closeCharacterModal();
            session()->flash('message', 'Data karakter berhasil di perbarui!');
        } catch (\Throwable $th) {
            Log::error('Error saveQuickCharacter: ' . $th->getMessage());
            session()->flash('warning', 'Gagal menyimpan karakter: ' . $th->getMessage());
        }
    }

    public function resetCharacterForm()
    {
        $this->editingCharacterId   = null;
        $this->char_name            = '';
        $this->char_position        = 'left';
        $this->char_avatar_upload   = null;
        $this->char_existing_avatar = null;
        $this->existing_image_url = null;
        $this->resetErrorBag();
    }

    public function saveChapter()
    {

        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
            'type' => 'required|in:regular,puisi,chat'
        ];

        $messages = [
            'title.required' => 'Judul bab wajib diisi!',
            'status.required' => 'Status bab wajib dipilih!',
            'regularContent.required' => 'Isi content chapter tidak boleh kosong!',
            'regualrContent.min' => 'Konten chapter minimal 5 karakter!',
            'bubbles.required' => 'Tambahkan minimal 1 pesan chat!',
            'bubbles.min' => 'Tambahkan minimal 1 pesan chat!'
        ];

        if (in_array($this->type, ['regular', 'puisi'])) {
            $rules['regularContent'] = 'required|string|min:5';
        } else {
            $rules['bubbles'] = 'required|array|min:1';
        }

        $validator = Validator::make([
            'title'          => $this->title,
            'status'         => $this->status,
            'type'           => $this->type,
            'regularContent' => $this->regularContent,
            'bubbles'        => $this->bubbles,
        ], $rules, $messages);

        if ($validator->fails()) {
            $this->dispatch('show-toast', type: 'error', message: $validator->errors()->first());
            return;
        }

        $isPremium = false;
        if ($this->story->monetization_type === 'premium' && $this->orderNumber > 5) {
            $isPremium = true;
            $premiumError = $this->validatePremiumChapterRequirements();
            if ($premiumError) {
                $this->dispatch('show-toast', type: 'error', message: $premiumError);
                return;
            }
        }

        try {
            foreach ($this->bubbles as $index => &$bubble) {
                if (($bubble['message_type'] ?? '') === 'image') {
                    // Jika ada file temporary yang diunggah
                    if (isset($bubble['image_upload']) && $bubble['image_upload']) {
                        // Simpan permanen ke disk public
                        $path = $bubble['image_upload']->store("chat-fic/story_{$this->story->id}", 'public');

                        // Set path permanen
                        $bubble['image_url'] = $path;
                    } else {
                        // Tetap gunakan path permanen yang sudah ada
                        $bubble['image_url'] = $bubble['existing_image_url'] ?? ($bubble['image_url'] ?? null);
                    }

                    // Bersihkan properti temporary agar file JSON tidak membengkak
                    unset($bubble['image_upload']);
                    unset($bubble['existing_image_url']);
                }
            }
            unset($bubble); // Unset reference

            // B. Hapus Fisik Gambar Lama yang Ditandai untuk Dihapus
            foreach ($this->imagesToDelete as $oldImagePath) {
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
            $this->imagesToDelete = [];

            // Update Judul Bab di Database
            $this->chapter->update([
                'title'      => trim($this->title),
                'word_count' => $this->calculateWordCount(),
                'slug'         => $this->slug ?: Str::slug($this->title),
                'status'       => $this->status,
                'is_premium' => $isPremium,
                'bean_price' => $this->calculateKisaBean()
            ]);

            // Simpan Ke File JSON
            $this->saveChapterFile();

            if ($this->status === 'published') {
                $followers = $this->chapter->story->user->followers;

                if ($followers->isNotEmpty()) {
                    Notification::send($followers, new NewChapterPublishedNotification($this->chapter));
                }
            }

            $this->dispatch('show-toast', type: 'success', message: 'Seluruh perubahan bab berhasil disimpan!');
        } catch (\Throwable $th) {
            Log::error('Error saveChapter: ' . $th->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'Gagal menyimpan bab: ' . $th->getMessage());
        }
    }

    private function saveChapterFile(): bool
    {
        $payload = [
            'type'       => $this->type,
            'bubbles'    => $this->bubbles,
            'content'    => $this->regularContent,
            'updated_at' => now()->toDateTimeString(),
        ];

        return $this->chapter->saveContent($payload);
    }

    private function validatePremiumChapterRequirements(): ?string
    {
        if ($this->type === 'chat') {
            $minBubbles = 30;
            $maxBubbles = 100;
            $currentBubbles = $this->calculateWordCount();

            if ($currentBubbles < $minBubbles) {
                return "Bab Chat Premium minimal harus memiliki {$minBubbles} gelembung percakapan! (Saat ini: {$currentBubbles} bubble)";
            }

            if ($currentBubbles > $maxBubbles) {
                return "Batas maksimal gelembung percakapan untuk bab premium adalah {$maxBubbles} bubble! (Saat ini: {$currentBubbles} bubble)";
            }
        } else {
            $minWords = ($this->type === 'puisi') ? 700 : 1000;
            $maxWords = 1500;
            $currentWords = $this->calculateWordCount();

            if ($currentWords < $minWords) {
                return "Karena cerita ini berstatus Premium, Bab 6 ke atas wajib memiliki minimal {$minWords} kata! (Saat ini: {$currentWords} kata)";
            }

            if ($currentWords > $maxWords) {
                return "Batas maksimal kata per bab premium adalah {$maxWords} kata! (Saat ini: {$currentWords} kata)";
            }
        }

        return null;
    }

    private function calculateKisaBean(): int
    {
        $isPremium = ($this->story->monetization_type === 'premium' && $this->orderNumber > 5);
        if (!$isPremium) {
            return 0;
        }

        if ($this->type === 'chat') {
            $bubbleCount = count($this->bubbles);

            if ($bubbleCount <= 50) {
                return 5;
            } elseif ($bubbleCount <= 80) {
                return 8;
            } else {
                return 10;
            }
        }

        $wordCount = $this->calculateWordCount();
        if ($wordCount <= 1000) {
            return 5;
        } elseif ($wordCount <= 1300) {
            return 8;
        } else {
            return 10;
        }
    }

    public function removeImagePreview()
    {
        if ($this->existing_image_url) {
            $this->imagesToDelete[] = $this->existing_image_url;
        }
        $this->image_upload       = null;
        $this->existing_image_url = null;
    }

    public function resetBubbleForm()
    {
        $this->editingIndex       = null;
        $this->message            = '';
        $this->image_upload       = null;
        $this->existing_image_url = null;
        $this->call_type          = 'incoming';
        $this->call_duration      = '00:00';
    }

    private function calculateWordCount(): int
    {
        if ($this->type === 'chat') {
            return count($this->bubbles);
        }

        return str_word_count(strip_tags($this->regularContent));
    }

    public function render()
    {
        return view('livewire.stories.chapters.chapter-editor')->layout('layouts.editor');
    }
}
