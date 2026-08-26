<div class="mb-20">
    <section class="flex items-center justify-between w-full py-4 border-b border-slate-50">
        <div class="flex flex-col">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selamat Datang di Kisa</span>
            <h1 class="text-lg font-black text-slate-800">
                Hai, {{ $user ? $user->name : 'Pembaca Budiman' }}! 👋
            </h1>
        </div>

        {{-- @if ($user)
            <a href="{{ url('/profile') }}" wire:navigate
                class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center flex-shrink-0 shadow-2xs hover:scale-105 transition-transform">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-full h-full object-cover">
                @else
                    <div
                        class="w-full h-full bg-brand-100 text-brand-700 flex items-center justify-center font-black text-sm uppercase">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                @endif
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate
                class="text-xs font-bold bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-brand-600 transition">
                Login
            </a>
        @endif --}}
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

    <section class="py-4 w-full">
        <div class="relative py-3">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari judul cerita atau nama penulis..."
                class="w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition shadow-2xs">

            @if (!empty($search))
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    ✕
                </button>
            @endif
        </div>

        @if ($featuredStory)
            @php
                $featuredFirstChapter = $featuredStory->chapters()->orderBy('order_number', 'asc')->first();
            @endphp

            <div
                class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-5 md:p-6 shadow-xl border border-slate-800 group">
                {{-- Background Ornament Blur Accent --}}
                <div
                    class="absolute -top-12 -right-12 w-40 h-40 bg-amber-500/20 rounded-full blur-2xl pointer-events-none">
                </div>
                <div
                    class="absolute -bottom-12 -left-12 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none">
                </div>

                @if ($featuredStory)
                    <div class="relative z-10 flex flex-col sm:flex-row gap-4 sm:gap-5 items-start sm:items-center">

                        {{-- Cover Cerita Hero --}}
                        <div
                            class="w-24 h-36 sm:w-28 sm:h-40 bg-slate-800 rounded-2xl overflow-hidden shrink-0 relative shadow-md self-center sm:self-auto">
                            @if ($featuredStory->cover_path)
                                <img src="{{ asset('storage/' . $featuredStory->cover_path) }}"
                                    alt="{{ $featuredStory->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-3xl bg-amber-500/10 text-amber-400 font-black">
                                    🔥
                                </div>
                            @endif

                            {{-- Tag Sorotan --}}
                            <span
                                class="absolute top-2 left-2 bg-rose-500 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider shadow-xs">
                                Rekomendasi
                            </span>
                        </div>

                        {{-- Detail Cerita Hero --}}
                        <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                            <div>
                                {{-- Badges --}}
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span
                                        class="text-[8px] font-black tracking-wider uppercase text-amber-400 bg-amber-400/10 px-2.5 py-0.5 rounded-lg border border-amber-400/20">
                                        {{ $featuredStory->category->name ?? 'Unggulan' }}
                                    </span>

                                    @if ($featuredStory->type === 'chat')
                                        <span
                                            class="text-[8px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-lg">
                                            💬 Chat Fic
                                        </span>
                                    @endif

                                    @if ($featuredStory->monetization_type === 'premium')
                                        <span
                                            class="text-[8px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-lg">
                                            🫘 Premium
                                        </span>
                                    @endif
                                </div>

                                {{-- Judul Cerita --}}
                                <h2
                                    class="text-[15px] text-base sm:text-lg font-black text-white leading-snug truncate group-hover:text-amber-400 transition">
                                    {{ $featuredStory->title }}
                                </h2>

                                {{-- Sinopsis Singkat --}}
                                <p class="text-xs text-slate-300 font-normal line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $featuredStory->synopsis }}
                                </p>
                            </div>

                            {{-- Footer Hero (Penulis & Tombol Baca) --}}
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                                <span class="text-xs text-slate-400 font-medium truncate">
                                    Oleh <span
                                        class="text-slate-200 font-bold">{{ $featuredStory->author->name ?? 'Penulis' }}</span>
                                </span>

                                @if ($featuredFirstChapter)
                                    <a href="{{ route('stories.chapter.read', [$featuredStory->slug, $featuredFirstChapter->slug]) }}"
                                        wire:navigate
                                        class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-1.5">
                                        <span>Baca</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        @endif
    </section>

    {{-- ================= SEKSYEN 3: KATEGORI & URUTAN LIST REAL-DATA ================= --}}
    <div>
        {{-- Kategori Pill Buttons --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-3 -mx-4 px-4 scrollbar-none mb-6">
            <button wire:click="$set('selectedCategory', '')"
                class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                Semua Genre
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('selectedCategory', '{{ $cat->id }}')"
                    class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedCategory == $cat->id ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    {{ $cat->name }}
                </button>
            @endforeach

        </div>

        <div
            class="flex items-center justify-between gap-2 bg-white p-2.5 rounded-2xl border border-slate-100 text-xs mb-3">

            {{-- Filter Gratis / Premium --}}
            <div class="flex items-center gap-1">
                <button wire:click="$set('selectedMonetization', '')"
                    class="px-2.5 py-1.5 rounded-lg font-bold transition {{ empty($selectedMonetization) ? 'bg-slate-100 text-slate-900' : 'text-slate-400 hover:text-slate-700' }}">
                    Semua
                </button>
                <button wire:click="$set('selectedMonetization', 'free')"
                    class="px-2.5 py-1.5 rounded-lg font-bold transition {{ $selectedMonetization === 'free' ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-slate-400 hover:text-slate-700' }}">
                    Gratis
                </button>
                <button wire:click="$set('selectedMonetization', 'premium')"
                    class="px-2.5 py-1.5 rounded-lg font-bold transition {{ $selectedMonetization === 'premium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-400 hover:text-slate-700' }}">
                    Premium
                </button>
            </div>

            {{-- Sorting Options --}}
            <select wire:model.live="sortBy"
                class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-700 focus:outline-hidden">
                <option value="latest">Terbaru</option>
                <option value="popular">Terpopuler</option>
                <option value="title">Judul (A-Z)</option>
            </select>
        </div>

        <section class="w-full flex flex-col gap-4">
            <div class="flex flex-col gap-4">
                {{-- DITAMPILKAN BERDASARKAN HASIL QUERY BERLAPIS --}}
                @forelse ($stories as $story)
                    <div class="p-2 flex gap-4 transition">
                        {{-- Cover Image Dinamis --}}
                        <div class="rounded-lg w-20 h-35 bg-slate-100 overflow-hidden shrink-0 relative shadow-2xs">
                            @if ($story->cover_path)
                                <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-2xl bg-amber-50 text-amber-500 font-black">
                                    📚
                                </div>
                            @endif

                            {{-- Badge Monetisasi --}}
                            @if ($story->monetization_type === 'premium')
                                <span
                                    class="absolute top-1.5 left-1.5 bg-amber-500/90 backdrop-blur-xs text-white text-[9px] font-black px-1.5 py-0.5 rounded-md">
                                    🫘
                                </span>
                            @endif
                        </div>

                        {{-- Metadata & Detail Deskripsi --}}
                        <div class="flex flex-col justify-between py-0.5 flex-1">
                            <div class="flex flex-col gap-0.5 ">
                                @auth
                                    @php
                                        $isSaved = auth()->user()->savedStories->contains($story->id);
                                    @endphp
                                    <div class="text-right">
                                        <button wire:click="toggleLibrary({{ $story->id }})"
                                            class="p-1.5 rounded-xl transition text-slate-400 hover:bg-slate-50 active:scale-90"
                                            title="{{ $isSaved ? 'Hapus dari Pustaka' : 'Simpan ke Pustaka' }}">
                                            @if ($isSaved)
                                                <svg class="w-5 h-5 text-amber-500 fill-amber-500" viewBox="0 0 24 24">
                                                    <path d="M5 5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-slate-400 hover:text-amber-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                @endauth
                                {{-- Judul Cerita Linkable --}}
                                <h3 class="text-sm font-bold text-slate-800 mt-1 line-clamp-1">
                                    <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                        class="hover:text-brand-600 transition">
                                        {{ $story->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    <span
                                        class="text-[11px] text-slate-400 font-medium">{{ number_format_short($story->views_count) }}
                                    </span>

                                    <span class="text-[11px] text-slate-400 font-medium">.</span>

                                    <span class="text-[11px] text-slate-400 font-medium">
                                        <a
                                            href={{ route('pen-name.show', [$story->penName->slug]) }}>{{ $story->penName->name ?? 'Penulis Kisa' }}</a>
                                    </span>
                                </div>


                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $story->synopsis ?? 'Tidak ada deskripsi singkat untuk kisah ini, Bro.' }}
                                </p>

                                <div>
                                    <div class="flex items-center gap-3 mt-2 text-[11px] font-bold text-slate-500">
                                        <div class="flex items-center gap-0.5 text-amber-500">
                                            <div
                                                class="flex items-center gap-1 mt-2 bg-white/80 p-1 px-2.5 rounded-full border border-slate-100 shadow-3xs">
                                                <span class="text-amber-400 text-xs">★</span>
                                                <span
                                                    class="text-xs font-black text-slate-700">{{ $story->average_rating }}</span>
                                                <span class="text-[10px] text-slate-300 font-bold">•</span>
                                                <span
                                                    class="text-[10px] text-slate-400 font-bold">({{ $story->total_reviews }}
                                                    Ulasan)</span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center gap-3 mt-2 bg-white/80 p-1 px-2.5 rounded-full border border-slate-100 shadow-3xs">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                            {{-- MENGHITUNG DENGAN AKURAT HANYA BAB YANG STATUSNYA PUBLISHED --}}
                                            <span>{{ $story->chapters->where('status', 'published')->count() }}
                                                Bab</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 p-6">
                        <div class="text-4xl mb-3">🔍</div>
                        <h3 class="text-sm font-black text-slate-800">Cerita Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain atau bersihkan filter pencarianmu.
                        </p>
                        <button wire:click="resetFilters"
                            class="mt-4 px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                            Reset Semua Filter
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $stories->links() }}
            </div>
        </section>
    </div>
</div>
