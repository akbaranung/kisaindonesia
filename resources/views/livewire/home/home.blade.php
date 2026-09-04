<div class="mb-20">
    <section class="flex items-center justify-between w-full py-4 border-b border-slate-50">
        <div class="flex flex-col">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selamat Datang di Kisa</span>
            <h1 class="text-lg font-black text-slate-800">
                Hai, {{ $user ? $user->name : 'Pembaca Budiman' }}! 👋
            </h1>
        </div>

        @if (!$user)
            <a href="{{ route('login') }}" wire:navigate
                class="text-xs font-bold bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-brand-600 transition">
                Login
            </a>
        @endif
        @auth
            <livewire:notification-bell />
        @endauth
    </section>

    <section class="py-2 w-full">
        <form action="{{ route('stories.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative w-full">
                <input type="text" name="search" placeholder="Cari judul atau penulis..."
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500"
                    value="{{ request('search') }}" />
            </div>

            <button type="submit"
                class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-xl transition">
                Cari
            </button>
        </form>

    </section>

    <section>
        <livewire:home.hero-carousel />
    </section>

    {{-- ================= SEKSYEN 3: KATEGORI & URUTAN LIST REAL-DATA ================= --}}
    <div>
        {{-- SECTION: TERAKHIR DIBACA (CONTINUE READING) --}}
        @if (auth()->check() && count($continueReading) > 0)
            <section class="py-4 px-3 w-full bg-brand-50/50 rounded-2xl border border-brand-100 my-4">
                {{-- Header Section --}}
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2
                            class="text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            Lanjutkan Membaca
                        </h2>
                        <p class="text-[11px] text-slate-500">Kisah yang terakhir kamu buka</p>
                    </div>
                </div>

                {{-- Slider / Horizontal Grid Container --}}
                <div
                    class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-none snap-x snap-mandatory scroll-smooth">
                    @foreach ($continueReading as $history)
                        @php
                            $chapter = $history->chapter;
                            $story = $chapter?->story;
                        @endphp

                        @if ($chapter && $story)
                            <div
                                class="w-72 flex-shrink-0 snap-start bg-white rounded-xl p-2.5 border border-slate-100 shadow-2xs flex gap-3 items-center relative group hover:border-brand-200 transition">
                                {{-- Cover --}}
                                <a href="{{ route('stories.chapter.read', ['story' => $story->slug, 'chapter' => $chapter->slug]) }}"
                                    wire:navigate class="flex-shrink-0">
                                    <div class="w-12 h-16 bg-slate-100 rounded-lg overflow-hidden relative">
                                        @if ($story->cover_path)
                                            <img src="{{ asset('storage/' . $story->cover_path) }}"
                                                alt="{{ $story->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-lg bg-amber-50 text-amber-500 font-black">
                                                📚
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Detail Info --}}
                                <div class="flex flex-col flex-1 min-w-0 justify-between h-16">
                                    <div>
                                        <h3
                                            class="text-xs font-bold text-slate-800 truncate leading-snug group-hover:text-brand-600 transition">
                                            <a href="{{ route('stories.chapter.read', ['story' => $story->slug, 'chapter' => $chapter->slug]) }}"
                                                wire:navigate>
                                                {{ $story->title }}
                                            </a>
                                        </h3>

                                        <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                                            Bab {{ $chapter->order_number ?? $chapter->chapter_number }}:
                                            {{ $chapter->title }}
                                        </p>
                                    </div>

                                    {{-- Action Button & Time --}}
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-[9px] text-slate-400 font-medium">
                                            {{ $history->updated_at->diffForHumans() }}
                                        </span>

                                        <a href="{{ route('stories.chapter.read', ['story' => $story->slug, 'chapter' => $chapter->slug]) }}"
                                            wire:navigate
                                            class="text-[10px] font-extrabold bg-brand-600 hover:bg-brand-700 text-white px-2.5 py-1 rounded-lg transition shadow-2xs">
                                            Lanjut
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
        <section class="py-4 px-3 w-full">
            {{-- Header Section --}}
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                        Cerita Terbaru
                    </h2>
                    <p class="text-[11px] text-slate-400">Geser untuk melihat kisah favorit pilihan pembaca</p>
                </div>
                <a href="{{ route('stories.index') }}"
                    class="text-xs font-bold text-brand-600 hover:text-brand-700 transition">
                    Lihat Semua
                </a>
            </div>


            <div
                class="flex items-center gap-4 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-none snap-x snap-mandatory scroll-smooth">
                @foreach ($stories as $story)
                    <div
                        class="w-36 flex-shrink-0 snap-start bg-white rounded-2xl border border-slate-100 p-2 shadow-2xs hover:shadow-md transition">
                        {{-- Cover Image --}}
                        <div class="w-full h-48 bg-slate-100 rounded-xl overflow-hidden relative mb-2">
                            <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                @if ($story->cover_path)
                                    <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-3xl bg-amber-50 text-amber-500 font-black">
                                        📚
                                    </div>
                                @endif
                            </a>

                            {{-- Badge Monetisasi --}}
                            @if ($story->monetization_type === 'premium')
                                <span
                                    class="absolute top-2 left-2 bg-amber-500/90 backdrop-blur-xs text-white text-[9px] font-black px-1.5 py-0.5 rounded-md">
                                    🫘 Premium
                                </span>
                            @endif
                        </div>

                        {{-- Detail Cerita --}}
                        <div class="flex flex-col gap-1">
                            <h3 class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                    class="hover:text-brand-600 transition">
                                    {{ $story->title }}
                                </a>
                            </h3>

                            <span class="text-[10px] text-slate-400 font-medium line-clamp-1">
                                {{ $story->penName?->name ?? 'Penulis Kisa' }}
                            </span>

                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 mt-1">
                                <div class="flex items-center gap-0.5 text-amber-500">
                                    <span>★</span>
                                    <span class="text-slate-700">{{ $story->average_rating ?? '0.0' }}</span>
                                </div>
                                <span class="text-slate-400">{{ number_format_short($story->views_count ?? 0) }}
                                    views</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="py-4 px-3 w-full border-t border-slate-50">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                        🔥 Cerita Populer
                    </h2>
                    <p class="text-[11px] text-slate-400">Kisah favorit yang paling banyak dibaca</p>
                </div>
                <a href="{{ route('stories.index', ['sortBy' => 'popular']) }}" wire:navigate
                    class="text-xs font-bold text-brand-600 hover:text-brand-700 transition">
                    Lihat Semua
                </a>
            </div>

            <div
                class="flex items-center gap-4 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-none snap-x snap-mandatory scroll-smooth">
                @foreach ($popularStories ?? $stories as $story)
                    <div
                        class="w-36 flex-shrink-0 snap-start bg-white rounded-2xl border border-slate-100 p-2 shadow-2xs hover:shadow-md transition">
                        <div class="w-full h-48 bg-slate-100 rounded-xl overflow-hidden relative mb-2">
                            <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                @if ($story->cover_path)
                                    <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-3xl bg-amber-50 text-amber-500 font-black">
                                        📚
                                    </div>
                                @endif
                            </a>

                            @if ($story->monetization_type === 'premium')
                                <span
                                    class="absolute top-2 left-2 bg-amber-500/90 backdrop-blur-xs text-white text-[9px] font-black px-1.5 py-0.5 rounded-md">
                                    🫘 Premium
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col gap-1">
                            <h3 class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                    class="hover:text-brand-600 transition">
                                    {{ $story->title }}
                                </a>
                            </h3>

                            <span class="text-[10px] text-slate-400 font-medium line-clamp-1">
                                {{ $story->penName?->name ?? 'Penulis Kisa' }}
                            </span>

                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 mt-1">
                                <div class="flex items-center gap-0.5 text-amber-500">
                                    <span>★</span>
                                    <span class="text-slate-700">{{ $story->average_rating ?? '0.0' }}</span>
                                </div>
                                <span class="text-slate-400">{{ number_format_short($story->views_count ?? 0) }}
                                    views</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="py-4 px-3 w-full border-t border-slate-50">
            {{-- Header Section --}}
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        Bab Terbaru
                    </h2>
                    <p class="text-[11px] text-slate-400">Update cerita harian favoritmu yang baru rilis</p>
                </div>
                <a href="{{ route('stories.index', ['sortBy' => 'latest']) }}" wire:navigate
                    class="text-xs font-bold text-brand-600 hover:text-brand-700 transition">
                    Lihat Semua
                </a>
            </div>

            {{-- List Container --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                @forelse ($recentChapters as $chapter)
                    <div
                        class="flex items-center justify-between p-2.5 bg-white rounded-2xl border border-slate-100 shadow-2xs hover:border-slate-200 transition group">
                        {{-- Left Side: Cover & Info --}}
                        <div class="flex items-center gap-3 min-w-0">
                            <a href="{{ route('stories.read', $chapter->story->slug) }}" wire:navigate
                                class="flex-shrink-0">
                                <div class="w-12 h-16 bg-slate-100 rounded-xl overflow-hidden relative">
                                    @if ($chapter->story->cover_path)
                                        <img src="{{ asset('storage/' . $chapter->story->cover_path) }}"
                                            alt="{{ $chapter->story->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-lg bg-amber-50 text-amber-500 font-black">
                                            📚
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="flex flex-col min-w-0 pr-2">
                                {{-- Judul Cerita --}}
                                <h3
                                    class="text-xs font-bold text-slate-800 truncate leading-snug group-hover:text-brand-600 transition">
                                    <a href="{{ route('stories.read', $chapter->story->slug) }}" wire:navigate>
                                        {{ $chapter->story->title }}
                                    </a>
                                </h3>

                                {{-- Nama Bab / Chapter --}}
                                <a href="{{ route('stories.chapter.read', [$chapter->story->slug, $chapter->slug]) }}"
                                    wire:navigate
                                    class="text-[11px] font-medium text-slate-600 hover:underline truncate mt-0.5">
                                    Bab {{ $chapter->order_number ?? $chapter->chapter_number }}:
                                    {{ $chapter->title }}
                                </a>

                                {{-- Nama Penulis & Waktu Update --}}
                                <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-1">
                                    <span class="truncate max-w-[110px] font-medium">
                                        {{ $chapter->story->penName?->name ?? 'Penulis Kisa' }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $chapter->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Badge Monetisasi / Action --}}
                        <div class="flex-shrink-0 pl-1">
                            @if ($chapter->is_premium)
                                <span
                                    class="inline-flex items-center gap-1 bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-black px-2 py-1 rounded-lg">
                                    🫘 Premium
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-lg">
                                    Gratis
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-6 text-center bg-white rounded-2xl border border-slate-100">
                        <p class="text-xs text-slate-400 font-medium">Belum ada pembaruan bab baru hari ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
