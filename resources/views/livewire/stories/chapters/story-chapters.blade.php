<div class="w-full min-h-screen bg-slate-50/50 pb-12">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <nav class="items-center justify-between w-full pb-4 border-b border-slate-100 mb-6">
        <a href="{{ route('my-stories') }}" wire:navigate
            class="text-slate-500 hover:text-slate-800 text-xs font-bold flex items-center gap-1 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div class="flex flex-col items-center">
            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.2em]">Workspace Studio</span>
            <h2 class="text-sm font-black text-slate-800 truncate max-w-[180px]">{{ $story->title }}</h2>
        </div>
        <div class="w-16"></div>
    </nav>

    @if ($chapterAction === 'list')
        @include('livewire.stories.chapters.chapter-lists')
        @if ($isCreateModalOpen)
            <div
                class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm p-0 sm:p-4">
                <div
                    class="w-full max-w-lg bg-slate-900 border-t sm:border border-slate-800 rounded-t-2xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto animate-in slide-in-from-bottom duration-200">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-sm font-bold text-slate-100 flex items-center gap-2">
                            Buat Chapter Baru
                        </h3>
                        <button wire:click="closeCreateModal"
                            class="text-slate-400 hover:text-slate-200 text-sm">✕</button>
                    </div>

                    <!-- Form Inisiasi -->
                    <form wire:submit.prevent="createAndRedirect" class="space-y-4">

                        <!-- 1. Input Judul Chapter -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Judul Chapter</label>
                            <input type="text" wire:model="title" placeholder="Contoh: Bab 1 - Awal Pertemuan"
                                class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 placeholder-slate-500">
                            @error('title')
                                <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 2. Input Jenis Chapter -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Jenis Chapter</label>
                            <select wire:model.live="type"
                                class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 capitalize">
                                <option value="regular">Novel / Reguler (Paragraf Teks)</option>
                                <option value="chat">Chat Fic (Percakapan Chat)</option>
                                <option value="puisi">Puisi (Bait & Lirik)</option>
                            </select>
                        </div>

                        <!-- 3. MANAJEMEN KARAKTER (Hanya Muncul Jika Jenis = Chat Fic) -->
                        @if (strtolower($type) === 'chat')
                            <div class="p-3.5 bg-slate-950/80 border border-slate-800 rounded-xl space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-amber-400 flex items-center gap-1">
                                        Kelola Karakter Chat
                                    </span>
                                    <span class="text-[10px] text-slate-500">Minimal 1 Karakter</span>
                                </div>

                                <!-- Form Tambah Karakter Baru -->
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model="newCharacterName"
                                        placeholder="Nama Karakter (cth: Rian)"
                                        class="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 placeholder-slate-500">

                                    <select wire:model="newCharacterPosition"
                                        class="px-2.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none">
                                        <option value="left">Kiri</option>
                                        <option value="right">Kanan</option>
                                    </select>

                                    <button type="button" wire:click="addCharacter"
                                        class="px-3 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl shrink-0">
                                        Tambah
                                    </button>
                                </div>
                                @error('newCharacterName')
                                    <span class="text-[10px] text-rose-400 font-bold block">{{ $message }}</span>
                                @enderror

                                <!-- Daftar Karakter Terdaftar -->
                                <div class="space-y-2 max-h-36 overflow-y-auto pt-1">
                                    @forelse($characters as $index => $char)
                                        <div
                                            class="flex items-center justify-between p-2 bg-slate-900 border border-slate-800 rounded-lg text-xs">
                                            <span class="font-semibold text-slate-200">{{ $char['name'] }}</span>

                                            <div class="flex items-center gap-2">
                                                <!-- Toggle Posisi Chat (Kiri / Kanan) -->
                                                <button type="button"
                                                    wire:click="toggleCharacterPosition({{ $index }})"
                                                    class="px-2 py-0.5 text-[10px] font-bold rounded border {{ $char['position'] === 'left' ? 'bg-slate-800 text-slate-300 border-slate-700' : 'bg-amber-500/20 text-amber-300 border-amber-500/30' }}">
                                                    {{ $char['position'] === 'left' ? 'Kiri' : 'Kanan' }}
                                                </button>

                                                <!-- Hapus Karakter -->
                                                <button type="button" wire:click="removeCharacter({{ $index }})"
                                                    class="text-rose-400 hover:text-rose-300 text-xs font-bold px-1">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-[11px] text-slate-500 italic text-center py-2">Belum ada karakter
                                            ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- 4. Status Awal Chapter -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Status Chapter</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label
                                    class="flex items-center gap-2 p-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl cursor-pointer">
                                    <input type="radio" wire:model="status" value="draft"
                                        class="text-amber-500 focus:ring-0">
                                    <div>
                                        <span class="text-xs font-bold text-slate-200 block">Draft</span>
                                        <span class="text-[9px] text-slate-400 block">Simpan dulu</span>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center gap-2 p-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl cursor-pointer">
                                    <input type="radio" wire:model="status" value="published"
                                        class="text-amber-500 focus:ring-0">
                                    <div>
                                        <span class="text-xs font-bold text-emerald-400 block">Published</span>
                                        <span class="text-[9px] text-slate-400 block">Langsung rilis</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                            <button type="button" wire:click="closeCreateModal"
                                class="px-4 py-2 bg-slate-800 text-slate-300 font-semibold text-xs rounded-xl">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl flex items-center gap-1 shadow-md shadow-amber-500/10">
                                Masuk Editor Full
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endif
    @elseif($chapterAction == 'view')
        @include('livewire.stories.chapters.chapter-view');
    @elseif($chapterAction == 'editor')
        @include('livewire.stories.chapters.chapter-editor');
    @endif
</div>

@push('scripts')
    <script>
        function initQuill() {
            const editorContainer = document.querySelector('#quill-editor');

            // Jika elemen tidak ada atau Quill sudah terinisialisasi, stop.
            if (!editorContainer || editorContainer.classList.contains('ql-container')) return;

            const AllowedFormats = [
                'bold',
                'italic',
                'underline',
                'strike',
                'blockquote',
                'code-block',
                'header',
                'list',
                'script',
                'indent',
                'direction',
                'size',
                'link',
                'image',
                'video'
            ];

            // 1. Inisialisasi Quill
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                formats: AllowedFormats,
                placeholder: 'Tumpahkan ide cerita hebatmu di sini, Bro...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'blockquote'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['clean']
                    ]
                }
            });



            // 2. Ambil data awal dari Livewire
            let initialContent = @this.get('content') || '';
            quill.root.innerHTML = initialContent;

            // 3. Kirim data ke Livewire saat ngetik
            quill.on('text-change', function() {
                let currentHtml = quill.root.innerHTML;
                if (currentHtml === '<p><br></p>') currentHtml = '';
                @this.set('content', currentHtml,
                    false); // false mencegah lag/render ulang instan yang bikin kursor lompat
            });

            // 4. Reset Editor jika ada trigger event
            Livewire.on('reset-editor', () => {
                quill.root.innerHTML = '';
            });
        }

        // Jalankan setiap kali Livewire selesai melakukan update komponen di layar
        document.addEventListener('livewire:navigated', initQuill);
        document.addEventListener('DOMContentLoaded', initQuill);

        // Backup: Pemicu berkala jika tombol "Bab Baru" diklik tanpa pindah halaman
        setInterval(() => {
            const editorContainer = document.querySelector('#quill-editor');
            if (editorContainer && !editorContainer.classList.contains('ql-container')) {
                initQuill();
            }
        }, 500);
    </script>
@endpush
</div>
