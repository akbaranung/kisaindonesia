<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserChat extends Component
{
    use WithFileUploads;

    public ?Conversation $conversation = null;
    public string $newMessage = '';
    public $file = null;

    public function mount()
    {
        $user = Auth::user();

        // Cari atau buat percakapan direct antara Penulis/User ini dengan Admin
        $this->conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Tandai pesan dari admin sebagai telah dibaca
        $this->conversation->markAsReadForUser();
    }

    public function removeFile()
    {
        $this->file = null;
        $this->resetErrorBag('file');
    }

    public function sendMessage()
    {
        $trimmed = trim($this->newMessage);
        if (empty($trimmed) && !$this->file) {
            return;
        }

        $this->validate([
            'newMessage' => 'nullable|string|max:3000',
            'file' => 'nullable|file|max:10240', // Maks 10 MB
        ], [
            'newMessage.max' => 'Pesan maksimal 3000 karakter.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'file.file' => 'File tidak valid.',
        ]);

        $user = Auth::user();

        $base64 = null;
        $fileName = null;
        $fileType = null;
        $fileSize = null;

        if ($this->file) {
            $fileType = $this->file->getMimeType() ?: 'application/octet-stream';
            $fileName = $this->file->getClientOriginalName();
            $fileSize = $this->file->getSize();
            $fileContent = file_get_contents($this->file->getRealPath());
            $base64 = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
        }

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $user->id,
            'body' => !empty($trimmed) ? $trimmed : null,
            'attachment' => $base64,
            'attachment_name' => $fileName,
            'attachment_type' => $fileType,
            'attachment_size' => $fileSize,
            'is_read' => false,
        ]);

        $this->conversation->update([
            'last_message_at' => now(),
        ]);
        $this->conversation->increment('admin_unread_count');

        $this->newMessage = '';
        $this->file = null;
        $this->resetErrorBag();

        $this->dispatch('message-sent');
    }

    public function pollMessages()
    {
        if ($this->conversation) {
            // Tandai jika ada pesan baru dari admin
            if ($this->conversation->user_unread_count > 0) {
                $this->conversation->markAsReadForUser();
                $this->dispatch('message-received');
            }
        }
    }

    public function render()
    {
        $messages = $this->conversation
            ? $this->conversation->messages()->with('sender')->get()
            : collect();

        return view('livewire.chat.user-chat', [
            'messages' => $messages,
        ])->layout('layouts.app', [
            'title' => 'Pesan ke Admin',
        ]);
    }
}

