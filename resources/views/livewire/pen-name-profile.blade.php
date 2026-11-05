<div x-data="{ showCoverPreview: false }"
    class="w-full max-w-5xl mx-auto pb-8 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen transition-colors duration-200">
    <header
        class="sticky mb-2 top-0 z-30 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-4 py-3.5 flex items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" wire:navigate
                class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="font-black text-base text-slate-900 dark:text-slate-100 tracking-tight">Profile Pena</h1>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Halaman profile Pena
                    {{ $penName->name }}</p>
            </div>
        </div>

        {{-- Tombol Toggle Dark/Light Mode --}}
        <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark Mode"
            class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-moon hidden dark:block text-amber-300 text-sm"></i>
            <i class="fa-solid fa-sun block dark:hidden text-amber-500 text-sm"></i>
        </button>
    </header>
    {{-- 1. HEADER PROFIL NAMA PENA --}}
    <div
        class="px-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 md:p-8 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            {{-- Avatar Nama Pena --}}
            <div
                class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden bg-brand-100 dark:bg-brand-950 flex-shrink-0 border-4 border-slate-50 dark:border-slate-800 shadow-inner flex items-center justify-center">
                @if ($penName->avatar)
                    <img src="{{ asset('storage/' . $penName->avatar) }}" alt="{{ $penName->name }}"
                        class="w-full h-full object-cover" @click="showCoverPreview = true"
                        oncontextmenu="return false;" ondragstart="return false;">
                    <div @click="showCoverPreview = true" oncontextmenu="return false;" ondragstart="return false;"
                        class="absolute inset-0 z-10 cursor-pointer"></div>
                @else
                    <span class="text-3xl font-black text-brand-600 dark:text-brand-400">
                        {{ strtoupper(substr($penName->name, 0, 1)) }}
                    </span>
                @endif
            </div>

            @if ($penName->avatar)
                <!-- MODAL PREVIEW COVER PROTECTED -->
                <template x-teleport="body">
                    <div x-show="showCoverPreview" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" @keydown.window.escape="showCoverPreview = false"
                        @keydown.window.ctrl.s.prevent @keydown.window.ctrl.p.prevent oncontextmenu="return false;"
                        ondragstart="return false;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 backdrop-blur-md p-4 print:hidden select-none">

                        <div class="fixed inset-0" @click="showCoverPreview = false"></div>

                        <div class="relative z-10 max-w-sm w-full flex flex-col items-center gap-3">
                            <div class="w-full flex items-center justify-between text-slate-300 px-2">
                                <span class="text-xs font-bold flex items-center gap-1.5 text-slate-400">
                                    <i class="fa-solid fa-shield-halved text-brand-400"></i> Preview Image
                                </span>
                                <button type="button" @click="showCoverPreview = false"
                                    class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center text-sm font-bold transition">
                                    ✕
                                </button>
                            </div>

                            <div
                                class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-800 bg-slate-900 max-h-[75vh] select-none">
                                <img src="{{ asset('storage/' . $penName->avatar) }}" alt="{{ $penName->name }}"
                                    oncontextmenu="return false;" ondragstart="return false;"
                                    class="max-h-[75vh] w-auto object-contain select-none">

                                <div class="absolute inset-0 z-20 pointer-events-auto flex items-center justify-center overflow-hidden"
                                    oncontextmenu="return false;" ondragstart="return false;">
                                    <div
                                        class="opacity-20 rotate-[-30deg] text-white font-extrabold text-xs tracking-widest text-center select-none pointer-events-none whitespace-nowrap">
                                        KISA INDONESIA • PROTECTED PREVIEW
                                    </div>
                                </div>
                            </div>

                            <p class="text-[10px] text-slate-400 font-medium text-center">
                                🔒 Cover dilindungi hak cipta &amp; tidak dapat diunduh.
                            </p>
                        </div>
                    </div>
                </template>
            @endif

            {{-- Detail Profil --}}
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100">{{ $penName->name }}</h1>
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 mt-0.5">@
                            {{ $penName->slug }}</p>
                    </div>

                    {{-- Tombol Follow --}}
                    <div>
                        <livewire:follow-button :pen-name="$penName" variant="default"
                            :wire:key="'follow-pen-'.$penName->id" />
                    </div>
                </div>

                {{-- Bio --}}
                @if ($penName->bio)
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-3 max-w-2xl leading-relaxed">
                        {{ $penName->bio }}
                    </p>
                @endif

                {{-- Statistik Nama Pena --}}
                <div
                    class="flex items-center justify-center md:justify-start gap-6 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div>
                        <span
                            class="block text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ number_format($stories->total()) }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Cerita</span>
                    </div>
                    <div class="h-8 w-px bg-slate-100 dark:bg-slate-800"></div>
                    <div>
                        <span
                            class="block text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ number_format($penName->followers_count) }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Pengikut</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. KATALOG CERITA NAMA PENA --}}
    <div
        class="px-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 mb-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            {{-- Input Search --}}
            <div class="lg:col-span-5 relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari cerita karya {{ $penName->name }}..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-2.5 text-slate-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Filter Status --}}
            <div class="lg:col-span-2">
                <select wire:model.live="status"
                    class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-brand-500 transition">
                    <option value="">Semua Status</option>
                    <option value="ongoing">Berjalan (Ongoing)</option>
                    <option value="completed">Tamat (Completed)</option>
                </select>
            </div>

            {{-- Filter Genre/Kategori --}}
            @if ($genres->isNotEmpty())
                <div class="lg:col-span-3">
                    <select wire:model.live="genreId"
                        class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-brand-500 transition">
                        <option value="">Semua Genre</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Pengurutan --}}
            <div class="{{ $genres->isNotEmpty() ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                <select wire:model.live="sortBy"
                    class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-brand-500 transition">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="popular">Terpopuler</option>
                </select>
            </div>
        </div>

        {{-- Indikator Filter Aktif & Reset --}}
        @if (!empty($search) || !empty($status) || !empty($genreId) || $sortBy !== 'latest')
            <div
                class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100 dark:border-slate-800 text-xs">
                <span class="text-slate-500 dark:text-slate-400">
                    Menampilkan hasil pencarian filter
                </span>
                <button wire:click="resetFilters"
                    class="text-brand-600 dark:text-brand-400 hover:text-brand-700 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    <div>
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-6">Karya Cerita</h2>

        @if ($stories->isEmpty())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-12 text-center border border-dashed border-slate-200 dark:border-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                    @if (!empty($search) || !empty($status) || !empty($genreId))
                        Tidak ada cerita yang cocok dengan kriteria pencarian/filter.
                    @else
                        Belum ada cerita yang dipublikasikan oleh nama pena ini.
                    @endif
                </p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                @foreach ($stories as $story)
                    <a href="{{ route('stories.read', $story->slug) }}"
                        class="group flex flex-col bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 overflow-hidden hover:shadow-md transition">
                        {{-- Cover & Badge --}}
                        <div class="aspect-[3/4] bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                            @if ($story->cover_path)
                                <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-brand-50 dark:bg-brand-950 text-brand-300 font-bold text-xs p-2 text-center">
                                    {{ $story->title }}
                                </div>
                            @endif

                            {{-- Badge Status --}}
                            <span
                                class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $story->story_status === 'completed' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">
                                {{ $story->story_status === 'completed' ? 'Tamat' : 'Ongoing' }}
                            </span>
                        </div>

                        {{-- Detail Cerita --}}
                        <div class="p-3.5 flex flex-col flex-1 justify-between">
                            <div>
                                <h3
                                    class="text-sm font-bold text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">
                                    {{ $story->title }}
                                </h3>

                                {{-- Sinopsis Singkat --}}
                                <p
                                    class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-relaxed">
                                    {{ $story->synopsis }}
                                </p>
                            </div>

                            {{-- Footer Card: Metrik --}}
                            <div
                                class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $story->chapters->count() ?? 0 }} Bab
                                </span>

                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ number_format($story->views_count ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $stories->links() }}
            </div>
        @endif
    </div>
</div>
