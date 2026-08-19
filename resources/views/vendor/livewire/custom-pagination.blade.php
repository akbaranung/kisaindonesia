@if ($paginator->hasPages())
    <div class="flex items-center justify-between border-t border-slate-800 pt-4 mt-4">
        <!-- Info Mobile / Counter Ringkas -->
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-slate-500 bg-slate-800/40 rounded-lg">Sebelumnya</span>
            @else
                <button wire:click="previousPage"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition">Sebelumnya</button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition">Berikutnya</button>
            @else
                <span class="px-3 py-1.5 text-xs text-slate-500 bg-slate-800/40 rounded-lg">Berikutnya</span>
            @endif
        </div>

        <!-- Tampilan Desktop (Nomor Halaman) -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-slate-400">
                    Menampilkan <span class="font-bold text-slate-200">{{ $paginator->firstItem() }}</span> sampai <span
                        class="font-bold text-slate-200">{{ $paginator->lastItem() }}</span> dari <span
                        class="font-bold text-slate-200">{{ $paginator->total() }}</span> hasil
                </p>
            </div>

            <div>
                <nav class="inline-flex gap-1 rounded-xl bg-slate-800/40 p-1" aria-label="Pagination">
                    {{-- Tombol Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-2.5 py-1 text-xs text-slate-600 cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <button wire:click="previousPage"
                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded-lg transition">
                            ‹
                        </button>
                    @endif

                    {{-- List Nomor Halaman --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span
                                class="inline-flex items-center px-2.5 py-1 text-xs text-slate-500">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-bold text-white bg-emerald-600 rounded-lg shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="inline-flex items-center px-3 py-1 text-xs font-semibold text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded-lg transition">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage"
                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded-lg transition">
                            ›
                        </button>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 text-xs text-slate-600 cursor-not-allowed">
                            ›
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endif
