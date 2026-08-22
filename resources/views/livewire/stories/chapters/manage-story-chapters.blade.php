<div class="min-h-screen text-slate-100 pb-20">
    <div class="sticky top-0 z-3 backdrop-blur-md border-b px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('my-stories') }}" class="text-slate-800 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-800 line-clamp-1">{{ $story->title }}</h1>
                <p class="text-[10px] text-slate-400">Kelola Bab Cerita</p>
            </div>
        </div>

        <button wire:click="openCreateModal"
            class="px-3 py-1.5 bg-brand-500 text-slate-100 font-bold rounded-lg text-xs flex items-center gap-2 shadow-md shadow-amber-500/10">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>
        </button>
    </div>

    <div class="p-4 space-y-3">
        @if (session()->has('message'))
            <div class="p-3 bg-brand-950/80 border border-brand-800 text-brand-300 text-xs rounded-xl">✓
                {{ session('message') }}</div>
        @endif

        <div class="space-y-2.5">
            @forelse($chapters as $chap)
                <div
                    class="p-3.5 border border-slate-100 rounded-xl flex items-center justify-between gap-3 hover:border-brand-500 shadow-md">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span
                                class="text-[10px] font-bold text-brand-500 bg-brand-500/10 px-2 py-0.5 rounded border border-brand-500/20">Bab
                                {{ $chap->order_number }}</span>
                            <span
                                class="text-[9px] font-bold {{ $chap->status === 'published' ? 'text-brand-400' : 'text-slate-500' }}">●
                                {{ ucfirst($chap->status) }}</span>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 line-clamp-1">{{ $chap->title }}</h3>
                        <p class="text-[10px] text-slate-400">{{ number_format($chap->word_count ?? 0) }} kata</p>
                    </div>

                    <a href="{{ route('chapters.editor', ['story' => $story->id, 'chapter' => $chap->id]) }}"
                        class="px-3 py-2 bg-brand-500 border border-brand-500 text-slate-100 font-bold text-xs rounded-lg shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                </div>
            @empty
                <div class="p-8 text-center bg-slate-200/50 border border-slate-100 rounded-2xl">
                    <p class="text-xs text-slate-600">Belum ada bab. Klik <strong>Bab Baru</strong> untuk mulai.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL INISIASI -->
    @if ($isCreateModalOpen)
        <div
            class="fixed inset-0 z-50 flex items-center sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm p-0 sm:p-4">
            <div
                class="w-full max-w-md bg-slate-900 border-t sm:border border-slate-800 rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-xs font-bold text-slate-100"><i class="fa-solid fa-book-open"></i> Buat Chapter Baru
                    </h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-200 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="createAndRedirect" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-300 mb-1">Judul Chapter</label>
                        <input type="text" wire:model="title" placeholder="Contoh: Bab 1 - Pertemuan"
                            class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500">
                        @error('title')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-300 mb-1">Jenis Content</label>
                        @if ($story->type === 'puisi')
                            {{-- Jika Cerita = Puisi --}}
                            <select wire:model="type"
                                class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 capitalize">
                                <option value="regular">Regular</option>
                            </select>
                            <span class="text-[11px] text-slate-400 mt-1 block">Tipe bab untuk cerita puisi otomatis
                                berformat Regular.</span>
                        @else
                            <select wire:model="type"
                                class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 capitalize">
                                <option value="regular">Regular</option>
                                <option value="chat">Chat Fic (Percakapan Chat)</option>
                            </select>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="closeCreateModal"
                            class="px-4 py-2 bg-slate-800 text-slate-300 text-xs rounded-xl">Batal</button>
                        <button type="submit" wire:loading.remove wire:target="createAndRedirect"
                            class="px-4 py-2 bg-brand-500 text-slate-950 font-bold text-xs rounded-xl">Masuk
                            Editor</button>
                        <button type="button" wire:loading wire:target="createAndRedirect"
                            class="px-4 py-2 bg-brand-500 text-slate-950 font-bold text-xs rounded-xl" disabled>Loading
                            ...</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
