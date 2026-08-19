<div>
    <form wire:submit.prevent="updatePassword" class="flex flex-col gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-800 mb-1">Password saat Ini</label>
            <input type="password" wire:model="current_password"
                class="w-full p-2.5 border border-slate-700 rounded-xl text-xs text-slate-500 focus:outline-none focus:border-[#38CAC8]">
            @error('current_password')
                <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">Password Baru</label>
                <input type="password" wire:model="new_password"
                    class="w-full p-2.5 border border-slate-700 rounded-xl text-xs text-slate-500 focus:outline-none focus:border-[#38CAC8]">
                @error('new_password')
                    <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">Konfirmasi Password Baru</label>
                <input type="password" wire:model="new_password_confirmation"
                    class="w-full p-2.5 border border-slate-700 rounded-xl text-xs text-slate-500 focus:outline-none focus:border-[#38CAC8]">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="bg-[#38CAC8] disabled:opacity-50 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="updatePassword">Ubah Password</span>
                <span wire:loading wire:target="updatePassword" class="animate-pulse">Memproses...</span>
            </button>
        </div>
    </form>
</div>
