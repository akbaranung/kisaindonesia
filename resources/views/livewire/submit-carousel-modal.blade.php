<div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="w-full max-w-md p-6 bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-black text-slate-800">Ajukan Banner Hero Carousel</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="submit" class="mt-4 space-y-4">
                    {{-- Saldo Kisa Bean --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-200">
                        <span class="text-xs font-bold text-amber-800">Kisa Bean Kamu:</span>
                        <span class="text-sm font-black text-amber-600">
                            🫘 {{ number_format(auth()->user()->kisa_bean_balance ?? 0) }} Bean
                        </span>
                    </div>

                    @error('beans')
                        <p class="text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- Pilih Cerita --}}
                    <div>
                        <label class="block mb-1 text-xs font-bold text-slate-700">Pilih Cerita</label>
                        <select wire:model="storyId"
                            class="w-full px-3 py-2 text-xs border rounded-xl border-slate-200 focus:ring-2 focus:ring-brand-500">
                            <option value="">-- Pilih Cerita Kamu --</option>
                            @foreach ($userStories as $story)
                                <option value="{{ $story->id }}">{{ $story->title }}</option>
                            @endforeach
                        </select>
                        @error('storyId')
                            <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Durasi Promosi --}}
                    <div>
                        <label class="block mb-1 text-xs font-bold text-slate-700">Durasi Promosi (Hari)</label>
                        <input type="number" wire:model.live="durationDays" min="1" max="30"
                            class="w-full px-3 py-2 text-xs border rounded-xl border-slate-200 focus:ring-2 focus:ring-brand-500" />
                        <p class="text-[10px] text-slate-400 mt-1">Biaya: {{ $costPerDay }} Kisa Bean / hari</p>
                        @error('durationDays')
                            <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Total Biaya --}}
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-600">Total Potongan:</span>
                        <span class="text-sm font-extrabold text-brand-600">
                            🫘 {{ number_format($this->calculatedCost) }} Kisa Bean
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="w-1/2 py-2 text-xs font-bold text-slate-600 rounded-xl bg-slate-100 hover:bg-slate-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-1/2 py-2 text-xs font-bold text-white rounded-xl bg-brand-600 hover:bg-brand-700 transition shadow-sm">
                            Ajukan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
