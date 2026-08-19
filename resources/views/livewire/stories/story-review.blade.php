<div class="bg-slate-50/50 mb-10">
    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-3">
        {{ $hasSubmitted ? 'Ulasan Kamu' : 'Berikan Rating Cerita' }}
    </h3>

    @if (session()->has('success'))
        <div class="py-3 mb-3 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-xl">
            🎉 {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="py-3 mb-3 text-xs font-bold text-rose-700 bg-rose-50 rounded-xl">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if (!$hasSubmitted && !auth()->check())
        <div class="text-center py-2 text-xs text-slate-400 font-medium">
            Silakan <a href="{{ route('login') }}" wire:navigate class="text-emerald-600 font-bold underline">login</a>
            terlebih dahulu untuk memberikan ulasan.
        </div>
    @else
        <form wire:submit.prevent="saveReview" class="space-y-3">
            {{-- ⭐️ Input Bintang Interaktif via Alpine.js --}}
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

            {{-- 📝 Input Teks Ulasan --}}
            <div>
                @if ($hasSubmitted)
                    <p
                        class="text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-100 leading-relaxed font-medium italic">
                        "{{ $review ?: 'Hanya memberikan rating bintang.' }}"
                    </p>
                    <button type="button" wire:click="$set('hasSubmitted', false)"
                        class="text-[10px] text-emerald-600 font-bold mt-2 hover:underline">
                        ✍️ Ubah Ulasan
                    </button>
                @else
                    <textarea wire:model="review" rows="3"
                        placeholder="Tulis pendapatmu tentang cerita ini, Bro... (maks 500 karakter)"
                        class="w-full p-3 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 font-medium placeholder-slate-300 transition"></textarea>
                    @error('review')
                        <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span>
                    @enderror

                    <button type="submit"
                        class="w-full py-2.5 bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-3xs transition">
                        Kirim Ulasan 🚀
                    </button>
                @endif
            </div>
        </form>
    @endif
</div>
