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

                    // Pengecekan status kontrak per cerita berdasarkan versi terbaru di settings dan masa berlaku 5 tahun
                    $latestContractVersion = (int) \App\Models\Setting::get('current_contract_version', 1);
                    $contract = $story->contract; // Relasi hasOne ke story_contracts
                    $isContractExpired = $contract && $contract->isExpired();
                    $hasSigned =
                        $contract && $contract->contract_version >= $latestContractVersion && !$isContractExpired;
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
                                    <span class="text-xs text-slate-400 font-bold">No Cover</span>
                                @endif
                            </a>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-sm font-black text-slate-800 dark:text-white truncate">
                                <a href="{{ route('stories.chapters', $story->id) }}" wire:navigate
                                    class="hover:underline">
                                    {{ $story->title }}
                                </a>
                            </h2>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">
                                {{ $story->synopsis ?? 'Tidak ada sinopsis' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span
                                    class="text-[9px] px-2 py-0.5 rounded-lg font-bold
                                {{ $story->status === 'published'
                                    ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20'
                                    : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                                    {{ ucfirst($story->status) }}
                                </span>
                                <span
                                    class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    {{ $story->chapters_count ?? $story->chapters()->count() }} Bab
                                </span>
                                <span
                                    class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    {{ $story->genre->name ?? 'Uncategorized' }}
                                </span>

                                @if ($isPending)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
                                        Menunggu Review
                                    </span>
                                @elseif($isApproved)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Pengajuan Disetujui
                                    </span>
                                @endif
                            </div>

                            {{-- Status & Informasi Kontrak Per Cerita --}}
                            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                @if ($hasSigned)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <i class="fa-solid fa-file-contract text-[9px]"></i> Kontrak Aktif
                                        (v{{ $contract->contract_version }})
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700"
                                        title="Berlaku 5 tahun s.d. {{ \Carbon\Carbon::parse($contract->expires_at)->translatedFormat('d M Y') }}">
                                        <i class="fa-regular fa-clock text-[9px]"></i> 5 Thn (s.d.
                                        {{ \Carbon\Carbon::parse($contract->expires_at)->format('d/m/Y') }})
                                    </span>
                                @elseif ($isContractExpired)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <i class="fa-solid fa-clock-rotate-left text-[9px]"></i> Masa Kontrak Berakhir
                                        (5 Tahun)
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
                                        class="text-[9px] px-2 py-0.5 bg-amber-500/10 text-amber-500 border border-amber-500/20 font-black rounded-lg">
                                        PREMIUM
                                    </span>
                                @else
                                    <span
                                        class="text-[9px] px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700 font-bold rounded-lg">
                                        GRATIS
                                    </span>
                                @endif

                                @if ($isPending)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
                                        Review
                                    </span>
                                @elseif($isApproved)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Disetujui
                                    </span>
                                @elseif($story->monetization_type === 'free')
                                    <a href="{{ route('monetization.apply', $story->id) }}" wire:navigate
                                        class="text-[9px] px-2.5 py-1.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                                        <span>Ajukan Monetisasi</span>
                                    </a>
                                @endif

                                <span
                                    class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-xl text-slate-700 dark:text-slate-300">
                                    <livewire:update-story-status :story="$story" :key="'story-status-' . $story->id" />
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                @if (($isApproved and !$hasSigned) || $isContractExpired)
                                    <a href="{{ route('author.contract.sign', $story->id) }}" wire:navigate
                                        class="text-[9px] px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-signature"></i>
                                        <span>{{ $isContractExpired ? 'Perpanjang Kontrak (5 Thn)' : ($contract ? 'Ttd Pembaruan' : 'Ttd Kontrak') }}</span>
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
