<form wire:submit.prevent="updateProfile" class="flex flex-col gap-6">
    <div class="rounded-2xl p-5 flex flex-col items-center text-center shadow-xl h-fit">
        <div class="relative flex flex-col items-center">
            <div class="relative">
                <div class="w-28 h-28 rounded-[2rem] overflow-hidden border-4 border-[#38CAC8] shadow-xl bg-white">
                    @if ($avatar_temp)
                        <img src="{{ $avatar_temp->temporaryUrl() }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover">
                    @endif

                    <div wire:loading wire:target="avatar_temp"
                        class="absolute inset-0 bg-slate-950/80 rounded-full flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-emerald-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <label
                    class="cursor-pointer bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold py-2 px-4 rounded-xl border border-slate-700 transition w-full block text-center mb-2">
                    📸 Pilih Foto Baru
                    <input type="file" wire:model="avatar_temp" accept="image/*" class="hidden">
                </label>

                @error('avatar_temp')
                    <span class="text-[10px] text-rose-400 font-bold block mb-2">{{ $message }}</span>
                @enderror

                <p class="text-[10px] text-slate-500">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-5">
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Nama
                Lengkap</label>
            <input type="text" wire:model="name"
                class="w-full px-4 py-3.5 bg-slate-50 border @error('name') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition-all shadow-2xs">
            @error('name')
                <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label
                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Email</label>
            <input type="text" wire:model="email"
                class="w-full py-3.5 bg-slate-50 border @error('email') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition-all shadow-2xs">
            @error('email')
                <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Nomor
                WhatsApp</label>
            <input type="text" wire:model="phone" placeholder="Contoh: 08123456789"
                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition-all shadow-2xs">
        </div>

        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Bio /
                Deskripsi Singkat</label>
            <textarea wire:model="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu..."
                class="w-full px-4 py-3.5 bg-slate-50 border @error('bio') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition-all shadow-2xs resize-none"></textarea>
            @error('bio')
                <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold text-sm py-4 rounded-2xl transition-all duration-300 shadow-lg mt-2 relative flex items-center justify-center">

            <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>

            <span wire:loading wire:target="updateProfile" class="flex items-center gap-1">
                Menyimpan...
            </span>
        </button>
    </div>
</form>
