<div class="w-full max-w-6xl mx-auto py-6 mb-20">
    {{-- Header --}}
    <div class="flex flex-col gap-1 mb-6">
        <h1 class="text-2xl font-black text-slate-800">Jelajahi Semua Cerita</h1>
        <p class="text-xs text-slate-500">Temukan ribuan cerita menarik dari berbagai genre dan penulis pilihan.</p>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col p-2 gap-3 bg-white rounded-2xl border border-slate-100 shadow-2xs mb-6">
        <div class="relative w-full">
            <input type="text" wire:model.live.debounce.1000ms="search" placeholder="Cari judul atau penulis..."
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500" />
        </div>

        {{-- Category Horizontal Filter --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <button wire:click="$set('selectedCategory', '')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Genre
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('selectedCategory', '{{ $cat->id }}')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedCategory == $cat->id ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Monetization & Sorting --}}
        <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-1">
                <button wire:click="$set('selectedMonetization', '')"
                    class="px-2.5 py-1 rounded-lg font-bold transition {{ empty($selectedMonetization) ? 'bg-slate-200 text-slate-900' : 'text-slate-400 hover:text-slate-700' }}">
                    Semua
                </button>
                <button wire:click="$set('selectedMonetization', 'free')"
                    class="px-2.5 py-1 rounded-lg font-bold transition {{ $selectedMonetization === 'free' ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-slate-400 hover:text-slate-700' }}">
                    Gratis
                </button>
                <button wire:click="$set('selectedMonetization', 'premium')"
                    class="px-2.5 py-1 rounded-lg font-bold transition {{ $selectedMonetization === 'premium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-400 hover:text-slate-700' }}">
                    Premium
                </button>
            </div>

            <select wire:model.live="sortBy"
                class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-bold text-slate-700 focus:outline-none">
                <option value="latest">Terbaru</option>
                <option value="popular">Terpopuler</option>
                <option value="title">Judul (A-Z)</option>
            </select>
        </div>
    </div>

    {{-- Story Grid Catalog --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($stories as $story)
            <div
                class="flex flex-col bg-white rounded-2xl border border-slate-100 p-2 shadow-2xs hover:shadow-md transition">
                <div class="w-full h-44 bg-slate-100 rounded-xl overflow-hidden relative mb-2">
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

                <div class="flex flex-col flex-1 justify-between gap-1">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">
                            <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                                class="hover:text-brand-600 transition">
                                {{ $story->title }}
                            </a>
                        </h3>

                        <span class="text-[10px] text-slate-400 font-medium line-clamp-1">
                            {{ $story->penName?->name ?? 'Penulis Kisa' }}
                        </span>
                    </div>

                    <div
                        class="flex items-center justify-between text-[10px] font-bold text-slate-500 pt-1 border-t border-slate-50">
                        <div class="flex items-center gap-0.5 text-amber-500">
                            <span>★</span>
                            <span class="text-slate-700">{{ $story->average_rating ?? '0.0' }}</span>
                        </div>
                        <span class="text-slate-400">{{ $story->chapters_count ?? 0 }} Bab</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-100 p-6">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-sm font-black text-slate-800">Cerita Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain atau bersihkan filter pencarianmu.</p>
                <button wire:click="resetFilters"
                    class="mt-4 px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                    Reset Semua Filter
                </button>
            </div>
        @endforelse
    </div>

    @if ($stories->count() < $totalStories)
        <div class="mt-8 flex flex-col items-center justify-center gap-2">
            {{-- Tombol Load More Manual --}}
            <button wire:click="loadMore" wire:loading.attr="disabled"
                class="px-6 py-2.5 bg-slate-900 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak</span>
                <span wire:loading wire:target="loadMore" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>

            {{-- Opsional: Pemicu Infinite Scroll Otomatis saat di-scroll ke bawah (Livewire 3) --}}
            <div wire:intersect="loadMore" class="h-2 w-full"></div>

            <span class="text-[11px] text-slate-400 font-medium mt-1">
                Menampilkan {{ $stories->count() }} dari {{ $totalStories }} cerita
            </span>
        </div>
    @elseif ($stories->count() > 0)
        <div class="mt-8 text-center text-xs text-slate-400 font-medium">
            Semua cerita telah ditampilkan
        </div>
    @endif

    <div x-data="{ showTopBtn: false }" x-init="window.addEventListener('scroll', () => { showTopBtn = window.scrollY > 300 })" class="fixed bottom-20 right-6 z-50">
        <button x-show="showTopBtn" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })" type="button" aria-label="Kembali ke atas"
            class="flex items-center justify-center w-11 h-11 bg-slate-900/90 hover:bg-brand-600 text-white rounded-full shadow-lg backdrop-blur-xs transition-all duration-300 hover:scale-110 active:scale-95 focus:outline-none">
            <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </div>
</div>
