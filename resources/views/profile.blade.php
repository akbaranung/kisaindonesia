@extends('layouts.app')

@section('title', 'Profil Saya - Kisa')

@section('content')
    <header
        class="bg-white border-b border-slate-200 sticky top-0 z-50 px-4 py-4 flex items-center justify-between shadow-xs">
        <h1 class="text-base font-bold tracking-tight text-slate-800">Profil Akun</h1>
    </header>

    <section class="bg-white border-b border-slate-200 p-6 flex items-center gap-4">
        <div
            class="w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-black uppercase">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-slate-900 truncate leading-tight mb-0.5">{{ Auth::user()->name }}</h2>
            <p class="text-xs text-slate-500 truncate mb-2">{{ Auth::user()->email }}</p>
            <span
                class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-100 uppercase">Pembaca
                Setia</span>
        </div>
    </section>

    <section class="p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 grid grid-cols-3 gap-2 text-center shadow-2xs">
            <div class="border-r border-slate-100">
                <span class="block text-base font-bold text-slate-900 font-serif">12</span>
                <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Rak Buku</span>
            </div>
            <div class="border-r border-slate-100">
                <span class="block text-base font-bold text-slate-900 font-serif">4</span>
                <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Mengikuti</span>
            </div>
            <div>
                <span class="block text-base font-bold text-slate-900 font-serif">0</span>
                <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Karya</span>
            </div>
        </div>
    </section>

    <main class="px-4 py-2 flex flex-col gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs flex flex-col">
            <a href="#"
                class="flex items-center justify-between p-4 hover:bg-slate-50 border-b border-slate-100 transition">
                <div class="flex items-center gap-3 text-sm font-medium text-slate-700">👤 <span>Ubah Informasi
                        Profil</span></div>
                <span class="text-slate-400 text-xs">&rarr;</span>
            </a>
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center gap-3 text-sm font-medium text-slate-700">🌙 <span>Mode Gelap</span></div>
                <span class="text-xs text-slate-400 font-medium">Segera Hadir</span>
            </div>
        </div>

        <div class="mt-4">
            <form action="{{ route('logout') }}" method="POST"
                onsubmit="return confirm('Apakah kamu yakin ingin keluar?');">
                @csrf
                <button type="submit"
                    class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-sm py-3.5 rounded-2xl transition">
                    Keluar dari Akun (Logout)
                </button>
            </form>
        </div>
    </main>
@endsection
