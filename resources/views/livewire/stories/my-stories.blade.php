<div
    class="min-h-screen bg-slate-50/50 dark:bg-slate-950 pb-12 p-4 pt-2 text-slate-800 dark:text-slate-100 transition-colors duration-200">
    @if ($action === 'list')
        <header
            class="flex items-center justify-between w-full pb-5 border-b border-slate-100 dark:border-slate-800 mb-6">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-brand-600 dark:text-brand-400 uppercase tracking-widest">Writer
                    Studio</span>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Cerita Saya</h1>
            </div>

            <button wire:click="switchAction('create')"
                class="bg-slate-900 hover:bg-brand-600 dark:bg-slate-800 dark:hover:bg-brand-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-xs flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7-7H5" />
                </svg>
                Cerita Baru
            </button>
        </header>

        @if (session()->has('success'))
            <div
                class="p-3.5 text-xs font-bold text-emerald-800 dark:text-emerald-200 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-100 dark:border-emerald-900 rounded-2xl flex items-center justify-between animate-fade-in mb-6">
                <span><i class="fa-regular fa-circle-check"></i> {{ session('success') }}</span>
                <button wire:click="$refresh"
                    class="text-emerald-500 hover:text-emerald-800 dark:hover:text-emerald-300">✕</button>
            </div>
        @endif

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Filter & Search --}}
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul..."
                    class="text-xs p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 w-60 focus:ring-brand-500 focus:border-brand-500">

                <select wire:model.live="filterMonetization"
                    class="text-xs p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 w-35 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Tipe</option>
                    <option value="free">Gratis</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <a href="{{ route('promotions') }}"
                class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition shadow-xs pointer w-fit">
                <span>🫘</span> Promo Hero Banner Saya
            </a>

            @forelse($myStories as $story)
                @php
                    $latestRequest = $story->premiumRequests->first();
                    $isPending = $latestRequest && $latestRequest->status === 'pending';
                    $isApproved = $latestRequest && $latestRequest->status === 'approved';

                    // Pengecekan status kontrak per cerita berdasarkan versi terbaru di settings
                    $latestContractVersion = (int) \App\Models\Setting::get('current_contract_version', 1);
                    $contract = $story->contract; // Relasi hasOne ke story_contracts
                    $hasSigned = $contract && $contract->contract_version >= $latestContractVersion;
                @endphp
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-4 shadow-2xl dark:shadow-none transition-colors">
                    <div class="flex gap-4 items-center">
                        <div
                            class="w-16 h-22 bg-slate-50 dark:bg-slate-800 rounded-2xl overflow-hidden flex-shrink-0 border border-slate-100 dark:border-slate-700 flex items-center justify-center">
                            <a href="{{ route('stories.chapters', $story->id) }}" wire:navigate>
                                @if ($story->cover_path)
                                    <img src="{{ asset('storage/' . $story->cover_path) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600" fill="none"
                                        stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                @endif
                            </a>
                        </div>

                        <div class="flex-2 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                @if ($story->monetization_type === 'premium')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-brand-100 dark:bg-brand-950/80 text-brand-800 dark:text-brand-300 text-[10px] font-extrabold uppercase">
                                        ⭐ Premium
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase">
                                        Gratis
                                    </span>
                                @endif
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    {{ $story->genre->name }}</p>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate"><a
                                    href="{{ route('stories.chapters', $story->id) }}"
                                    wire:navigate>{{ $story->title }}</a></h3>

                            <div
                                class="flex items-center gap-3 mt-2 text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                                <span class="flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    {{ $story->chapters->count() }} Bab
                                    <span class="mx-1">•</span>
                                    Dibuat {{ $story->created_at->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Status & Informasi Kontrak Per Cerita --}}
                            <div class="mt-2.5 flex items-center gap-2">
                                @if ($hasSigned)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <i class="fa-solid fa-file-contract text-[9px]"></i> Kontrak Aktif
                                        (v{{ $contract->contract_version }})
                                    </span>
                                @elseif ($contract)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Pembaruan Kontrak
                                        Diperlukan
                                    </span>
                                @elseif ($isApproved and !$hasSigned)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <i class="fa-solid fa-file-circle-xmark text-[9px]"></i> Kontrak Belum
                                        Ditandatangani
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="block mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                @if ($story->monetization_type === 'premium')
                                    <span
                                        class="text-[9px] px-2 py-1 rounded-xl bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-900 font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Monetisasi Aktif
                                    </span>
                                @elseif ($isApproved and $story->monetization_type === 'free')
                                    <span
                                        class="text-[9px] px-2 py-1 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900 font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Tanda Tangan Kontrak
                                    </span>
                                @elseif($isPending)
                                    <span
                                        class="text-[9px] px-2 py-1 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900 font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Sedang Ditinjau Admin
                                    </span>
                                @else
                                    <a href="{{ route('monetization.apply', ['story_id' => $story->id]) }}"
                                        class="text-[9px] px-2.5 py-1 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold transition-all shadow-xs flex items-center">
                                        <span>Monetisasi Konten</span>
                                    </a>
                                @endif

                                <span
                                    class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-xl text-slate-700 dark:text-slate-300">
                                    <livewire:update-story-status :story="$story" :key="'story-status-' . $story->id" />
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                @if ($isApproved and !$hasSigned)
                                    <a href="{{ route('author.contract.sign', $story->id) }}" wire:navigate
                                        class="text-[9px] px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-signature"></i>
                                        <span>{{ $contract ? 'Ttd Pembaruan' : 'Ttd Kontrak' }}</span>
                                    </a>
                                @elseif($hasSigned)
                                    <button wire:click="downloadContractPdf({{ $story->id }})" type="button"
                                        class="text-[9px] px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl transition flex items-center gap-1">
                                        <i class="fa-solid fa-file-pdf text-rose-400"></i>
                                        <span>PDF</span>
                                    </button>
                                @endif

                                <button wire:click="editStory({{ $story->id }})"
                                    class="text-[9px] p-1.5 bg-brand-600 hover:bg-brand-500 rounded-xl text-white transition flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="text-center py-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 border-dashed">
                    <p class="text-sm font-medium text-slate-400 dark:text-slate-500">Belum ada cerita yang dibuat.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $myStories->links() }}
        </div>
    @else
        @include('stories.create-stories')
    @endif

</div>
