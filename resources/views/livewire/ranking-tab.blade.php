<section class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 gap-3">
        <div>
            <h2
                class="text-base font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider flex items-center gap-2">
                Trending
            </h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">10 Cerita teratas dengan pembaca terbanyak saat
                ini.</p>
        </div>

        <!-- Navigasi Tab Gratis & Premium -->
        <div class="grid grid-cols-2 text-center border-b border-slate-200 dark:border-slate-800">
            <div wire:click="setRankingTab('free')"
                class="pb-2.5 text-xs font-bold border-b-2 cursor-pointer transition {{ $rankingTab === 'free' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300' }}">
                Gratis
            </div>
            <div wire:click="setRankingTab('premium')"
                class="pb-2.5 text-xs font-bold border-b-2 cursor-pointer transition {{ $rankingTab === 'premium' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300' }}">
                Premium
            </div>
        </div>
    </div>

    <!-- Daftar Peringkat (List 1 - 10) -->
    <div class="overflow-hidden">
        <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
            @forelse($topStories as $index => $story)
                <a href="{{ route('stories.read', $story->slug) }}" wire:navigate
                    class="py-3 px-2 flex items-center gap-3.5 transition group hover:bg-slate-50/80 dark:hover:bg-slate-900/60 rounded-2xl">

                    <!-- Nomor Peringkat (Ranking Badge) -->
                    <div class="flex-shrink-0 w-7 text-center">
                        @if ($index == 0)
                            <span class="text-lg font-black text-amber-500 dark:text-amber-400">#1</span>
                        @elseif($index == 1)
                            <span class="text-base font-bold text-slate-400 dark:text-slate-300">#2</span>
                        @elseif($index == 2)
                            <span class="text-base font-bold text-amber-700 dark:text-amber-500">#3</span>
                        @else
                            <span
                                class="text-xs font-bold text-slate-400 dark:text-slate-500">#{{ $index + 1 }}</span>
                        @endif
                    </div>

                    <!-- Cover Gambar -->
                    <div
                        class="w-12 h-16 bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden flex-shrink-0 border border-slate-200/60 dark:border-slate-800 shadow-3xs">
                        @if ($story->cover_path)
                            <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-xs text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800">
                                📖
                            </div>
                        @endif
                    </div>

                    <!-- Detail Cerita -->
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition truncate">
                            {{ $story->title }}
                        </h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5 font-medium">
                            {{ '@' . ($story->penName?->name ?? 'Anonim') }}
                        </p>
                        <div class="mt-1 flex items-center gap-2">
                            @if ($story->monetization_type === 'premium')
                                <span class="text-[10px] text-amber-500 font-bold">
                                    <i class="fa-solid fa-crown"></i>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Icon Chevron -->
                    <div
                        class="text-right flex-shrink-0 text-xs text-slate-300 dark:text-slate-600 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition group-hover:translate-x-0.5">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>

                </a>
            @empty
                <div class="py-10 text-center text-xs text-slate-400 dark:text-slate-500 font-medium">
                    Belum ada peringkat cerita untuk kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</section>
