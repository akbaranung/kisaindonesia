@extends('layouts.app')

@section('title', 'Kisa - Beranda')

@section('content')
    <header
        class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50 px-4 py-3 flex items-center justify-between shadow-xs transition-colors">
        <img src="images/logo-2.png" alt="Kisa" width="100" class="dark:brightness-110">
        <div class="flex items-center gap-2">
            <button onclick="toggleTheme()" type="button" aria-label="Toggle Mode Gelap" class="p-1.5 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-moon hidden dark:block text-slate-200"></i>
                <i class="fa-solid fa-sun block dark:hidden text-amber-500"></i>
            </button>
            <button class="text-slate-600 dark:text-slate-300 p-1 hover:text-[#38CAC8] transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d=" m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                </svg>
            </button>
        </div>
    </header>

    <section class="p-4">
        <div
            class="bg-gradient-to-r from-[#38CAC8] to-[#248c8b] rounded-2xl p-6 text-white shadow-xs relative overflow-hidden">
            <div class="max-w-[65%] relative z-10">
                <span
                    class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider mb-2 inline-block">Spesial
                    Hari Ini</span>
                <h2 class="text-lg font-bold font-serif leading-tight mb-2">Matahari di Balik Hujan Agustus</h2>
                <p class="text-xs text-[#dcdede] line-clamp-2 mb-4">Kisah romansa remaja yang terjebak di antara masa lalu
                    dan impian besar.</p>
                <a href="#"
                    class="bg-white text-[#38CAC8] text-xs font-bold px-3 py-2 rounded-lg inline-block shadow-xs">Baca
                    Sekarang</a>
            </div>
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
        </div>
    </section>

    <section class="py-2">
        <div class="px-4 mb-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Genre Populer</h3>
        </div>
        <div class="flex gap-3 overflow-x-auto px-4 pb-2 no-scrollbar">
            <a href="#"
                class="bg-[#dcfaf9] dark:bg-[#144d4c] text-[#38CAC8] dark:text-[#4cd8d6] text-xs font-semibold px-4 py-2 rounded-full whitespace-nowrap border border-white dark:border-slate-800">🔥
                All Genre</a>
            <a href="#"
                class="bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 text-xs font-medium px-4 py-2 rounded-full whitespace-nowrap border border-slate-200 dark:border-slate-800">💖
                Romance</a>
            <a href="#"
                class="bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 text-xs font-medium px-4 py-2 rounded-full whitespace-nowrap border border-slate-200 dark:border-slate-800">⚔️
                Fantasy</a>
        </div>
    </section>

    <main class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Rekomendasi Untukmu</h3>
            <a href="#" class="text-xs font-semibold text-[#38CAC8]">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden flex flex-col h-full shadow-2xs transition-colors">
                <div class="bg-slate-300 dark:bg-slate-800 aspect-[3/4] flex items-center justify-center relative">

                    <img src="" alt="" class="w-full h-full object-cover">

                    <span class="text-xs text-slate-600 dark:text-slate-400 font-serif">No Cover</span>

                    <span
                        class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">4.0
                        ★</span>
                </div>
                <div class="p-3 flex flex-col flex-1">
                    <h4 class="font-bold text-xs text-slate-900 dark:text-slate-100 line-clamp-2 leading-snug mb-1">Test1</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-auto">Anonim</p>
                </div>
            </div>
        </div>
    </main>
@endsection

