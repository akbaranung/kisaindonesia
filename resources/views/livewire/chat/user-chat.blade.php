<div class="relative min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col justify-between transition-colors duration-200 pb-20"
    wire:poll.4s="pollMessages"
    x-data="{
        scrollToBottom() {
            $nextTick(() => {
                const el = this.$refs.chatContainer;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });
        }
    }"
    x-init="scrollToBottom()"
    @message-sent.window="scrollToBottom()"
    @message-received.window="scrollToBottom()">

    <!-- Header Obrolan -->
    <header class="sticky top-0 left-0 right-0 z-30 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-4 py-3 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile') }}" wire:navigate
                class="p-2 -ml-1 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 rounded-xl transition">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </a>

            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-linear-to-br from-brand-500 to-teal-600 text-white flex items-center justify-center font-black text-sm shadow-sm">
                    K
                </div>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
            </div>

            <div>
                <div class="flex items-center gap-1.5">
                    <h1 class="text-sm font-bold text-slate-900 dark:text-slate-100">Admin Kisa</h1>
                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                        Official
                    </span>
                </div>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Support & Layanan Penulis
                </p>
            </div>
        </div>

        <a href="{{ route('support') }}" wire:navigate
            class="text-[11px] font-bold text-slate-500 dark:text-slate-400 hover:text-brand-500 transition px-2 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
            title="Pusat Bantuan">
            <i class="fa-solid fa-headset mr-1"></i>Bantuan
        </a>
    </header>

    <!-- Area Pesan (Chat Stream) -->
    <main x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-3.5 max-h-[calc(100vh-140px)]">
        <!-- Banner Sambutan -->
        <div class="p-3.5 bg-brand-50/70 dark:bg-brand-950/30 border border-brand-200/70 dark:border-brand-900/40 rounded-2xl text-center space-y-1 my-2">
            <div class="w-8 h-8 rounded-full bg-brand-500/10 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto text-sm">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h4 class="text-xs font-bold text-brand-900 dark:text-brand-200">Layanan Pesan Resmi Kisa Indonesia</h4>
            <p class="text-[11px] text-brand-700/80 dark:text-brand-300/70 leading-relaxed max-w-xs mx-auto">
                Saluran resmi ini menghubungkan Anda langsung dengan tim Admin Kisa untuk konsultasi naskah, kontrak, royalti, atau bantuan teknis.
            </p>
        </div>

        @if ($messages->isEmpty())
            <div class="text-center py-8 text-slate-400 dark:text-slate-500 space-y-2">
                <i class="fa-regular fa-comments text-3xl opacity-40"></i>
                <p class="text-xs">Belum ada percakapan.<br>Ketik pesan di bawah untuk memulai obrolan dengan Admin.</p>
            </div>
        @else
            @foreach ($messages as $msg)
                @php
                    $isMe = $msg->sender_id === auth()->id();
                @endphp

                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start items-start gap-2' }} animate-fade-in"
                    wire:key="msg-{{ $msg->id }}">

                    @if (!$isMe)
                        <div class="w-7 h-7 rounded-full bg-linear-to-br from-brand-500 to-teal-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5 shadow-2xs">
                            K
                        </div>
                    @endif

                    <div class="max-w-[80%] sm:max-w-[75%]">
                        <div class="p-3 rounded-2xl {{ $isMe ? 'bg-brand-500 text-white rounded-tr-xs shadow-sm shadow-brand-500/20' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-800 dark:text-slate-100 rounded-tl-xs shadow-2xs' }}">
                            @if (!$isMe)
                                <div class="flex items-center gap-1.5 mb-1 text-[10px] font-bold text-brand-600 dark:text-brand-400">
                                    <span>{{ $msg->sender->name ?? 'Admin Kisa' }}</span>
                                    <span class="text-[8px] px-1 py-0.2 rounded bg-brand-500/10 border border-brand-500/20">Admin</span>
                                </div>
                            @endif

                            @if ($msg->hasAttachment())
                                @if ($msg->isImage())
                                    <div class="mb-2 rounded-xl overflow-hidden border border-black/10 dark:border-white/10 cursor-pointer shadow-xs max-w-xs"
                                        onclick="window.open('{{ $msg->attachment }}', '_blank')">
                                        <img src="{{ $msg->attachment }}" alt="{{ $msg->attachment_name }}"
                                            class="w-full max-h-64 object-cover hover:scale-102 transition duration-200">
                                    </div>
                                @else
                                    <a href="{{ $msg->attachment }}" download="{{ $msg->attachment_name ?? 'lampiran' }}"
                                        class="mb-2 flex items-center gap-2.5 p-2 rounded-xl {{ $isMe ? 'bg-white/15 hover:bg-white/25 text-white' : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100' }} border border-black/5 dark:border-white/5 transition group">
                                        <div class="w-8 h-8 rounded-lg {{ $isMe ? 'bg-white/20 text-white' : 'bg-brand-500/10 text-brand-600 dark:text-brand-400' }} flex items-center justify-center text-sm shrink-0">
                                            @if ($msg->isPdf())
                                                <i class="fa-solid fa-file-pdf text-rose-500"></i>
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

                            <div class="flex items-center justify-end gap-1 mt-1 text-[9px] {{ $isMe ? 'text-white/80' : 'text-slate-400' }}">
                                <span>{{ $msg->created_at->format('H:i') }}</span>
                                @if ($isMe)
                                    @if ($msg->is_read)
                                        <i class="fa-solid fa-check-double text-[9px] text-white" title="Dibaca admin"></i>
                                    @else
                                        <i class="fa-solid fa-check text-[9px] text-white/70" title="Terkirim"></i>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </main>

    <!-- Fixed Input Form di Bawah -->
    <div class="fixed bottom-14 left-0 right-0 z-40 max-w-md mx-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200/80 dark:border-slate-800 p-2.5 shadow-lg">
        <!-- Preview File Lampiran -->
        @if ($file)
            <div class="mb-2 p-2 bg-slate-100 dark:bg-slate-800/90 rounded-xl border border-slate-200 dark:border-slate-700/80 flex items-center justify-between gap-2 animate-fade-in">
                <div class="flex items-center gap-2 min-w-0">
                    @php
                        $isImg = str_starts_with($file->getMimeType() ?? '', 'image/');
                    @endphp
                    @if ($isImg)
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-slate-200 dark:bg-slate-700 shrink-0">
                            <img src="{{ $file->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-8 h-8 rounded-lg bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-paperclip"></i>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold text-slate-800 dark:text-slate-100 truncate max-w-[200px]">
                            {{ $file->getClientOriginalName() }}
                        </p>
                        <p class="text-[9px] text-slate-400">
                            {{ round($file->getSize() / 1024, 1) }} KB
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="removeFile"
                    class="w-6 h-6 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 hover:text-rose-500 flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div wire:loading wire:target="file" class="text-[10px] text-brand-500 flex items-center gap-1.5 mb-1.5 px-1 font-medium">
            <i class="fa-solid fa-spinner fa-spin"></i> Memproses lampiran...
        </div>

        <form wire:submit="sendMessage" class="flex items-end gap-1.5">
            <!-- Hidden File Input & Trigger Button -->
            <input type="file" wire:model="file" x-ref="userFileInput" class="hidden"
                accept="image/*,.pdf,.doc,.docx,.txt">
            <button type="button" @click="$refs.userFileInput.click()"
                class="w-9 h-9 rounded-xl text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition shrink-0 mb-0.5"
                title="Lampirkan File / Gambar">
                <i class="fa-solid fa-paperclip text-sm"></i>
            </button>

            <!-- Textarea Dinamis Auto-Resize -->
            <div class="relative flex-1">
                <textarea
                    x-ref="userChatInput"
                    x-data="{
                        resize() {
                            $el.style.height = '38px';
                            $el.style.height = Math.min($el.scrollHeight, 120) + 'px';
                        }
                    }"
                    x-init="resize()"
                    @input="resize()"
                    @message-sent.window="$nextTick(() => { $el.style.height = '38px'; })"
                    wire:model="newMessage"
                    @keydown.enter="if (!$event.shiftKey && window.innerWidth > 640) { $event.preventDefault(); $wire.sendMessage(); }"
                    rows="1"
                    placeholder="Tulis pesan..."
                    maxlength="3000"
                    class="w-full resize-none overflow-y-auto px-3.5 py-2 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-brand-500 dark:focus:border-brand-500 leading-relaxed block"
                    style="height: 38px; min-height: 38px; max-height: 120px;"></textarea>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit"
                wire:loading.attr="disabled"
                class="w-9.5 h-9.5 rounded-xl bg-brand-500 hover:bg-brand-600 active:scale-95 text-white flex items-center justify-center transition shadow-sm shadow-brand-500/30 disabled:opacity-50 shrink-0 mb-0.5">
                <span wire:loading.remove wire:target="sendMessage, file">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </span>
                <span wire:loading wire:target="sendMessage, file">
                    <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                </span>
            </button>
        </form>
        @error('file')
            <p class="text-[10px] text-rose-500 mt-1 ml-1">{{ $message }}</p>
        @enderror
        @error('newMessage')
            <p class="text-[10px] text-rose-500 mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>
</div>

