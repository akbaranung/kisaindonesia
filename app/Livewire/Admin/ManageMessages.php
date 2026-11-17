<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageMessages extends Component
{
    use WithFileUploads;

    public ?int $selectedConversationId = null;
    public string $search = '';
    public string $replyText = '';
    public $replyFile = null;

    // Modal Mulai Chat Baru dengan Penulis
    public bool $showNewChatModal = false;
    public string $userSearch = '';

    protected $queryString = [
        'selectedConversationId' => ['as' => 'id', 'except' => null],
    ];

    public function mount()
    {
        if ($this->selectedConversationId) {
            $conv = Conversation::find($this->selectedConversationId);
            if ($conv) {
                $conv->markAsReadForAdmin();
            } else {
                $this->selectedConversationId = null;
            }
        } else {
            // Auto-select percakapan pertama jika ada
            $first = Conversation::orderByDesc('last_message_at')->first();
            if ($first) {
                $this->selectedConversationId = $first->id;
                $first->markAsReadForAdmin();
            }
        }
    }

    public function selectConversation(int $id)
    {
        $this->selectedConversationId = $id;

        $conv = Conversation::find($id);
        if ($conv) {
            $conv->markAsReadForAdmin();
        }

        $this->dispatch('admin-chat-selected');
    }

    public function removeReplyFile()
    {
        $this->replyFile = null;
        $this->resetErrorBag('replyFile');
    }

    public function sendReply()
    {
        $trimmed = trim($this->replyText);
        if (empty($trimmed) && !$this->replyFile) {
            return;
        }

        if (!$this->selectedConversationId) {
            return;
        }

        $this->validate([
            'replyText' => 'nullable|string|max:3000',
            'replyFile' => 'nullable|file|max:10240', // Maks 10 MB
        ], [
            'replyText.max' => 'Pesan maksimal 3000 karakter.',
            'replyFile.max' => 'Ukuran file maksimal 10 MB.',
            'replyFile.file' => 'File tidak valid.',
        ]);

        $conv = Conversation::findOrFail($this->selectedConversationId);

        $base64 = null;
        $fileName = null;
        $fileType = null;
        $fileSize = null;

        if ($this->replyFile) {
            $fileType = $this->replyFile->getMimeType() ?: 'application/octet-stream';
            $fileName = $this->replyFile->getClientOriginalName();
            $fileSize = $this->replyFile->getSize();
            $fileContent = file_get_contents($this->replyFile->getRealPath());
            $base64 = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
        }

        Message::create([
            'conversation_id' => $conv->id,
            'sender_id' => Auth::id(),
            'body' => !empty($trimmed) ? $trimmed : null,
            'attachment' => $base64,
            'attachment_name' => $fileName,
            'attachment_type' => $fileType,
            'attachment_size' => $fileSize,
            'is_read' => false,
        ]);

        $conv->update([
            'last_message_at' => now(),
        ]);
        $conv->increment('user_unread_count');

        $this->replyText = '';
        $this->replyFile = null;
        $this->resetErrorBag();

        $this->dispatch('admin-message-sent');
    }

    public function pollMessages()
    {
        if ($this->selectedConversationId) {
            $conv = Conversation::find($this->selectedConversationId);
            if ($conv && $conv->admin_unread_count > 0) {
                $conv->markAsReadForAdmin();
                $this->dispatch('admin-message-received');
            }
        }
    }

    public function openNewChatModal()
    {
        $this->userSearch = '';
        $this->showNewChatModal = true;
    }

    public function closeNewChatModal()
    {
        $this->showNewChatModal = false;
        $this->userSearch = '';
    }

    public function startChatWithUser(int $userId)
    {
        $conv = Conversation::firstOrCreate([
            'user_id' => $userId,
        ]);

        $this->selectedConversationId = $conv->id;
        $this->showNewChatModal = false;
        $this->userSearch = '';

        $conv->markAsReadForAdmin();
        $this->dispatch('admin-chat-selected');
    }

    public function render()
    {
        // Ambil daftar percakapan
        $conversations = Conversation::with(['user', 'latestMessage.sender'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($u) {
                    $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        // Data percakapan yang sedang dipilih
        $activeConversation = $this->selectedConversationId
            ? Conversation::with(['user.stories', 'messages.sender'])->find($this->selectedConversationId)
            : null;

        // Daftar pencarian user untuk modal chat baru
        $searchedUsers = [];
        if ($this->showNewChatModal && strlen(trim($this->userSearch)) >= 2) {
            $searchedUsers = User::where(function ($q) {
                $q->where('name', 'like', '%' . $this->userSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->userSearch . '%');
            })
                ->where('role', '!=', 'admin')
                ->where('role', '!=', 'superadmin')
                ->take(8)
                ->get();
        }

        return view('livewire.admin.manage-messages', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'searchedUsers' => $searchedUsers,
        ])->layout('layouts.admin', [
            'title' => 'Pesan Penulis',
        ]);
    }
}

