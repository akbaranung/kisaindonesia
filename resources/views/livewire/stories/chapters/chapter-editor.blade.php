<div class="h-screen flex flex-col bg-slate-950 text-slate-100 font-sans antialiased overflow-hidden">
    <header class="h-14 bg-slate-900 border-b border-slate-800 px-4 flex items-center justify-between shrink-0 z-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('stories.chapters', $story->id) }}"
                class="p-1.5 text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="max-w-[150px] sm:max-w-xs">
                <h1 class="text-xs font-bold text-slate-100 truncate">Bab {{ $chapter->order_number }}</h1>
                <p class="text-[10px] text-brand-400 font-semibold truncate">
                    ⚡ {{ number_format($this->calculateWordCount()) }} Kata • {{ ucfirst($type) }}
                </p>
            </div>
        </div>
        <div class="flex gap-1">
            <!-- Status Chapter -->
            <select wire:model="status"
                class="bg-slate-800 border border-slate-700 text-slate-200 text-xs rounded-xl px-2.5 py-1.5 font-semibold focus:outline-none focus:border-brand-500">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>

            <!-- Tombol Simpan Bab -->
            <button wire:click="saveChapter" wire:loading.attr="disabled"
                class="px-4 py-1.5 bg-brand-500 hover:bg-brand-600 active:scale-95 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition flex items-center gap-1.5">
                <span wire:loading.remove wire:target="saveChapter"><i class="fas fa-save"></i></span>
                <span wire:loading wire:target="saveChapter">Menyimpan...</span>
            </button>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- 2. MAIN CANVAS AREA (SCROLLABLE)           -->
    <!-- ========================================== -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Flash Messages -->
        <div class="max-w-xl mx-auto space-y-2">
            @if (session()->has('message'))
                <div
                    class="p-3 bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-xs rounded-xl flex items-center justify-between">
                    <span>✓ {{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('warning'))
                <div
                    class="p-3 bg-amber-950/80 border border-amber-800 text-amber-300 text-xs rounded-xl flex items-center justify-between">
                    <span>⚠️ {{ session('warning') }}</span>
                </div>
            @endif
        </div>


        <!-- Input Judul Bab -->
        <div class="max-w-xl mx-auto">
            <input type="text" wire:model.live="title" placeholder="Judul Bab Cerita..."
                class="w-full bg-transparent text-lg font-extrabold text-slate-100 placeholder-slate-600 focus:outline-none border-b border-slate-800 pb-2 focus:border-brand-500 transition">
            @error('title')
                <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- TAMPILAN CANVAS CHAT FIC -->
        @if ($type === 'chat')
            <div class="max-w-xl mx-auto space-y-4 pb-36">

                @forelse($bubbles as $index => $b)
                    @php
                        $char = $characters->firstWhere('id', $b['character_id'] ?? null);
                        $isRight = ($char->default_position ?? 'left') === 'right';
                        $isLeft = ($char->default_position ?? 'right') === 'left';
                        $isCenter = $b['message_type'] === 'center_text';
                        $charName = $char ? $char->name : 'Unknown';
                        $avatar =
                            $char && $char->avatar_path
                                ? asset('storage/' . $char->avatar_path)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($charName) . '&background=random';
                    @endphp

                    <!-- A. Tipe Pesan TEKS -->
                    @if (($b['message_type'] ?? 'text') === 'text')
                        <div class="space-y-1 {{ $isRight ? 'text-right' : 'text-left' }}">
                            <div class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                {{-- Avatar --}}
                                <img src="{{ $avatar }}"
                                    class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                {{-- Gelembung Pesan --}}
                                <div class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                    <span
                                        class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $char->name }}</span>
                                    @if (!$isRight && !empty($row['character_name']))
                                        <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                            {{ $row['character_name'] }}
                                        </span>
                                    @endif

                                    <div
                                        class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-brand-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                        <p class="whitespace-pre-line">{{ $b['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- B. Tipe Pesan GAMBAR -->
                    @elseif(($b['message_type'] ?? '') === 'image')
                        <div class=" space-y-1 {{ $isRight ? 'text-right' : 'text-left' }}">
                            <div class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                {{-- Avatar --}}
                                <img src="{{ $avatar }}"
                                    class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                {{-- Gelembung Pesan --}}
                                <div class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                    <span
                                        class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $char->name }}</span>
                                    @if (!$isRight && !empty($row['character_name']))
                                        <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                            {{ $row['character_name'] }}
                                        </span>
                                    @endif

                                    <div
                                        class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'border border-brand-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                        @if (isset($b['image_upload']) && $b['image_upload'])
                                            <img src="{{ $b['image_upload']->temporaryUrl() }}"
                                                class="max-w-[200px] sm:max-w-xs rounded-xl object-cover">
                                        @elseif(!empty($b['image_url'] ?? ($b['existing_image_url'] ?? null)))
                                            <img src="{{ Storage::url($b['image_url'] ?? $b['existing_image_url']) }}"
                                                class="max-w-[200px] sm:max-w-xs rounded-xl object-cover">
                                        @endif
                                        <p class="whitespace-pre-line">
                                            @if (!empty($b['caption'] ?? $b['message']))
                                                <p class="p-2 text-xs text-slate-300 leading-normal">
                                                    {{ $b['caption'] ?? $b['message'] }}</p>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- C. Tipe Pesan LOG PANGGILAN -->
                    @elseif(($b['message_type'] ?? '') === 'call')
                        <div class=" space-y-1 {{ $isRight ? 'text-right' : 'text-left' }}">
                            <div class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                <img src="{{ $avatar }}"
                                    class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">
                                <div
                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-slate-950 border border-slate-800 text-xs shadow-sm">
                                    @if (($b['call_type'] ?? '') === 'missed')
                                        <span class="text-rose-400 font-bold flex items-center gap-1">📵
                                            Panggilan
                                            Tak Terjawab</span>
                                    @elseif(($b['call_type'] ?? '') === 'incoming')
                                        <span class="text-emerald-400 font-bold flex items-center gap-1">📲
                                            Panggilan Masuk</span>
                                    @else
                                        <span class="text-brand-400 font-bold flex items-center gap-1">📞 Panggilan
                                            Keluar</span>
                                    @endif

                                    <span
                                        class="text-[10px] text-slate-500 font-mono">({{ $b['call_duration'] ?? '00:00' }})</span>
                                </div>
                            </div>
                        </div>
                    @elseif (($b['message_type'] ?? '') === 'center_text')
                        <div class="flex flex-col items-center justify-center my-4 group">
                            <div
                                class="px-3 py-1 bg-slate-950/80 border border-slate-800 rounded-full text-[11px] text-slate-400 font-medium italic text-center max-w-[85%] shadow-sm">
                                {{ $b['message'] ?? '' }}
                            </div>
                        </div>
                    @endif

                    <!-- Aksi Quick Edit & Hapus Row -->
                    @php
                        if ($isRight) {
                            $position = 'justify-end';
                        } elseif ($isLeft) {
                            $position = 'justify-start';
                        } elseif ($isCenter) {
                            $position = 'justify-center';
                        }
                    @endphp


                    <div class="flex items-center gap-2 text-[10px] text-slate-500 pt-0.5 {{ $position }}">
                        <button type="button" wire:click="editBubble({{ $index }})"
                            class="hover:text-brand-400 font-semibold transition">Edit</button>
                        <span>•</span>
                        <button type="button" wire:click="deleteBubble({{ $index }})"
                            class="hover:text-rose-400 font-semibold transition">Hapus</button>
                    </div>



                @empty
                    <div class="py-20 text-center space-y-2">
                        <span class="text-3xl">💬</span>
                        <p class="text-xs text-slate-500">Belum ada gelembung pesan. Tulis pesan di panel bawah!</p>
                    </div>
                @endforelse
                {{-- </div> --}}

            </div>
            <!-- TAMPILAN CANVAS REGULER / NOVEL / PUISI -->
        @else
            <div class="max-w-xl mx-auto h-[calc(100vh-200px)] flex flex-col pt-2">
                <!-- Quill Editor Wrapper dengan $nextTick -->
                <div x-data="{
                    initQuill() {
                        // Tunggu hingga elemen $refs.editor benar-benar ada di DOM
                        this.$nextTick(() => {
                            if (!$refs.editor) return;
                
                            // 1. Register ImageResize
                            if (typeof ImageResize !== 'undefined' && typeof Quill !== 'undefined') {
                                Quill.register('modules/imageResize', ImageResize.default || ImageResize);
                            }
                
                            // 2. Inisialisasi Quill
                            window.quillEditor = new Quill($refs.editor, {
                                theme: 'snow',
                                placeholder: 'Tuliskan isi cerita, narasi, atau deskripsi bab di sini...',
                                modules: {
                                    toolbar: [
                                        [{ 'header': [1, 2, 3, false] }],
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'color': [] }, { 'background': [] }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        [{ 'align': [] }],
                                        ['blockquote', 'code-block'],
                                        ['image', 'link'],
                                        ['clean']
                                    ],
                                    imageResize: {
                                        displaySize: true
                                    }
                                }
                            });
                
                            // 3. Load Konten Awal dari Livewire
                            let initialContent = $wire.get('regularContent');
                            if (initialContent) {
                                window.quillEditor.clipboard.dangerouslyPasteHTML(initialContent);
                            }
                
                            // 4. Update ke Livewire saat ada perubahan teks
                            window.quillEditor.on('text-change', () => {
                                let html = window.quillEditor.root.innerHTML;
                                $wire.set('regularContent', html, false);
                            });
                        });
                    }
                }" x-init="initQuill()" wire:ignore
                    class="bg-slate-950 rounded-xl overflow-hidden border border-slate-800">
                    <div x-ref="editor" class="text-slate-200 min-h-[350px]"></div>
                </div>
            </div>
        @endif

    </div>

    @if ($type === 'chat')
        <div x-data="{ showTypePicker: false }" class="p-3">

            @if ($message_type !== 'center_text')
                <!-- 1. QUICK LIVE MANAGEMENT KARAKTER BAR (Tetap Ditamfikan di Atas Input) -->
                <div class="flex items-center justify-between gap-2 pb-2.5 mb-2 border-b border-slate-800/80">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-0.5 scrollbar-none">
                        <span class="text-[10px] font-bold text-slate-500 uppercase shrink-0 mr-1">Tokoh:</span>

                        @foreach ($characters as $c)
                            <div
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ $character_id == $c->id ? 'bg-brand-500/20 border border-brand-500/40 text-brand-300' : 'bg-slate-800 text-slate-400 border border-slate-700/60' }}">
                                <button type="button" wire:click="$set('character_id', {{ $c->id }})"
                                    class="flex items-center gap-1.5">
                                    <div class="w-4 h-4 rounded-full bg-slate-700 overflow-hidden shrink-0">
                                        @if ($c->avatar_path)
                                            <img src="{{ Storage::url($c->avatar_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span
                                                class="text-[8px] font-bold text-slate-200 flex items-center justify-center h-full">{{ strtoupper(substr($c->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <span>{{ $c->name }}</span>
                                </button>

                                <button type="button" wire:click="openEditCharacterModal({{ $c->id }})"
                                    class="hover:text-amber-400 text-[10px] ml-0.5" title="Edit Karakter Ini">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>

                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Quick Add Karakter -->
                    <button type="button" wire:click="openAddCharacterModal"
                        class="px-2.5 py-1 bg-brand-500/10 hover:bg-brand-500/20 border border-brand-500/30 text-brand-400 font-bold text-[11px] rounded-xl shrink-0 flex items-center gap-1 transition">
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span> Tokoh
                    </button>
                </div>
            @else
                <div
                    class="flex items-center justify-between pb-2 mb-2 border-b border-slate-800/80 text-[11px] text-brand-400 font-bold">
                    <span>📢 Mode Teks Tengah / Narasi Sistem</span>
                    <button type="button" wire:click="$set('message_type', 'text')"
                        class="text-slate-400 hover:text-slate-200 underline text-[10px]">Kembali ke Chat</button>
                </div>
            @endif

            <!-- 2. STATUS SEDANG EDIT BUBBLE (Hanya Muncul Jika Sedang Edit Row) -->
            @if ($editingIndex !== null)
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-800/60">
                    <span
                        class="text-[10px] font-bold text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-lg border border-amber-500/20">
                        <i class="fas fa-pencil"></i> Sedang Edit Bubble #{{ $editingIndex + 1 }}
                    </span>
                    <button type="button" wire:click="resetBubbleForm"
                        class="text-[10px] text-red-400 hover:text-red-200">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            @endif

            @if ($message_type === 'image' && ($image_upload || $existing_image_url))
                <div class="relative inline-block mb-2 p-1.5 bg-slate-950 border border-slate-800 rounded-2xl group">
                    <div class="relative w-24 h-24 rounded-xl overflow-hidden bg-slate-900 border border-slate-800">
                        @if ($image_upload)
                            <img src="{{ $image_upload->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($existing_image_url)
                            <img src="{{ Storage::url($existing_image_url) }}" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- Tombol Batal/Hapus Preview Gambar -->
                    <button type="button" wire:click="removeImagePreview"
                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-rose-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-md hover:bg-rose-600 transition">
                        ✕
                    </button>
                    <span class="text-[9px] font-semibold text-slate-400 block text-center mt-1">Prinjau Gambar</span>
                </div>
            @endif

            <!-- 3. INPUT FORM CHAT DENGAN TOMBOL PLUS (+) -->
            <div class="space-y-1">
                <form wire:submit.prevent="saveBubble" class="flex items-center gap-2">

                    <!-- Tombol Plus (+) untuk Buka Popup Tipe Pesan -->
                    <button type="button" @click="showTypePicker = true"
                        class="p-2.5 bg-slate-950 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-brand-400 rounded-xl shrink-0 transition flex items-center justify-center active:scale-95"
                        title="Pilih Tipe Pesan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </button>

                    <!-- A. Tipe Pesan TEKS -->
                    @if ($message_type === 'text')
                        <input type="text" wire:model="message" placeholder="Ketik pesan chat..."
                            class="flex-1 px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500 placeholder-slate-600">

                        <!-- B. Tipe Pesan GAMBAR -->
                    @elseif($message_type === 'image')
                        <div class="flex-1 flex items-center gap-1.5 min-w-0">
                            <label
                                class="px-2.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-300 cursor-pointer hover:border-brand-500 transition shrink-0 flex items-center gap-1">
                                <span>📷</span>
                                <span class="text-[11px] truncate max-w-[80px] sm:max-w-none">
                                    {{ $image_upload ? 'Terpilih' : 'Pilih' }}
                                </span>
                                <input type="file" wire:model="image_upload" accept="image/*" class="hidden">
                            </label>
                            <input type="text" wire:model="message" placeholder="Caption (opsional)..."
                                class="flex-1 min-w-0 px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                        </div>

                        <!-- C. Tipe Pesan PANGGILAN -->
                    @elseif($message_type === 'call')
                        <div class="flex-1 grid grid-cols-2 gap-1.5 min-w-0">
                            <select wire:model="call_type"
                                class="px-2 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-brand-500 truncate">
                                <option value="incoming">📲 Masuk</option>
                                <option value="outgoing">📞 Keluar</option>
                                <option value="missed">📵 Tak Terjawab</option>
                            </select>
                            <input type="text" wire:model="call_duration" placeholder="Durasi (02:45)"
                                class="px-2.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                        </div>
                    @elseif($message_type === 'center_text')
                        <input type="text" wire:model="message"
                            placeholder="Tulis narasi / keterangan waktu (cth: 'Keesokan Harinya')..."
                            class="flex-1 px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-brand-300 italic focus:outline-none focus:border-brand-500 placeholder-slate-600">
                    @endif

                    <!-- Tombol Kirim / Update -->
                    <button type="submit"
                        class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 active:scale-95 text-slate-950 font-bold text-xs rounded-xl shrink-0 transition flex items-center gap-1 shadow-md shadow-brand-500/10">
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </span>
                    </button>

                </form>
                @error('message')
                    <span class="text-[10px] text-rose-400 font-bold block pt-0.5">⚠️ {{ $message }}</span>
                @enderror
                @error('image_upload')
                    <span class="text-[10px] text-rose-400 font-bold block pt-0.5">⚠️ {{ $message }}</span>
                @enderror
                @error('call_type')
                    <span class="text-[10px] text-rose-400 font-bold block pt-0.5">⚠️ {{ $message }}</span>
                @enderror
                @error('call_duration')
                    <span class="text-[10px] text-rose-400 font-bold block pt-0.5">⚠️ {{ $message }}</span>
                @enderror
            </div>

            <!-- 4. POPUP ACTION SHEET PILIHAN TIPE PESAN (MOBILE/DESKTOP) -->
            <div x-show="showTypePicker" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4"
                style="display: none;">

                <div class="fixed inset-0" @click="showTypePicker = false"></div>

                <div x-show="showTypePicker" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="translate-y-0 sm:scale-100"
                    x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-sm bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-2xl z-10 space-y-4">

                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h3 class="text-xs font-bold text-slate-300">Pilih Tipe Pesan</h3>
                        <button type="button" @click="showTypePicker = false"
                            class="text-slate-500 hover:text-slate-300 text-base font-bold">
                            &times;
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" wire:click="$set('message_type', 'text')"
                            @click="showTypePicker = false"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl border transition text-center {{ $message_type === 'text' ? 'bg-slate-800 border-brand-500 text-brand-400' : 'bg-slate-950/60 border-slate-800 text-slate-400 hover:border-slate-700' }}">
                            <span class="text-xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                </svg>
                            </span>
                            <span class="text-[11px] font-bold">Teks</span>
                        </button>

                        <button type="button" wire:click="$set('message_type', 'image')"
                            @click="showTypePicker = false"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl border transition text-center {{ $message_type === 'image' ? 'bg-slate-800 border-brand-500 text-brand-400' : 'bg-slate-950/60 border-slate-800 text-slate-400 hover:border-slate-700' }}">
                            <span class="text-xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>

                            </span>
                            <span class="text-[11px] font-bold">Gambar</span>
                        </button>

                        <button type="button" wire:click="$set('message_type', 'call')"
                            @click="showTypePicker = false"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl border transition text-center {{ $message_type === 'call' ? 'bg-slate-800 border-brand-500 text-brand-400' : 'bg-slate-950/60 border-slate-800 text-slate-400 hover:border-slate-700' }}">
                            <span class="text-xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                            </span>
                            <span class="text-[11px] font-bold">Panggilan</span>
                        </button>

                        <button type="button" wire:click="$set('message_type', 'center_text')"
                            @click="showTypePicker = false"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl border transition text-center {{ $message_type === 'center_text' ? 'bg-slate-800 border-brand-500 text-brand-400' : 'bg-slate-950/60 border-slate-800 text-slate-400 hover:border-slate-700' }}">
                            <span class="text-xl">📢</span>
                            <span class="text-[11px] font-bold">Teks Tengah</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- 4. MODAL LIVE MANAGEMENT KARAKTER          -->
    <!-- ========================================== -->
    @if ($isCharacterModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-xs bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-2xl space-y-4">

                <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-100 flex items-center gap-1.5">
                        <span><i class="fas fa-user"></i></span>
                        {{ $editingCharacterId ? 'Edit Tokoh Karakter' : 'Tambah Tokoh Baru' }}
                    </h3>
                    <button type="button" wire:click="closeCharacterModal"
                        class="text-slate-400 hover:text-slate-200 text-xs">✕</button>
                </div>

                <form wire:submit.prevent="saveQuickCharacter" class="space-y-3.5">

                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-3">
                        <label
                            class="w-12 h-12 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center cursor-pointer overflow-hidden shrink-0 hover:border-brand-500 transition relative group">
                            @if ($char_avatar_upload)
                                <img src="{{ $char_avatar_upload->temporaryUrl() }}"
                                    class="w-full h-full object-cover">
                            @elseif ($char_existing_avatar)
                                <img src="{{ Storage::url($char_existing_avatar) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-lg"><i class="fas fa-camera"></i></span>
                            @endif
                            <input type="file" wire:model="char_avatar_upload" accept="image/*" class="hidden">
                        </label>

                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-semibold text-slate-400 mb-0.5">Nama Tokoh</label>
                            <input type="text" wire:model="char_name" placeholder="Contoh: Alex"
                                class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('char_name')
                                <span class="text-[9px] text-rose-400 block font-bold mt-0.5">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Posisi Chat Default -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 mb-1">Posisi Chat Default</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label
                                class="flex items-center gap-1.5 p-2 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer hover:border-slate-600 transition {{ $char_position === 'left' ? 'border-brand-500 bg-brand-500/10' : '' }}">
                                <input type="radio" wire:model="char_position" value="left"
                                    class="text-brand-500 focus:ring-0">
                                <span class="text-xs font-bold text-slate-200"><i class="fa-solid fa-caret-left"></i>
                                    Kiri</span>
                            </label>
                            <label
                                class="flex items-center gap-1.5 p-2 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer hover:border-slate-600 transition {{ $char_position === 'right' ? 'border-brand-500 bg-brand-500/10' : '' }}">
                                <input type="radio" wire:model="char_position" value="right"
                                    class="text-brand-500 focus:ring-0">
                                <span class="text-xs font-bold text-slate-200">Kanan <i
                                        class="fa-solid fa-caret-right"></i></span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="closeCharacterModal"
                            class="px-3.5 py-1.5 bg-slate-800 text-slate-300 font-semibold text-xs rounded-xl">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-3.5 py-1.5 bg-brand-500 hover:bg-brand-600 text-slate-950 font-bold text-xs rounded-xl shadow-md shadow-brand-500/10 transition">
                            <span wire:loading.remove wire:target="saveQuickCharacter">Simpan Tokoh</span>
                            <span wire:loading wire:target="saveQuickCharacter">...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>

<style>
    .ql-image-resize-module {
        border: 1px dashed #38bdf8 !important;
        /* Sky 400 */
    }

    /* Titik/Handle penarik di sudut gambar */
    .ql-image-resize-module div {
        background-color: #38bdf8 !important;
        border: 1px solid #0f172a !important;
        border-radius: 4px;
        width: 10px !important;
        height: 10px !important;
    }

    /* Indikator Angka Ukuran Pixel */
    .ql-image-resize-module .ql-image-size-display {
        background-color: #020617 !important;
        color: #f1f5f9 !important;
        border: 1px solid #1e293b !important;
        border-radius: 6px;
        font-size: 11px;
        padding: 2px 6px;
    }

    /* Kustomisasi Toolbar Quill agar sesuai dengan Dark Theme Slate */
    .ql-toolbar.ql-snow {
        background-color: #020617;
        /* slate-950 */
        border-color: #1e293b !important;
        /* slate-800 */
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }

    .ql-container.ql-snow {
        border-color: #1e293b !important;
        /* slate-800 */
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        font-family: inherit;
        font-size: 0.875rem;
    }

    .ql-editor {
        min-height: 250px;
        color: #f1f5f9;
        /* slate-100 */
    }

    .ql-editor.ql-blank::before {
        color: #475569 !important;
        /* slate-600 */
        font-style: normal;
    }

    /* Warna ikon di toolbar */
    .ql-snow .ql-stroke {
        stroke: #94a3b8 !important;
    }

    .ql-snow .ql-fill {
        fill: #94a3b8 !important;
    }

    .ql-snow .ql-picker {
        color: #94a3b8 !important;
    }

    .ql-snow .ql-picker-options {
        background-color: #0f172a !important;
        /* slate-900 */
        border-color: #1e293b !important;
    }
</style>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-toast', (data) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#0f172a', // Slate 900
                color: '#f8fafc',
                customClass: {
                    popup: 'border border-slate-800 rounded-xl shadow-2xl'
                }
            });

            Toast.fire({
                icon: data.type || 'error',
                title: data.message
            });
        });
    });
</script>
