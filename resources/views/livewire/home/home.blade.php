<div class="mb-20">
    <section
        class="sticky flex items-center justify-between p-2 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 top-0 left-0 right-0 z-50 transition-colors">
        <div class="flex flex-col">
            <img src="images/logo-2.png" alt="logo" class="w-20 dark:brightness-110">
        </div>

        <div class="flex items-center gap-2">
            <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark Mode"
                class="p-1.5 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-moon hidden dark:block text-amber-300"></i>
                <i class="fa-solid fa-sun block dark:hidden text-amber-500"></i>
            </button>

            @if (!$user)
                <a href="{{ route('login') }}" wire:navigate
                    class="text-xs font-bold bg-slate-900 dark:bg-brand-600 text-white px-4 py-2 rounded-xl hover:bg-brand-600 transition">
                    Login
                </a>
            @endif
            @auth
                <livewire:notification-bell />
            @endauth
        </div>
    </section>

    <section class="p-2 w-full">
        <form action="{{ route('stories.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative w-full">
                <input type="text" name="search" placeholder="Cari judul atau penulis..."
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500"
                    value="{{ request('search') }}" />
            </div>

            <button type="submit"
                class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-xl transition">
                Cari
            </button>
        </form>

    </section>

    <section class="p-2">
        <livewire:home.hero-carousel />
    </section>

    {{-- ================= SEKSYEN 3: KATEGORI & URUTAN LIST REAL-DATA ================= --}}
    <div class="p-2">
        @if ($editorChoices->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                            Pilihan Editor
                        </h2>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Geser untuk melihat kisah favorit
                            pilihan
                            pembaca</p>
                    </div>

                    <a href="{{ route('stories.index') }}" wire:navigate
                        class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1 shrink-0">
                        Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Grid Cerita Pilihan Editor -->
                <div
                    class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 pb-2 space-y-3 justify-items-center">
                    @foreach ($editorChoices as $story)
                        <div class="w-28 flex-shrink-0 snap-start transition">
                            {{-- Cover Image --}}
                            <div
                                class="w-full h-48 bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden relative mb-2">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                    @if ($story->cover_path)
                                        <img src="{{ asset('storage/' . $story->cover_path) }}"
                                            alt="{{ $story->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-3xl bg-amber-50 dark:bg-slate-800 text-amber-500 font-black">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="flex items-center space-x-1.5 p-2">
                                        <span
                                            class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-slate-900 dark:text-slate-100 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                            <i class="fa-regular fa-eye"></i>
                                            {{ number_format_short($story->views_count ?? 0) }}
                                        </span>
                                        <div class="absolute right-2">
                                            @if ($story->monetization_type === 'premium')
                                                <span
                                                    class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-amber-500 text-white border border-amber-500 me-1">
                                                    <i class="fa-solid fa-crown"></i>
                                                </span>
                                            @endif
                                            @if ($story->hasTypeChat())
                                                <span
                                                    class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                    <i class="fa-solid fa-comment-dots"></i>
                                                </span>
                                            @elseif(!$story->hasTypeChat() && $story->type === 'novel')
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </span>
                                            @elseif($story->type === 'puisi')
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                    <i class="fa-solid fa-feather-pointed"></i>
                                                </span>
                                            @elseif($story->type === 'non_fiksi')
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="absolute bottom-0 flex items-center space-x-1.5 p-2">
                                        <div
                                            class="flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400 mt-1">
                                            <div class="flex items-center gap-0.5 text-amber-500">
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span
                                                    class="text-slate-700 dark:text-slate-200">{{ $story->average_rating ?? '0.0' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Cerita --}}
                            <div class="flex flex-col">
                                <h3
                                    class="text-xs font-bold text-slate-800 dark:text-slate-100 line-clamp-2 leading-snug">
                                    <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                        class="hover:text-brand-600 dark:hover:text-brand-400 transition">
                                        {{ $story->title }}
                                    </a>
                                </h3>

                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium line-clamp-1">
                                    {{ $story->genre->name ?? 'Uncategorized' }}
                                </span>

                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium line-clamp-1">
                                    <a href="{{ route('pen-name.show', [$story->penName->id]) }}"
                                        class="hover:underline">{{ '@' . $story->penName?->name ?? 'Penulis Kisa' }}</a>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($recommendedStories->isNotEmpty())
            @php
                /*
                 * BENTO ACAK (STABIL) - COVER ONLY
                 * Ukuran: large (2x2), tall (1x2), wide (2x1), small (1x1).
                 * Pola dipilih berdasarkan ID cerita, jadi tidak berubah saat Livewire re-render.
                 */
                $sizeClasses = [
                    'large' => 'col-span-2 row-span-2',
                    'tall' => 'row-span-2',
                    'wide' => 'col-span-2',
                    'small' => '',
                ];

                $variants = [
                    ['large', 'small', 'small', 'tall'],
                    ['tall', 'wide', 'small', 'small'],
                    ['small', 'small', 'large', 'tall'],
                    ['wide', 'tall', 'small', 'small'],
                    ['large', 'wide', 'small', 'small'],
                    ['tall', 'small', 'small', 'large'],
                ];

                // Blok terakhir yang isinya kurang dari 4
                $fallbacks = [
                    1 => ['tall'],
                    2 => ['tall', 'tall'],
                    3 => ['tall', 'small', 'small'],
                ];

                $blocks = [];
                $prevPick = -1;

                foreach ($recommendedStories->values()->chunk(4) as $chunk) {
                    if ($chunk->count() === 4) {
                        $pick = crc32((string) $chunk->first()->id) % count($variants);
                        if ($pick === $prevPick) {
                            $pick = ($pick + 1) % count($variants);
                        }
                        $prevPick = $pick;
                        $sizes = $variants[$pick];
                    } else {
                        $sizes = $fallbacks[$chunk->count()];
                    }

                    $blocks[] = ['stories' => $chunk->values(), 'sizes' => $sizes];
                }
            @endphp

            <section class="space-y-3 my-6">
                <div class="flex items-center justify-between mb-3 px-1">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                            Rekomendasi Untuk Anda
                        </h2>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">
                            Geser untuk melihat kisah rekomendasi pilihan untuk anda
                        </p>
                    </div>

                    <a href="{{ route('stories.index') }}" wire:navigate
                        class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1 shrink-0">
                        Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="flex items-start gap-3 overflow-x-auto pb-4 scrollbar-none snap-x snap-mandatory">
                    @foreach ($blocks as $block)
                        <div
                            class="grid grid-rows-2 grid-flow-col-dense auto-cols-[7.5rem] gap-3 h-[21rem] shrink-0 snap-start">
                            @foreach ($block['stories'] as $i => $story)
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                    aria-label="{{ $story->title }}" title="{{ $story->title }}"
                                    class="{{ $sizeClasses[$block['sizes'][$i]] }} group relative overflow-hidden rounded-2xl bg-slate-800 shadow-md ring-1 ring-slate-800/60 transition focus-visible:outline-2 focus-visible:outline-brand-500">
                                    @if ($story->cover_path)
                                        <img src="{{ asset('storage/' . $story->cover_path) }}"
                                            alt="{{ $story->title }}" loading="lazy"
                                            class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                    @else
                                        <div
                                            class="absolute inset-0 flex items-center justify-center text-3xl text-amber-500">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <livewire:ranking-tab />
        <div class="mt-6 text-center">
            <a href="{{ route('stories.index') }}" wire:navigate
                class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-4 bg-slate-900 dark:bg-brand-600 hover:bg-brand-600 dark:hover:bg-brand-500 text-white font-bold text-xs rounded-2xl shadow-md transition transform active:scale-95">
                <span>Lihat Semua Cerita</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>

        <section class="space-y-3 my-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2
                        class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-sparkles text-amber-500"></i> Cerita Terbaru
                    </h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">10 Rilisan karya cerita terbaru di Kisa
                    </p>
                </div>
                <a href="{{ route('stories.index') }}" wire:navigate
                    class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1 shrink-0">
                    Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            {{-- Horizontal Scroll Slider Container --}}
            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none snap-x snap-mandatory">
                @foreach ($latestStories as $story)
                    <div class="w-28 flex-shrink-0 snap-start transition">
                        {{-- Cover Image --}}
                        <div
                            class="w-full h-48 bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden relative mb-2">
                            <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                @if ($story->cover_path)
                                    <img src="{{ asset('storage/' . $story->cover_path) }}"
                                        alt="{{ $story->title }}"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-3xl bg-amber-50 dark:bg-slate-800 text-amber-500 font-black">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="absolute inset-0 pointer-events-none">
                                <div class="flex items-center space-x-1.5 p-2">
                                    <span
                                        class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-slate-900 dark:text-slate-100 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                        <i class="fa-regular fa-eye"></i>
                                        {{ number_format_short($story->views_count ?? 0) }}
                                    </span>
                                    <div class="absolute right-2">
                                        @if ($story->monetization_type === 'premium')
                                            <span
                                                class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-amber-500 text-white border border-amber-500 me-1">
                                                <i class="fa-solid fa-crown"></i>
                                            </span>
                                        @endif
                                        @if ($story->hasTypeChat())
                                            <span
                                                class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                <i class="fa-solid fa-comment-dots"></i>
                                            </span>
                                        @elseif(!$story->hasTypeChat() && $story->type === 'novel')
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                <i class="fa-solid fa-pencil"></i>
                                            </span>
                                        @elseif($story->type === 'puisi')
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                <i class="fa-solid fa-feather-pointed"></i>
                                            </span>
                                        @elseif($story->type === 'non_fiksi')
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[8px] font-bold bg-white/90 dark:bg-slate-900/90 text-brand-600 dark:text-brand-400 border border-white/60 dark:border-slate-700/60 shadow-xs">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute bottom-0 flex items-center space-x-1.5 p-2">
                                    <div
                                        class="flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400 mt-1">
                                        <div class="flex items-center gap-0.5 text-amber-500">
                                            <span><i class="fa-solid fa-star"></i></span>
                                            <span
                                                class="text-slate-700 dark:text-slate-200">{{ $story->average_rating ?? '0.0' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Cerita --}}
                        <div class="flex flex-col">
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 line-clamp-2 leading-snug">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                    class="hover:text-brand-600 dark:hover:text-brand-400 transition">
                                    {{ $story->title }}
                                </a>
                            </h3>

                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium line-clamp-1">
                                {{ $story->genre->name ?? 'Uncategorized' }}
                            </span>

                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium line-clamp-1">
                                <a href="{{ route('pen-name.show', [$story->penName->id ?? '']) }}"
                                    class="hover:underline">{{ '@' . ($story->penName?->name ?? 'Penulis Kisa') }}</a>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</div>
