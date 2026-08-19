<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-xl font-black text-slate-100 text-center mb-6">🔒 Buat Password Baru</h2>

        <form wire:submit.prevent="resetPassword" class="flex flex-col gap-4">
            <input type="hidden" wire:model="token">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Alamat Email</label>
                <input type="email" wire:model="email" readonly
                    class="w-full p-2.5 bg-slate-800/50 border border-slate-700/50 rounded-xl text-xs text-slate-400 cursor-not-allowed">
                @error('email')
                    <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Password Baru</label>
                <input type="password" wire:model="password" placeholder="Minimal 8 karakter"
                    class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                @error('password')
                    <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Konfirmasi Password Baru</label>
                <input type="password" wire:model="password_confirmation" placeholder="Ulangi password baru"
                    class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2 mt-2">
                <span wire:loading.remove wire:target="resetPassword">Simpan Password Baru</span>
                <span wire:loading wire:target="resetPassword" class="animate-pulse">Memproses...</span>
            </button>
        </form>
    </div>
</div>
