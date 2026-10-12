<div class="bg-slate-50/50 dark:bg-transparent mb-10 transition-colors">
    <h3 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
        {{ $hasSubmitted ? 'Ulasan Kamu' : 'Berikan Rating Cerita' }}
    </h3>

    @if (session()->has('success'))
        <div class="py-3 px-3 mb-3 text-xs font-bold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-950/60 border border-brand-200 dark:border-brand-800/60 rounded-xl">
            <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="py-3 px-3 mb-3 text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60 rounded-xl">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    @if (!$hasSubmitted && !auth()->check())
        <div class="text-center py-2 text-xs text-slate-400 dark:text-slate-500 font-medium">
            Silakan <a href="{{ route('login') }}" wire:navigate class="text-brand-600 dark:text-brand-400 font-bold underline">login</a>
            terlebih dahulu untuk memberikan ulasan.
        </div>
    @else
        <form wire:submit.prevent="saveReview" class="space-y-3 mb-6">
            <div x-data="{ currentRating: @entangle('rating') }" class="flex items-center gap-1.5">
                <template x-for="i in 5">
                    <button type="button" @click="if(!{{ $hasSubmitted ? 'true' : 'false' }}) { currentRating = i }"
                        class="text-xl transition transform active:scale-95 focus:outline-none"
                        :class="i <= currentRating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'">
                        ★
                    </button>
                </template>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 ml-2" x-text="'(' + currentRating + ' Bintang)'"></span>
            </div>

            <div>
                @if ($hasSubmitted)
                    <p
                        class="text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900 p-3 rounded-xl border border-slate-100 dark:border-slate-800 leading-relaxed font-medium italic">
                        "{{ $review ?: 'Hanya memberikan rating bintang.' }}"
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <button type="button" wire:click="$set('hasSubmitted', false)"
                            class="text-[10px] text-brand-600 dark:text-brand-400 font-bold hover:underline flex items-center gap-1">
                            <span><i class="fa-solid fa-pencil"></i></span> Ubah Ulasan
                        </button>
                        <button type="button" wire:click="deleteReview"
                            wire:confirm="Yakin ingin menghapus ulasan kamu?"
                            class="text-[10px] text-rose-500 dark:text-rose-400 font-bold hover:underline flex items-center gap-1">
                            <span><i class="fa-solid fa-trash"></i></span> Hapus
                        </button>
                    </div>
                @else
                    <textarea wire:model="review" rows="3"
                        placeholder="Tulis pendapatmu tentang cerita ini, Bro... (maks 500 karakter)"
                        class="w-full p-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:border-brand-500 font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition resize-none"></textarea>
                    @error('review')
                        <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span>
                    @enderror

                    <button type="submit"
                        class="w-full py-2.5 bg-slate-900 dark:bg-brand-600 hover:bg-brand-600 dark:hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-3xs transition">
                        Kirim Ulasan <i class="fa-regular fa-paper-plane"></i>
                    </button>
                @endif
            </div>
        </form>
    @endif

    <div class="mt-6 pt-6 border-t border-slate-200/60 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                Ulasan Pembaca ({{ $reviews->count() }})
            </h3>
            @if ($reviews->count() > 0)
                <div
                    class="flex items-center gap-1 text-xs font-bold text-amber-500 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border border-transparent dark:border-amber-800/40 px-2.5 py-1 rounded-lg">
                    <span><i class="fa-solid fa-star"></i></span>
                    <span>{{ round($reviews->avg('rating'), 1) }} / 5</span>
                </div>
            @endif
        </div>

        <div class="space-y-3">
            @forelse ($reviews as $rev)
                <div
                    class="bg-white dark:bg-slate-900 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-3xs transition hover:border-slate-200 dark:hover:border-slate-700">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200/60 dark:border-slate-700">
                                @if ($rev->user?->profile_photo_url)
                                    <img src="{{ $rev->user->profile_photo_url }}"
                                        alt="{{ $rev->user->name ?? 'User' }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center font-bold text-xs text-slate-500 dark:text-slate-400 bg-slate-200 dark:bg-slate-800">
                                        {{ strtoupper(substr($rev->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                        {{ $rev->user->name ?? 'Pembaca' }}
                                    </span>
                                    @if (auth()->check() && $rev->user_id === auth()->id())
                                        <span
                                            class="text-[9px] font-extrabold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 px-1.5 py-0.5 rounded-md border border-transparent dark:border-brand-900/40">
                                            Kamu
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $rev->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Rating Bintang --}}
                        <div class="flex items-center text-xs text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    @if (!empty($rev->review))
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium mt-1">
                            {{ $rev->review }}
                        </p>
                    @else
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 italic mt-1">
                            Hanya memberikan rating bintang.
                        </p>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 bg-white/60 dark:bg-slate-900/40 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-4">
                    <span class="text-2xl mb-1 block">💬</span>
                    <p class="text-xs text-slate-400 dark:text-slate-400 font-semibold">Belum ada ulasan dari pembaca lain.</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Jadilah yang pertama memberikan penilaian untuk cerita
                        ini!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
