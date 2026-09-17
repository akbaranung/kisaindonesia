<div class="w-full min-h-screen bg-slate-50 max-w-2xl mx-auto border-x border-slate-100 pb-28">
    {{-- 📱 TOP NAVIGATION BAR --}}
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 py-3.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" wire:navigate
                class="p-1.5 text-slate-500 hover:text-slate-800 transition rounded-xl hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center text-sm shadow-2xs">
                    <i class="{{ $parentCategory->icon ?: 'fa-solid fa-shapes' }}"></i>
                </div>
                <div>
                    <h1 class="font-black text-base text-slate-900 tracking-tight">{{ $parentCategory->name }}</h1>
                    <p class="text-[10px] text-slate-400 font-medium">{{ $totalStories }} Cerita Ditemukan</p>
                </div>
            </div>
        </div>
    </header>

    <main class="p-4 md:p-6 space-y-6">

        {{-- 🔍 SEARCH BAR --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari judul cerita atau penulis di {{ $parentCategory->name }}..."
                class="w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition shadow-2xs">

            @if (!empty($search))
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </div>

        {{-- 🌟 SEKSYEN PILIHAN EDITOR (Hanya jika ada) --}}
        @if ($editorChoices->count() > 0 && empty($search) && empty($selectedSubCategory))
            <section class="space-y-3 bg-brand-50/40 p-4 rounded-3xl border border-brand-100/70">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-crown text-amber-500"></i> Pilihan Editor {{ $parentCategory->name }}
                        </h2>
                        <p class="text-[10px] text-slate-400 mt-0.5">Kisah unggulan rekomendasi tim editor</p>
                    </div>
                </div>

                {{-- Horizontal Scroll Slider --}}
                <div class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-none snap-x snap-mandatory">
                    @foreach ($editorChoices as $story)
                        <div class="w-28 flex-shrink-0 snap-start transition">
                            <div class="w-full h-40 bg-slate-100 rounded-xl overflow-hidden relative mb-2 shadow-3xs">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                    @if ($story->cover_path)
                                        <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-2xl bg-amber-50 text-amber-500 font-black">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="flex items-center justify-between p-1.5">
                                        <span class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white/90 text-slate-800 shadow-2xs">
                                            <i class="fa-regular fa-eye"></i>
                                            {{ number_format_short($story->views_count ?? 0) }}
                                        </span>
                                        @if ($story->monetization_type === 'premium')
                                            <span class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-amber-500 text-white shadow-2xs">
                                                <i class="fa-solid fa-crown"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <h3 class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug">
                                    <a href="{{ route('stories.read', $story->slug) }}" wire:navigate class="hover:text-brand-600 transition">
                                        {{ $story->title }}
                                    </a>
                                </h3>
                                <span class="text-[10px] text-slate-400 font-medium line-clamp-1 mt-0.5">
                                    <a href="{{ route('pen-name.show', [$story->penName->slug ?? '']) }}">{{ '@' . ($story->penName?->name ?? 'Penulis Kisa') }}</a>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 🏷️ FILTER SUB-KATEGORI --}}
        @if ($subCategories->count() > 0)
            <div class="space-y-2">
                <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Filter Sub-Kategori</h3>
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                    <button wire:click="selectSubCategory('')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition shrink-0 border {{ empty($selectedSubCategory) ? 'bg-slate-900 text-white border-slate-900 shadow-2xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Semua Sub-Kategori
                    </button>
                    @foreach ($subCategories as $sub)
                        <button wire:click="selectSubCategory({{ $sub->id }})"
                            class="px-3.5 py-1.5 rounded-full text-xs font-bold transition shrink-0 border {{ (string)$selectedSubCategory === (string)$sub->id ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $sub->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 📚 DAFTAR CERITA --}}
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                    Daftar Cerita ({{ $totalStories }})
                </h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @forelse ($stories as $story)
                    <div class="bg-white rounded-2xl border border-slate-100 p-2.5 shadow-3xs flex flex-col justify-between hover:border-slate-200 transition group">
                        <div>
                            {{-- Cover --}}
                            <div class="w-full h-44 bg-slate-100 rounded-xl overflow-hidden relative mb-2">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                    @if ($story->cover_path)
                                        <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-2xl bg-amber-50 text-amber-500 font-black">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                    @endif
                                </a>

                                {{-- Badges --}}
                                <div class="absolute inset-0 pointer-events-none p-1.5 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-white/90 text-slate-800 shadow-2xs">
                                            <i class="fa-regular fa-eye"></i> {{ number_format_short($story->views_count ?? 0) }}
                                        </span>
                                        @if ($story->monetization_type === 'premium')
                                            <span class="px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-amber-500 text-white shadow-2xs">
                                                <i class="fa-solid fa-crown"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Info --}}
                            <h4 class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-brand-600 transition">
                                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate>
                                    {{ $story->title }}
                                </a>
                            </h4>
                            <p class="text-[10px] text-slate-400 font-medium truncate mt-1">
                                <a href="{{ route('pen-name.show', [$story->penName->slug ?? '']) }}">{{ '@' . ($story->penName?->name ?? 'Penulis Kisa') }}</a>
                            </p>
                        </div>

                        {{-- Category Badge --}}
                        <div class="mt-2 pt-2 border-t border-slate-50 flex items-center justify-between text-[10px]">
                            <span class="font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md truncate max-w-[110px]">
                                {{ $story->genre->name ?? $parentCategory->name }}
                            </span>
                            <span class="text-amber-500 font-bold flex items-center gap-0.5">
                                ★ {{ $story->average_rating ?? '0.0' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 p-6">
                        <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h4 class="text-sm font-black text-slate-800">Belum Ada Cerita Ditemukan</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                            Tidak ada cerita yang cocok dengan kata kunci atau filter sub-kategori terpilih.
                        </p>
                        @if (!empty($search) || !empty($selectedSubCategory))
                            <button wire:click="resetFilters"
                                class="mt-4 px-4 py-2 bg-brand-600 text-white font-bold text-xs rounded-xl hover:bg-brand-700 transition">
                                Reset Filter
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Load More Button --}}
            @if ($totalStories > count($stories))
                <div class="mt-6 text-center">
                    <button wire:click="loadMore"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition">
                        Muat Lebih Banyak Cerita
                    </button>
                </div>
            @endif
        </div>
    </main>
</div>
