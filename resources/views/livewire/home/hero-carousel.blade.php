<div class="w-full py-4">
    @if ($featuredStories->isNotEmpty())
        <!-- Container Carousel Khusus Mobile dengan Touch Swipe -->
        <div x-data="{
            activeSlide: 0,
            slidesCount: {{ $featuredStories->count() }},
            timer: null,
            touchStartX: 0,
            touchEndX: 0,
            startAutoplay() {
                this.stopAutoplay();
                this.timer = setInterval(() => { this.next(); }, 5000);
            },
            stopAutoplay() {
                if (this.timer) clearInterval(this.timer);
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
            },
            handleTouchStart(e) {
                this.stopAutoplay();
                this.touchStartX = e.changedTouches[0].screenX;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
                this.startAutoplay();
            },
            handleSwipe() {
                if (this.touchStartX - this.touchEndX > 40) this.next();
                if (this.touchEndX - this.touchStartX > 40) this.prev();
            }
        }" x-init="startAutoplay()" @touchstart="handleTouchStart($event)"
            @touchend="handleTouchEnd($event)"
            class="relative bg-slate-950 rounded-2xl overflow-hidden shadow-xl border border-slate-800/80 text-white select-none h-[230px]">

            <!-- Carousel Slides (Fixed Height Container) -->
            <div class="relative w-full h-full">
                @foreach ($featuredStories as $index => $hero)
                    <div x-show="activeSlide === {{ $index }}" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4"
                        class="absolute inset-0 w-full h-full p-4 flex flex-col justify-between">

                        <!-- Background Blur Cover -->
                        <div class="absolute inset-0 bg-cover bg-center scale-110 pointer-events-none"
                            style="background-image: url('{{ asset('storage/' . $hero->cover_path) }}');">
                        </div>
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-950/30 pointer-events-none">
                        </div>

                        <!-- Content Layout Compact -->
                        <div class="relative z-10 space-y-2">

                            <!-- Badges -->
                            <div class="flex items-center space-x-1.5">
                                <span
                                    class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-brand-500 text-brand-300 border border-brand-500">
                                    Cerita Pilihan
                                </span>
                                @if ($hero->monetization_type === 'premium')
                                    <span
                                        class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500 text-amber-300 border border-amber-500">
                                        Premium
                                    </span>
                                @endif
                            </div>

                            <!-- Judul Utama (Mobile Optimized) -->
                            <h1 class="text-lg font-black tracking-tight text-white line-clamp-1 leading-snug">
                                {{ $hero->title }}
                            </h1>

                            <!-- Info Penulis & Genre -->
                            <div class="flex items-center space-x-2 text-[11px] text-slate-300">
                                <span class="text-brand-400 font-semibold truncate max-w-[120px]">
                                    {{ $hero->penName->name ?? 'Anonim' }}
                                </span>
                                <span>•</span>
                                <div class="flex items-center space-x-1 overflow-hidden">
                                    <span class="bg-slate-800/90 text-slate-300 px-1.5 py-0.5 rounded text-[9px]">
                                        {{ $hero->genre->name ?? 'Umum' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Sinopsis Singkat (2 Baris) -->
                            <p class="text-[11px] text-slate-300 leading-relaxed line-clamp-2">
                                {{ $hero->synopsis }}
                            </p>
                        </div>

                        <!-- CTA Button & Indicators (Bottom Row) -->
                        <div class="relative z-10 pt-2 flex items-center justify-between border-t border-slate-800/60">

                            <!-- Indicator Dots (Minimalis) -->
                            <div class="flex space-x-1 items-center">
                                @foreach ($featuredStories as $dotIndex => $dotHero)
                                    <button @click="activeSlide = {{ $dotIndex }}"
                                        :class="activeSlide === {{ $dotIndex }} ? 'bg-brand-500 w-4' : 'bg-slate-700 w-1.5'"
                                        class="h-1.5 rounded-full transition-all duration-300 focus:outline-none">
                                    </button>
                                @endforeach
                            </div>

                            <!-- Button Baca -->
                            <a href="{{ route('stories.read', $hero->slug) }}"
                                class="inline-flex items-center space-x-1.5 bg-brand-600 active:bg-brand-700 text-white text-xs font-bold px-4 py-1.5 rounded-lg transition shadow-md shadow-brand-600/30">
                                <span>Baca</span>
                            </a>

                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @else
        <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 text-center text-xs text-slate-500">
            Belum ada cerita pilihan.
        </div>
    @endif
</div>
