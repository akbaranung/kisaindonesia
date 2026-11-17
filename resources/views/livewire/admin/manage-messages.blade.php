<div class="h-[calc(100vh-125px)] bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row"
    wire:poll.4s="pollMessages"
    x-data="{
        scrollToBottom() {
            $nextTick(() => {
                const el = this.$refs.adminChatArea;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });
        }
    }"
    x-init="scrollToBottom()"
    @admin-chat-selected.window="scrollToBottom()"
    @admin-message-sent.window="scrollToBottom()"
    @admin-message-received.window="scrollToBottom()">

    <!-- Panel Kiri: Daftar Percakapan Penulis -->
    <div class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-slate-800 flex flex-col bg-slate-950/40 shrink-0">
        <!-- Header Daftar Percakapan -->
        <div class="p-4 border-b border-slate-800 shrink-0 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-comments text-brand-400"></i>
                        <span>Pesan Penulis</span>
                    </h2>
                    <p class="text-[11px] text-slate-400">Hubungi & tanggapi pesan dari penulis</p>
                </div>

                <button type="button" wire:click="openNewChatModal"
                    class="px-2.5 py-1.5 bg-brand-500/10 hover:bg-brand-500/20 border border-brand-500/30 text-brand-400 text-xs font-bold rounded-xl transition flex items-center gap-1 shadow-xs"
                    title="Mulai Percakapan Baru">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Chat Baru</span>
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" wire:model.live.debounce.250ms="search"
                    placeholder="Cari nama atau email..."
                    class="w-full pl-8 pr-3 py-1.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-brand-400">
            </div>
        </div>

        <!-- List Obrolan (Scrollable) -->
        <div class="flex-1 overflow-y-auto divide-y divide-slate-800/60">
            @forelse ($conversations as $conv)
                @php
                    $isSelected = $selectedConversationId === $conv->id;
                    $hasUnread = $conv->admin_unread_count > 0;
                    $lastMsg = $conv->latestMessage;
                @endphp

                <button type="button"
                    wire:click="selectConversation({{ $conv->id }})"
                    class="w-full text-left p-3.5 transition flex items-start gap-3 hover:bg-slate-800/40 {{ $isSelected ? 'bg-slate-800/70 border-l-4 border-brand-500' : '' }}"
                    wire:key="conv-item-{{ $conv->id }}">

                    <!-- Avatar User -->
                    <div class="relative shrink-0 mt-0.5">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-brand-400 overflow-hidden">
                            @if (!empty($conv->user?->avatar))
                                <img src="{{ $conv->user->avatar }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($conv->user?->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        @if ($hasUnread)
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-rose-500 rounded-full border-2 border-slate-900 animate-pulse"></span>
                        @endif
                    </div>

                    <!-- Info User & Preview Pesan -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-xs font-bold truncate {{ $hasUnread ? 'text-slate-100' : 'text-slate-300' }}">
                                {{ $conv->user->name ?? 'User #' . $conv->user_id }}
                            </span>
                            <span class="text-[10px] text-slate-500 shrink-0">
                                {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(short: true) : '' }}
                            </span>
                        </div>

                        <p class="text-[11px] truncate {{ $hasUnread ? 'font-semibold text-slate-200' : 'text-slate-400' }}">
                            @if ($lastMsg)
                                @if ($lastMsg->sender_id === auth()->id())
                                    <span class="text-brand-400">Anda:</span>
                                @endif
                                {{ $lastMsg->body }}
                            @else
                                <span class="text-slate-500 italic">Belum ada pesan</span>
                            @endif
                        </p>

                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[9px] text-slate-500 truncate max-w-[130px]">
                                {{ $conv->user->email ?? '-' }}
                            </span>

                            @if ($hasUnread)
                                <span class="px-1.5 py-0.2 bg-rose-500 text-white rounded-full text-[9px] font-bold">
                                    {{ $conv->admin_unread_count }} baru
                                </span>
                            @endif
                        </div>
                    </div>
                </button>
            @empty
                <div class="p-8 text-center text-slate-500 text-xs space-y-2">
                    <i class="fa-regular fa-comments text-2xl opacity-40"></i>
                    <p>Belum ada percakapan dengan penulis.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Panel Kanan: Ruang Obrolan Aktif -->
    <div class="flex-1 flex flex-col bg-slate-900/60 min-w-0">
        @if ($activeConversation)
            <!-- Header Chat Aktif -->
            <div class="p-4 border-b border-slate-800 bg-slate-900 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-brand-400 overflow-hidden shrink-0">
                        @if (!empty($activeConversation->user?->avatar))
                            <img src="{{ $activeConversation->user->avatar }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($activeConversation->user?->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-100 truncate">
                                {{ $activeConversation->user->name ?? 'User #' . $activeConversation->user_id }}
                            </h3>
                            <span class="px-2 py-0.2 rounded-full text-[9px] font-bold bg-slate-800 border border-slate-700 text-slate-400">
                                Penulis / User
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 truncate">
                            {{ $activeConversation->user->email ?? '-' }}
                            @if ($activeConversation->user?->stories)
                                • <span class="text-brand-400">{{ $activeConversation->user->stories->count() }} Cerita</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-500 hidden sm:inline">
                        ID: #{{ $activeConversation->user_id }}
                    </span>
                </div>
            </div>

            <!-- Pesan Percakapan (Scrollable) -->
            <div x-ref="adminChatArea" class="flex-1 min-h-0 overflow-y-auto p-4 sm:p-5 space-y-3.5 bg-slate-950/20">
                @if ($activeConversation->messages->isEmpty())
                    <div class="text-center py-12 text-slate-500 text-xs space-y-2">
                        <i class="fa-regular fa-paper-plane text-3xl opacity-30"></i>
                        <p>Belum ada pesan dalam percakapan ini.<br>Kirimkan sapaan atau informasi awal kepada penulis.</p>
                    </div>
                @else
                    @foreach ($activeConversation->messages as $msg)
                        @php
                            $isAdminSender = $msg->isFromAdmin();
                        @endphp

                        <div class="flex {{ $isAdminSender ? 'justify-end' : 'justify-start items-start gap-2' }}"
                            wire:key="admin-msg-{{ $msg->id }}">

                            @if (!$isAdminSender)
                                <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 text-brand-400 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                                    {{ strtoupper(substr($activeConversation->user?->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif

                            <div class="max-w-[75%] sm:max-w-[70%]">
                                <div class="p-3 rounded-2xl {{ $isAdminSender ? 'bg-brand-600 text-white rounded-tr-xs shadow-md shadow-brand-900/20' : 'bg-slate-800 border border-slate-700 text-slate-100 rounded-tl-xs shadow-sm' }}">
                                    @if ($isAdminSender)
                                        <div class="flex items-center justify-end gap-1.5 mb-1 text-[10px] font-bold text-brand-200">
                                            <span>{{ $msg->sender->name ?? 'Admin' }}</span>
                                            <span class="text-[8px] px-1 py-0.2 rounded bg-white/20">Admin</span>
                                        </div>
                                    @else
                                        <div class="mb-1 text-[10px] font-bold text-brand-400">
                                            {{ $activeConversation->user->name ?? 'Penulis' }}
                                        </div>
                                    @endif

                                    @if ($msg->hasAttachment())
                                        @if ($msg->isImage())
                                            <div class="mb-2 rounded-xl overflow-hidden border border-black/10 dark:border-white/10 cursor-pointer shadow-xs max-w-sm"
                                                onclick="window.open('{{ $msg->attachment }}', '_blank')">
                                                <img src="{{ $msg->attachment }}" alt="{{ $msg->attachment_name }}"
                                                    class="w-full max-h-72 object-cover hover:scale-102 transition duration-200">
                                            </div>
                                        @else
                                            <a href="{{ $msg->attachment }}" download="{{ $msg->attachment_name ?? 'lampiran' }}"
                                                class="mb-2 flex items-center gap-2.5 p-2.5 rounded-xl {{ $isAdminSender ? 'bg-white/15 hover:bg-white/25 text-white' : 'bg-slate-900/60 hover:bg-slate-900 text-slate-100' }} border border-black/10 dark:border-white/10 transition group">
                                                <div class="w-8 h-8 rounded-lg {{ $isAdminSender ? 'bg-white/20 text-white' : 'bg-brand-500/20 text-brand-400' }} flex items-center justify-center text-sm shrink-0">
                                                    @if ($msg->isPdf())
                                                        <i class="fa-solid fa-file-pdf text-rose-400"></i>
                                                    @else
                                                        <i class="fa-solid fa-file-lines"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs font-semibold truncate">{{ $msg->attachment_name ?? 'Dokumen' }}</p>
                                                    <p class="text-[9px] opacity-75">{{ $msg->formattedAttachmentSize() }}</p>
                                                </div>
                                                <i class="fa-solid fa-download text-xs opacity-70 group-hover:opacity-100 shrink-0"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if ($msg->body)
                                        <div class="text-xs leading-relaxed break-words whitespace-pre-line select-text">
                                            {{ $msg->body }}
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-end gap-1 mt-1 text-[9px] {{ $isAdminSender ? 'text-white/70' : 'text-slate-400' }}">
                                        <span>{{ $msg->created_at->format('d M, H:i') }}</span>
                                        @if ($isAdminSender)
                                            @if ($msg->is_read)
                                                <i class="fa-solid fa-check-double text-[9px] text-white" title="Dibaca oleh penulis"></i>
                                            @else
                                                <i class="fa-solid fa-check text-[9px] text-white/60" title="Terkirim"></i>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Input Balasan Admin -->
            <div class="p-3 sm:p-4 border-t border-slate-800 bg-slate-900 shrink-0">
                <!-- Preview File Lampiran Balasan -->
                @if ($replyFile)
                    <div class="mb-2.5 p-2.5 bg-slate-950/80 rounded-xl border border-slate-800 flex items-center justify-between gap-2 animate-fade-in max-w-md">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @php
                                $isImg = str_starts_with($replyFile->getMimeType() ?? '', 'image/');
                            @endphp
                            @if ($isImg)
                                <div class="w-9 h-9 rounded-lg overflow-hidden bg-slate-800 shrink-0">
                                    <img src="{{ $replyFile->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-9 h-9 rounded-lg bg-brand-500/10 text-brand-400 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-paperclip"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-100 truncate max-w-[240px]">
                                    {{ $replyFile->getClientOriginalName() }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ round($replyFile->getSize() / 1024, 1) }} KB
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="removeReplyFile"
                            class="w-6 h-6 rounded-full hover:bg-slate-800 text-slate-400 hover:text-rose-400 flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                <div wire:loading wire:target="replyFile" class="text-[11px] text-brand-400 flex items-center gap-1.5 mb-2 px-1 font-medium">
                    <i class="fa-solid fa-spinner fa-spin"></i> Memproses lampiran file...
                </div>

                <form wire:submit="sendReply" class="flex items-end gap-2">
                    <!-- Hidden File Input & Trigger -->
                    <input type="file" wire:model="replyFile" x-ref="adminFileInput" class="hidden"
                        accept="image/*,.pdf,.doc,.docx,.txt">
                    <button type="button" @click="$refs.adminFileInput.click()"
                        class="w-10 h-10 rounded-xl text-slate-400 hover:text-brand-400 hover:bg-slate-800 flex items-center justify-center transition shrink-0 mb-0.5"
                        title="Lampirkan File / Gambar">
                        <i class="fa-solid fa-paperclip text-sm"></i>
                    </button>

                    <!-- Textarea Dinamis Auto-Resize -->
                    <div class="relative flex-1">
                        <textarea
                            x-ref="adminChatInput"
                            x-data="{
                                resize() {
                                    $el.style.height = '42px';
                                    $el.style.height = Math.min($el.scrollHeight, 140) + 'px';
                                }
                            }"
                            x-init="resize()"
                            @input="resize()"
                            @admin-message-sent.window="$nextTick(() => { $el.style.height = '42px'; })"
                            wire:model="replyText"
                            @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $wire.sendReply(); }"
                            rows="1"
                            placeholder="Tulis balasan untuk {{ $activeConversation->user->name ?? 'penulis' }} (Tekan Enter untuk kirim, Shift+Enter untuk baris baru)..."
                            maxlength="3000"
                            class="w-full resize-none overflow-y-auto px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-brand-400 leading-relaxed block"
                            style="height: 42px; min-height: 42px; max-height: 140px;"></textarea>
                    </div>

                    <!-- Tombol Kirim -->
                    <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm shadow-brand-900/30 disabled:opacity-50 shrink-0 h-10 mb-0.5">
                        <span wire:loading.remove wire:target="sendReply, replyFile" class="flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span class="hidden sm:inline">Kirim</span>
                        </span>
                        <span wire:loading wire:target="sendReply, replyFile" class="flex items-center gap-1.5">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <span class="hidden sm:inline">Mengirim...</span>
                        </span>
                    </button>
                </form>

                @error('replyFile')
                    <p class="text-[10px] text-rose-400 mt-1.5 ml-1">{{ $message }}</p>
                @enderror
                @error('replyText')
                    <p class="text-[10px] text-rose-400 mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>
        @else
            <!-- State Kosong jika belum memilih percakapan -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-500">
                <div class="w-16 h-16 rounded-2xl bg-slate-800/80 text-brand-400 flex items-center justify-center text-2xl mb-3 shadow-inner">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-300">Pilih Percakapan Penulis</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-xs">
                    Klik salah satu percakapan di sebelah kiri untuk melihat riwayat pesan dan mengirim balasan.
                </p>
                <button type="button" wire:click="openNewChatModal"
                    class="mt-4 px-4 py-2 bg-brand-500/10 hover:bg-brand-500/20 border border-brand-500/30 text-brand-400 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Mulai Chat Baru</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Modal Mulai Chat Baru dengan Penulis -->
    @if ($showNewChatModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4"
            x-data @keydown.window.escape="$wire.closeNewChatModal()">

            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md overflow-hidden flex flex-col shadow-2xl animate-fade-in"
                @click.away="$wire.closeNewChatModal()">

                <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-slate-900">
                    <h3 class="text-sm font-bold text-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-user-plus text-brand-400"></i>
                        <span>Mulai Chat dengan Penulis</span>
                    </h3>
                    <button type="button" wire:click="closeNewChatModal"
                        class="p-1.5 text-slate-400 hover:text-slate-200 rounded-lg transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Cari Penulis / Pengguna</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="text" wire:model.live.debounce.300ms="userSearch"
                                placeholder="Ketik minimal 2 huruf nama atau email..."
                                class="w-full pl-8 pr-3 py-2 bg-slate-950/60 border border-slate-800 rounded-xl text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-brand-400">
                        </div>
                    </div>

                    <!-- Hasil Pencarian User -->
                    <div class="max-h-60 overflow-y-auto space-y-1 pr-1">
                        @if (!empty($searchedUsers))
                            @foreach ($searchedUsers as $u)
                                <button type="button" wire:click="startChatWithUser({{ $u->id }})"
                                    class="w-full text-left p-2.5 rounded-xl hover:bg-slate-800 transition flex items-center justify-between group">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 text-brand-400 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-200 block truncate group-hover:text-brand-400">
                                                {{ $u->name }}
                                            </span>
                                            <span class="text-[10px] text-slate-500 block truncate">
                                                {{ $u->email }}
                                            </span>
                                        </div>
                                    </div>

                                    <span class="text-[11px] font-bold text-brand-400 opacity-0 group-hover:opacity-100 transition shrink-0">
                                        Pilih →
                                    </span>
                                </button>
                            @endforeach
                        @elseif (strlen(trim($userSearch)) >= 2)
                            <p class="text-center py-6 text-xs text-slate-500">Tidak ada pengguna ditemukan.</p>
                        @else
                            <p class="text-center py-6 text-xs text-slate-500">Ketik nama atau email penulis untuk mencari.</p>
                        @endif
                    </div>
                </div>

                <div class="p-3 border-t border-slate-800 flex justify-end bg-slate-900">
                    <button type="button" wire:click="closeNewChatModal"
                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>

