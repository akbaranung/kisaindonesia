<div>
    <main class="w-full my-auto">
        <div class="mb-8">
            <img src="images/logo-2.png" alt="logo">
        </div>

        @if (session()->has('success'))
            <div
                class="mb-6 p-4 bg-emerald-500 text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-200 flex items-center gap-3 animate-bounce">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="prosesLogin" class="flex flex-col gap-4">
            @csrf

            <div>
                <label for="email"
                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 px-1">Alamat
                    Email</label>
                <input type="email" wire:model="email" placeholder="nama@email.com"
                    class="w-full px-4 py-3.5 bg-white border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-400 focus:ring-1 focus:ring-brand-400 shadow-2xs">
                @error('email')
                    <span class="text-xs text-rose-500 mt-1 block px-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password"
                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 px-1">Password</label>
                <input type="password" wire:model="password" placeholder="••••••••"
                    class="w-full px-4 py-3.5 bg-white border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-400 focus:ring-1 focus:ring-brand-400 shadow-2xs">
                @error('password')
                    <span class="text-xs text-rose-500 mt-1 block px-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between px-1 text-xs">
                <label class="flex items-center gap-2 text-slate-600 font-medium">
                    <input type="checkbox" name="remember"
                        class="rounded border-slate-300 text-emerald-600 focus:ring-brand-400">
                    Ingat Saya
                </label>
                <a href="{{ route('password.request') }}" class="text-brand-500 font-semibold hover:text-brand-400">Lupa
                    Password?</a>
            </div>

            <button type="submit"
                class="w-full bg-brand-500 hover:bg-brand-400 text-white font-bold text-sm py-4 rounded-2xl transition shadow-xs mt-2">
                <span wire:loading.remove>Masuk ke Akun</span>
                <span wire:loading class="flex items-center gap-2">
                    Memverifikasi...
                </span>
            </button>
        </form>

        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-slate-400 text-xs font-medium">atau</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <a href="{{ url('auth/google') }}"
            class="w-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-sm py-3.5 rounded-2xl transition shadow-2xs flex items-center justify-center gap-2.5">
            <svg class="w-4 h-4" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1, 0, 0, 1, 0, 0)">
                    <path
                        d="M21.35,11.1H12v2.7h5.38c-0.24,1.28 -0.96,2.37 -2.04,3.1v2.56h3.3c1.93,-1.78 3.04,-4.4 3.04,-7.49C21.68,11.77 21.56,11.41 21.35,11.1Z"
                        fill="#4285F4" />
                    <path
                        d="M12,20.8c2.38,0 4.37,-0.79 5.82,-2.15l-3.3,-2.56c-0.91,0.61 -2.08,0.98 -3.34,0.98 -2.57,0 -4.75,-1.74 -5.53,-4.07H2.25v2.64C3.72,18.57 7.57,20.8 12,20.8Z"
                        fill="#34A853" />
                    <path
                        d="M6.47,13c-0.2,-0.61 -0.31,-1.26 -0.31,-1.93s0.11,-1.32 0.31,-1.93V6.5H2.25C1.59,7.83 1.22,9.33 1.22,10.93s0.37,3.1 1.03,4.43l3.3,-2.64L6.47,13Z"
                        fill="#FBBC05" />
                    <path
                        d="M12,5.27c1.3,0 2.46,0.45 3.38,1.32l2.53,-2.53C16.37,2.65 14.38,1.8 12,1.8 7.57,1.8 3.72,4.03 2.25,6.5l4.22,3.38C7.25,7.01 9.43,5.27 12,5.27Z"
                        fill="#EA4335" />
                </g>
            </svg>
            <span>Masuk dengan Google</span>
        </a>
    </main>

    <footer class="w-full text-center text-xs text-slate-500 py-2">
        Belum punya akun? <a href="/register" wire:navigate class="text-brand-500 font-bold hover:text-brand-400">Daftar
            Sekarang</a>
    </footer>
</div>
