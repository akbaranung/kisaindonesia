<div class="w-full min-h-screen bg-slate-100 max-w-2xl mx-auto border-x border-slate-100 pb-28">
    <header
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 py-3.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" wire:navigate
                class="p-1.5 text-slate-500 hover:text-slate-800 transition rounded-xl hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-black text-base text-slate-900 tracking-tight">Perpustakaan Saya</h1>
        </div>

        <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100/80 px-3 py-1 rounded-full">
            <span class="text-xs">🫘</span>
            <span class="text-xs font-black text-amber-700">{{ auth()->user()->kisa_bean_balance ?? 0 }}</span>
        </div>
    </header>

    <main class="p-4 md:p-6 space-y-6">

        {{-- Alert Sukses --}}
        @if (session()->has('success_library'))
            <div
                class="p-3.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-between animate-fade-in">
                <span>✅ {{ session('success_library') }}</span>
                <button wire:click="$refresh" class="text-emerald-500 hover:text-emerald-800">✕</button>
            </div>
        @endif

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari dalam perpustakaanmu..."
                class="w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-[#38CAC8] focus:ring-1 focus:ring-[#38CAC8] transition shadow-2xs">

            @if (!empty($search))
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    ✕
                </button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($savedStories as $story)
                @php
                    $firstChapter = $story->chapters()->orderBy('order_number', 'asc')->first();
                @endphp

                <div
                    class="bg-white border border-slate-100 rounded-3xl p-4 flex gap-4 hover:border-[#38CAC8] hover:shadow-md transition group relative">

                    {{-- Cover Cerita --}}
                    <div class="w-20 h-28 bg-slate-100 rounded-2xl overflow-hidden shrink-0 relative shadow-2xs">
                        @if ($story->cover_path)
                            <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-2xl bg-amber-50 text-amber-500 font-black">
                                📚
                            </div>
                        @endif

                        @if ($story->monetization_type === 'premium')
                            <span
                                class="absolute top-1.5 left-1.5 bg-amber-500/90 backdrop-blur-xs text-white text-[9px] font-black px-1.5 py-0.5 rounded-md">
                                🫘
                            </span>
                        @endif
                    </div>

                    {{-- Informasi Cerita --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                        <div>
                            <div class="flex flex-col justify-between">
                                <div class="text-right">
                                    {{-- Tombol Hapus dari Library --}}
                                    <button wire:click="removeFromLibrary({{ $story->id }})"
                                        wire:confirm="Yakin ingin menghapus cerita ini dari perpustakaanmu?"
                                        class="p-1.5 rounded-xl transition text-amber-500 hover:bg-rose-50 hover:text-rose-600 active:scale-90"
                                        title="Hapus dari Pustaka">
                                        <svg class="w-5 h-5 fill-amber-500 hover:fill-rose-600" viewBox="0 0 24 24">
                                            <path d="M5 5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h3
                                class="text-sm font-black text-slate-900 truncate group-hover:text-[#38CAC8] transition">
                                {{ $story->title }}
                            </h3>

                            <p class="text-xs text-slate-400 font-medium line-clamp-2 mt-1 leading-relaxed">
                                {{ $story->synopsis }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50">
                            <span class="text-[11px] font-bold text-slate-500 truncate">
                                Dibuat oleh: {{ $story->author->name ?? 'Penulis' }}
                            </span>

                            @if ($firstChapter)
                                <a href="{{ route('stories.chapter.read', [$story->slug, $firstChapter->slug]) }}"
                                    wire:navigate
                                    class="px-3.5 flex py-1.5 bg-[#38CAC8] hover:bg-[#60D5D2] text-white font-bold text-xs rounded-xl shadow-2xs transition transform active:scale-95">
                                    Baca
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                {{-- Empty State jika Library Kosong --}}
                <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 p-8 shadow-2xs">
                    <div
                        class="w-16 h-16 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 border border-amber-100">
                        🔖
                    </div>
                    <h3 class="text-base font-black text-slate-800">Perpustakaanmu Masih Kosong</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto leading-relaxed">
                        Kamu belum menyimpan cerita apapun. Jelajahi cerita menarik dan klik ikon bookmark untuk
                        menyimpannya di sini.
                    </p>
                    <a href="{{ route('home') }}" wire:navigate
                        class="inline-block mt-5 px-5 py-2.5 bg-[#38CAC8] hover:bg-[#60D5D2] text-white font-bold text-xs rounded-2xl shadow-md transition transform active:scale-95">
                        Cari Cerita Sekarang
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $savedStories->links() }}
        </div>

    </main>

</div>
