@extends('layouts.app')

@section('title', 'Profil Saya - Kisa')

@section('content')
    <header
        class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50 px-4 py-4 flex items-center justify-between shadow-xs transition-colors">
        <h1 class="text-base font-bold tracking-tight text-slate-800 dark:text-slate-100">Profil Akun</h1>
        <!-- Fast Theme Toggle in Header -->
        <button onclick="toggleThemeAndUpdateUI()" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
            <i class="fa-solid fa-moon hidden dark:block text-slate-200"></i>
            <i class="fa-solid fa-sun block dark:hidden text-amber-500"></i>
        </button>
    </header>

    <section class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 p-6 flex items-center gap-4 transition-colors">
        <div
            class="w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-black uppercase shadow-md shadow-emerald-600/20">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 truncate leading-tight mb-0.5">{{ Auth::user()->name }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mb-2">{{ Auth::user()->email }}</p>
            <span
                class="bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-100 dark:border-emerald-800/50 uppercase">Pembaca
                Setia</span>
        </div>
    </section>

    <section class="p-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 grid grid-cols-3 gap-2 text-center shadow-2xs transition-colors">
            <div class="border-r border-slate-100 dark:border-slate-800">
                <span class="block text-base font-bold text-slate-900 dark:text-slate-100 font-serif">12</span>
                <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rak Buku</span>
            </div>
            <div class="border-r border-slate-100 dark:border-slate-800">
                <span class="block text-base font-bold text-slate-900 dark:text-slate-100 font-serif">4</span>
                <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mengikuti</span>
            </div>
            <div>
                <span class="block text-base font-bold text-slate-900 dark:text-slate-100 font-serif">0</span>
                <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Karya</span>
            </div>
        </div>
    </section>

    <main class="px-4 py-2 flex flex-col gap-3">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-2xs flex flex-col transition-colors">
            <a href="#"
                class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 transition">
                <div class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                    <i class="fa-solid fa-user text-slate-400"></i>
                    <span>Ubah Informasi Profil</span>
                </div>
                <span class="text-slate-400 text-xs">&rarr;</span>
            </a>
            
            <!-- Dark Mode Toggle Switch -->
            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/60 transition" onclick="toggleThemeAndUpdateUI()">
                <div class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                    <i id="theme-icon-label" class="fa-solid fa-moon text-slate-400 dark:text-amber-400"></i>
                    <span>Mode Gelap (Dark Mode)</span>
                </div>
                <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-slate-200 dark:bg-[#38CAC8]" role="switch" aria-checked="false" id="theme-switch-btn">
                    <span id="theme-switch-thumb" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out translate-x-0 dark:translate-x-5"></span>
                </button>
            </div>
        </div>

        <div class="mt-4">
            <form action="{{ route('logout') }}" method="POST"
                onsubmit="return confirm('Apakah kamu yakin ingin keluar?');">
                @csrf
                <button type="submit"
                    class="w-full bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50 font-semibold text-sm py-3.5 rounded-2xl transition">
                    Keluar dari Akun (Logout)
                </button>
            </form>
        </div>
    </main>

@push('scripts')
<script>
    function toggleThemeAndUpdateUI() {
        const next = toggleTheme();
        updateThemeUIState();
    }

    function updateThemeUIState() {
        const isDark = document.documentElement.classList.contains('dark');
        const switchBtn = document.getElementById('theme-switch-btn');
        const switchThumb = document.getElementById('theme-switch-thumb');
        const labelIcon = document.getElementById('theme-icon-label');

        if (switchThumb && switchBtn) {
            if (isDark) {
                switchBtn.classList.remove('bg-slate-200');
                switchBtn.classList.add('bg-[#38CAC8]');
                switchThumb.classList.remove('translate-x-0');
                switchThumb.classList.add('translate-x-5');
                if (labelIcon) {
                    labelIcon.className = "fa-solid fa-sun text-amber-400";
                }
            } else {
                switchBtn.classList.remove('bg-[#38CAC8]');
                switchBtn.classList.add('bg-slate-200');
                switchThumb.classList.remove('translate-x-5');
                switchThumb.classList.add('translate-x-0');
                if (labelIcon) {
                    labelIcon.className = "fa-solid fa-moon text-slate-400";
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateThemeUIState();
    });

    document.addEventListener('livewire:navigated', () => {
        updateThemeUIState();
    });

    window.addEventListener('theme-changed', () => {
        updateThemeUIState();
    });
</script>
@endpush
@endsection

