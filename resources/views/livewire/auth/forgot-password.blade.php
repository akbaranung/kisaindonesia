<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-xl font-black text-slate-100 text-center mb-2">🔑 Lupa Password?</h2>
        <p class="text-xs text-slate-400 text-center mb-6">
            Masukkan alamat email kamu. Kami akan mengirimkan tautan untuk mengatur ulang password.
        </p>

        @if (session('status'))
            <div
                class="mb-4 text-xs text-emerald-400 font-bold bg-emerald-950/50 border border-emerald-800/50 p-3 rounded-xl text-center">
                ✓ {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetPasswordLink" class="flex flex-col gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Alamat Email</label>
                <input type="email" wire:model="email" placeholder="nama@email.com"
                    class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                @error('email')
                    <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="sendResetPasswordLink">Kirim Link Reset Password</span>
                <span wire:loading wire:target="sendResetPasswordLink" class="animate-pulse">Mengirim...</span>
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-slate-200 transition">
                ← Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>
