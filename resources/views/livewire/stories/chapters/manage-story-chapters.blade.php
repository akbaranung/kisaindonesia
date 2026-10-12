<div
    class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 pb-20 transition-colors duration-200">
    <div
        class="sticky top-0 z-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('my-stories') }}"
                class="text-slate-800 dark:text-slate-200 hover:text-slate-600 dark:hover:text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-800 dark:text-slate-100 line-clamp-1">{{ $story->title }}</h1>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Kelola Bab Cerita</p>
            </div>
        </div>

        <button wire:click="openCreateModal"
            class="px-3 py-1.5 bg-brand-500 text-white font-bold rounded-lg text-xs flex items-center gap-2 shadow-md shadow-brand-500/10 hover:bg-brand-600 transition">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>
            <span>Bab Baru</span>
        </button>
    </div>

    <div class="p-4 space-y-3">
        @if (session()->has('message'))
            <div
                class="p-3 bg-brand-50 dark:bg-brand-950/80 border border-brand-200 dark:border-brand-800 text-brand-700 dark:text-brand-300 text-xs rounded-xl">
                ✓ {{ session('message') }}
            </div>
        @endif

        @php
            // Optimasi N+1 Query: Ambil seluruh ID bab yang sudah pernah dibeli sekali jalan
            $chapterIds = $chapters->pluck('id')->toArray();
            $purchasedChapterIds = \Illuminate\Support\Facades\DB::table('user_purchased_chapters')
                ->whereIn('chapter_id', $chapterIds)
                ->pluck('chapter_id')
                ->toArray();
        @endphp

        <div class="space-y-2.5">
            @forelse($chapters as $chap)
                @php
                    $isDraft = $chap->status === 'draft';
                    $hasPurchases = in_array($chap->id, $purchasedChapterIds);
                    $canDelete = $isDraft && !$hasPurchases;
                @endphp
                <div wire:key="chap-{{ $chap->id }}"
                    class="p-3.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center justify-between gap-3 hover:border-brand-500 dark:hover:border-brand-500 shadow-2xs transition">
                    <div class="flex items-center gap-3 space-y-0">
                        @if ($chap->cover_url)
                            <img src="{{ $chap->cover_url }}" alt="Cover {{ $chap->title }}"
                                class="w-12 h-14 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                        @else
                            <div
                                class="w-12 h-14 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center shrink-0 text-slate-400 dark:text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                        @endif
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-bold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 px-2 py-0.5 rounded border border-brand-200 dark:border-brand-900">
                                    Bab {{ $chap->order_number }}
                                </span>
                                <span
                                    class="text-[9px] font-bold {{ $chap->status === 'published' ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}">
                                    ● {{ ucfirst($chap->status) }}
                                </span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 line-clamp-1">
                                {{ $chap->title }}</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">
                                {{ number_format($chap->word_count ?? 0) }} kata</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($canDelete)
                            <button type="button" wire:click="deleteChapter({{ $chap->id }})"
                                wire:confirm="Hapus Bab {{ $chap->order_number }}? Urutan bab setelahnya akan disesuaikan."
                                wire:loading.attr="disabled" wire:target="deleteChapter({{ $chap->id }})"
                                class="px-3 py-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900 rounded-lg text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/60 transition flex items-center gap-1">
                                <span wire:loading.remove wire:target="deleteChapter({{ $chap->id }})">Hapus</span>
                                <span wire:loading wire:target="deleteChapter({{ $chap->id }})">...</span>
                            </button>
                        @else
                            <button type="button" disabled
                                class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-lg text-xs font-medium cursor-not-allowed border border-slate-200 dark:border-slate-700"
                                title="{{ !$isDraft ? 'Bab sudah dipublikasikan' : 'Bab sudah dibeli oleh pembaca' }}">
                                Terkunci
                            </button>
                        @endif

                        <a href="{{ route('chapters.editor', ['story' => $story->id, 'chapter' => $chap->id]) }}"
                            class="px-3 py-1.5 bg-brand-500 border border-brand-500 text-white font-bold text-xs rounded-lg shrink-0 flex items-center justify-center hover:bg-brand-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div
                    class="p-8 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada bab. Klik <strong>Bab Baru</strong>
                        untuk mulai.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL INISIASI -->
    @if ($isCreateModalOpen)
        <div
            class="fixed inset-0 z-50 flex items-center sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm p-0 sm:p-4">
            <div
                class="w-full max-w-md bg-white dark:bg-slate-900 border-t sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">
                        <i class="fa-solid fa-book-open"></i> Buat Chapter Baru
                    </h3>
                    <button wire:click="closeCreateModal"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="createAndRedirect" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul
                            Chapter</label>
                        <input type="text" wire:model="title" placeholder="Contoh: Bab 1 - Pertemuan"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-500">
                        @error('title')
                            <span
                                class="text-[10px] text-rose-500 dark:text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Cover
                            Chapter (Opsional)</label>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-14 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($cover_upload)
                                    <img src="{{ $cover_upload->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 text-slate-400 dark:text-slate-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="cover_upload" accept="image/*"
                                    class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-200 hover:file:bg-slate-200 dark:hover:file:bg-slate-700 cursor-pointer">
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Format: JPG, PNG, WEBP
                                    (Maks 2MB)</p>
                            </div>
                        </div>
                        @error('cover_upload')
                            <span
                                class="text-[10px] text-rose-500 dark:text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis
                            Content</label>
                        @if ($story->type === 'puisi')
                            <select wire:model="type"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-500 capitalize">
                                <option value="regular">Regular</option>
                            </select>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">Tipe bab untuk
                                cerita puisi otomatis berformat Regular.</span>
                        @else
                            <select wire:model="type"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-500 capitalize">
                                <option value="regular">Regular</option>
                                <option value="chat">Chat Fic (Percakapan Chat)</option>
                            </select>
                        @endif
                    </div>

                    <div
                        class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="closeCreateModal"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit" wire:loading.remove wire:target="createAndRedirect"
                            class="px-4 py-2 bg-brand-500 text-white font-bold text-xs rounded-xl hover:bg-brand-600 transition">Masuk
                            Editor</button>
                        <button type="button" wire:loading wire:target="createAndRedirect"
                            class="px-4 py-2 bg-brand-500 text-white font-bold text-xs rounded-xl opacity-75 cursor-not-allowed"
                            disabled>Loading ...</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
