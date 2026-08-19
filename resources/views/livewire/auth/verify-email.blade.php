<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl text-center">
        <div class="text-4xl mb-4">✉️</div>

        <h2 class="text-xl font-bold text-slate-100 mb-2">Verifikasi Email Kamu</h2>

        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Terima kasih telah mendaftar! Tautan verifikasi telah dikirimkan ke alamat email kamu. Silakan klik tautan
            tersebut untuk mengaktifkan akun.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div
                class="mb-4 text-xs text-emerald-400 font-bold bg-emerald-950/50 border border-emerald-800/50 p-3 rounded-xl">
                ✓ Tautan verifikasi baru berhasil dikirimkan ulang!
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <button wire:click="resendVerificationEmail" wire:loading.attr="disabled"
                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="resendVerificationEmail">Kirim Ulang Email Verifikasi</span>
                <span wire:loading wire:target="resendVerificationEmail" class="animate-pulse">Mengirim...</span>
            </button>

            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-slate-200 transition mt-2">
                ← Kembali ke Login
            </a>
        </div>
    </div>
</div>
