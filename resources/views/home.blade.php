@extends('layouts.app')

@section('title', 'Kisa - Beranda')

@section('content')
    <header
        class="bg-white border-b border-slate-200 sticky top-0 z-50 px-4 py-3 flex items-center justify-between shadow-xs">
        <img src="images/logo-2.png" alt="" width="100">
        <button class="text-slate-600 p-1 hover:text-emerald-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d=" m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
            </svg>
        </button>
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
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Genre Populer</h3>
        </div>
        <div class="flex gap-3 overflow-x-auto px-4 pb-2 no-scrollbar">
            <a href="#"
                class="bg-[#dcfaf9] text-[#38CAC8] text-xs font-semibold px-4 py-2 rounded-full whitespace-nowrap border border-white">🔥
                All Genre</a>
            <a href="#"
                class="bg-white text-slate-600 text-xs font-medium px-4 py-2 rounded-full whitespace-nowrap border border-slate-200">💖
                Romance</a>
            <a href="#"
                class="bg-white text-slate-600 text-xs font-medium px-4 py-2 rounded-full whitespace-nowrap border border-slate-200">⚔️
                Fantasy</a>
        </div>
    </section>

    <main class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Rekomendasi Untukmu</h3>
            <a href="#" class="text-xs font-semibold text-[#38CAC8]">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden flex flex-col h-full shadow-2xs">
                <div class="bg-slate-300 aspect-[3/4] flex items-center justify-center relative">

                    <img src="" alt="" class="w-full h-full object-cover">

                    <span class="text-xs text-slate-600 font-serif">No Cover</span>

                    <span
                        class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">4.0
                        ★</span>
                </div>
                <div class="p-3 flex flex-col flex-1">
                    <h4 class="font-bold text-xs text-slate-900 line-clamp-2 leading-snug mb-1">Test1</h4>
                    <p class="text-[11px] text-slate-500 mt-auto">Anonim</p>
                </div>
            </div>
        </div>
    </main>
@endsection
