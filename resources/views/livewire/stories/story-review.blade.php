<div class="bg-slate-50/50 mb-10">
    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-3">
        {{ $hasSubmitted ? 'Ulasan Kamu' : 'Berikan Rating Cerita' }}
    </h3>

    @if (session()->has('success'))
        <div class="py-3 px-3 mb-3 text-xs font-bold text-brand-700 bg-brand-50 rounded-xl">
            <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="py-3 px-3 mb-3 text-xs font-bold text-rose-700 bg-rose-50 rounded-xl">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    @if (!$hasSubmitted && !auth()->check())
        <div class="text-center py-2 text-xs text-slate-400 font-medium">
            Silakan <a href="{{ route('login') }}" wire:navigate class="text-brand-600 font-bold underline">login</a>
            terlebih dahulu untuk memberikan ulasan.
        </div>
    @else
        <form wire:submit.prevent="saveReview" class="space-y-3 mb-6">
            <div x-data="{ currentRating: @entangle('rating') }" class="flex items-center gap-1.5">
                <template x-for="i in 5">
                    <button type="button" @click="if(!{{ $hasSubmitted ? 'true' : 'false' }}) { currentRating = i }"
                        class="text-xl transition transform active:scale-95 focus:outline-none"
                        :class="i <= currentRating ? 'text-amber-400' : 'text-slate-200'">
                        ★
                    </button>
                </template>
                <span class="text-xs font-bold text-slate-400 ml-2" x-text="'(' + currentRating + ' Bintang)'"></span>
            </div>

            <div>
                @if ($hasSubmitted)
                    <p
                        class="text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-100 leading-relaxed font-medium italic">
                        "{{ $review ?: 'Hanya memberikan rating bintang.' }}"
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <button type="button" wire:click="$set('hasSubmitted', false)"
                            class="text-[10px] text-brand-600 font-bold hover:underline flex items-center gap-1">
                            <span><i class="fa-solid fa-pencil"></i></span> Ubah Ulasan
                        </button>
                        <button type="button" wire:click="deleteReview"
                            wire:confirm="Yakin ingin menghapus ulasan kamu?"
                            class="text-[10px] text-rose-500 font-bold hover:underline flex items-center gap-1">
                            <span><i class="fa-solid fa-trash"></i></span> Hapus
                        </button>
                    </div>
                @else
                    <textarea wire:model="review" rows="3"
                        placeholder="Tulis pendapatmu tentang cerita ini, Bro... (maks 500 karakter)"
                        class="w-full p-3 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium placeholder-slate-300 transition resize-none"></textarea>
                    @error('review')
                        <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span>
                    @enderror

                    <button type="submit"
                        class="w-full py-2.5 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-3xs transition">
                        Kirim Ulasan <i class="fa-regular fa-paper-plane"></i>
                    </button>
                @endif
            </div>
        </form>
    @endif

    <div class="mt-6 pt-6 border-t border-slate-200/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">
                Ulasan Pembaca ({{ $reviews->count() }})
            </h3>
            @if ($reviews->count() > 0)
                <div
                    class="flex items-center gap-1 text-xs font-bold text-amber-500 bg-amber-50 px-2.5 py-1 rounded-lg">
                    <span><i class="fa-solid fa-star"></i></span>
                    <span>{{ round($reviews->avg('rating'), 1) }} / 5</span>
                </div>
            @endif
        </div>

        <div class="space-y-3">
            @forelse ($reviews as $rev)
                <div
                    class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-3xs transition hover:border-slate-200">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-full overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200/60">
                                @if ($rev->user?->profile_photo_url)
                                    <img src="{{ $rev->user->profile_photo_url }}"
                                        alt="{{ $rev->user->name ?? 'User' }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center font-bold text-xs text-slate-500 bg-slate-200">
                                        {{ strtoupper(substr($rev->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800">
                                        {{ $rev->user->name ?? 'Pembaca' }}
                                    </span>
                                    @if (auth()->check() && $rev->user_id === auth()->id())
                                        <span
                                            class="text-[9px] font-extrabold bg-brand-50 text-brand-600 px-1.5 py-0.5 rounded-md">
                                            Kamu
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ $rev->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Rating Bintang --}}
                        <div class="flex items-center text-xs text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    @if (!empty($rev->review))
                        <p class="text-xs text-slate-600 leading-relaxed font-medium mt-1">
                            {{ $rev->review }}
                        </p>
                    @else
                        <p class="text-[11px] text-slate-400 italic mt-1">
                            Hanya memberikan rating bintang.
                        </p>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 bg-white/60 rounded-2xl border border-dashed border-slate-200 p-4">
                    <span class="text-2xl mb-1 block">💬</span>
                    <p class="text-xs text-slate-400 font-semibold">Belum ada ulasan dari pembaca lain.</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Jadilah yang pertama memberikan penilaian untuk cerita
                        ini!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
