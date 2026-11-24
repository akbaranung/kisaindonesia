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
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('promotions') }}"
                    class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition shadow-xs pointer w-fit">
                    <span>🫘</span> Promo Hero Banner Saya
                </a>

                <a href="{{ route('messages.index') }}" wire:navigate
                    class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-brand-500 dark:hover:border-brand-500 rounded-xl transition shadow-xs pointer w-fit">
                    <i class="fa-solid fa-comments text-brand-500"></i>
                    <span>Pesan ke Admin</span>
                    @if (auth()->user()->conversation && auth()->user()->conversation->user_unread_count > 0)
                        <span
                            class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-rose-500 text-white animate-pulse">
                            {{ auth()->user()->conversation->user_unread_count }}
                        </span>
                    @endif
                </a>
            </div>

            @forelse($myStories as $story)
                @php
                    $latestRequest = $story->premiumRequests->first();
                    $isPending = $latestRequest && $latestRequest->status === 'pending';
                    $isApproved = $latestRequest && $latestRequest->status === 'approved';
                    $isRevisi = $latestRequest && $latestRequest->status === 'revisi';
                    $isRejected = $latestRequest && $latestRequest->status === 'rejected';

                    // Cerita bisa dihapus hanya jika belum premium & tidak sedang/sudah diajukan monetisasi
                    $canDelete = $story->monetization_type !== 'premium' && !$isPending && !$isApproved;

                    // Pengecekan status kontrak per cerita berdasarkan versi terbaru di settings dan masa berlaku 5 tahun
                    $latestContractVersion = (int) \App\Models\Setting::get('current_contract_version', 1);
                    $contract = $story->contract; // Relasi hasOne ke story_contracts
                    $isContractExpired = $contract && $contract->isExpired();
                    $hasSigned =
                        $contract && $contract->contract_version >= $latestContractVersion && !$isContractExpired;
                @endphp
                <div
                    class="bg-white dark:bg-slate-900 border {{ $story->is_blocked ? 'border-rose-500/40 bg-rose-500/5' : 'border-slate-100 dark:border-slate-800' }} rounded-3xl p-4 shadow-2xl dark:shadow-none transition-colors">
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
                                @if ($story->is_blocked)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center gap-1">
                                        <i class="fa-solid fa-ban text-[8px]"></i> Diblokir oleh Admin
                                    </span>
                                @elseif ($story->status === 'published')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                                        Published
                                    </span>
                                @elseif ($story->status === 'pending_review')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        Review Publish
                                    </span>
                                @elseif ($story->status === 'revisi')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20"
                                        title="{{ $story->rejection_reason ? 'Catatan revisi: ' . $story->rejection_reason : '' }}">
                                        Perlu Revisi
                                    </span>
                                @elseif ($story->status === 'rejected')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20"
                                        title="{{ $story->rejection_reason ? 'Alasan ditolak: ' . $story->rejection_reason : '' }}">
                                        Publish Ditolak
                                    </span>
                                @else
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Draft
                                    </span>
                                @endif
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
                                        Ditinjau Editor
                                    </span>
                                @elseif($isApproved)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Pengajuan Disetujui
                                    </span>
                                @elseif($isRevisi)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20"
                                        title="{{ $latestRequest->rejection_reason ? 'Catatan revisi: ' . $latestRequest->rejection_reason : '' }}">
                                        Revisi Monetisasi
                                    </span>
                                @elseif($isRejected)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20"
                                        title="{{ $latestRequest->rejection_reason ? 'Alasan ditolak: ' . $latestRequest->rejection_reason : '' }}">
                                        Monetisasi Ditolak
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
                                @elseif($isRevisi)
                                    <a href="{{ route('monetization.apply', ['story_id' => $story->id]) }}"
                                        wire:navigate
                                        class="text-[9px] px-2.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition flex items-center gap-1 shadow-sm"
                                        title="{{ $latestRequest->rejection_reason ? 'Catatan revisi: ' . $latestRequest->rejection_reason : '' }}">
                                        <span>Perbaiki Monetisasi</span>
                                    </a>
                                @elseif($isRejected)
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-rose-500/10 text-rose-500 border border-rose-500/20"
                                        title="{{ $latestRequest->rejection_reason ? 'Alasan ditolak: ' . $latestRequest->rejection_reason : '' }}">
                                        Monetisasi Ditolak
                                    </span>
                                @elseif($story->monetization_type === 'free')
                                    <a href="{{ route('monetization.apply', ['story_id' => $story->id]) }}"
                                        wire:navigate
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

                                @if ($story->completion_status === 'tamat')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                        <i class="fa-solid fa-flag-checkered"></i> Tamat
                                    </span>
                                @elseif ($story->completion_status === 'pending')
                                    <span
                                        class="text-[9px] px-2 py-0.5 rounded-lg font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
                                        Tamat: Menunggu Editor
                                    </span>
                                @elseif (in_array($story->completion_status, ['ongoing', 'rejected']) && $story->status === 'published')
                                    @if ($reason = $story->completionBlockReason())
                                        <span
                                            class="text-[9px] px-2.5 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold rounded-xl cursor-not-allowed"
                                            title="{{ $reason }}">Ajukan Tamat</span>
                                    @else
                                        <button wire:click="requestCompletion({{ $story->id }})"
                                            wire:confirm="Ajukan cerita ini tamat? Admin akan mengecek dulu sebelum statusnya berubah. Setelah tamat, bab tidak bisa diedit atau dihapus lagi."
                                            class="text-[9px] px-2.5 py-1.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition shadow-sm">
                                            Ajukan Tamat
                                        </button>
                                    @endif
                                @endif

                                @if ($canDelete)
                                    <button type="button" wire:click="confirmDelete({{ $story->id }})"
                                        title="Hapus cerita"
                                        class="text-[9px] p-1.5 bg-rose-600 hover:bg-rose-500 rounded-xl text-white transition flex items-center justify-center disabled:opacity-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
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
                        @if ($story->status === 'rejected' && $story->rejection_reason)
                            <div
                                class="mt-3 p-2.5 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-[11px] text-rose-600 dark:text-rose-300">
                                <span class="font-black block mb-0.5"><i class="fa-solid fa-triangle-exclamation"></i>
                                    Publish Ditolak:</span>
                                <p class="leading-relaxed">{{ $story->rejection_reason }}</p>
                            </div>
                        @endif
                        @if ($story->status === 'revisi' && $story->rejection_reason)
                            <div
                                class="mt-3 p-2.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-600 dark:text-amber-300">
                                <span class="font-black block mb-0.5"><i class="fa-solid fa-triangle-exclamation"></i>
                                    Revisi Publish:</span>
                                <p class="leading-relaxed">{{ $story->rejection_reason }}</p>
                            </div>
                        @endif
                        @if ($story->completion_status === 'rejected' && $story->completion_note)
                            <div
                                class="mt-3 p-2.5 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-[11px] text-rose-600 dark:text-rose-300">
                                <span class="font-black block mb-0.5">Pengajuan Tamat Ditolak:</span>
                                <p class="leading-relaxed">{{ $story->completion_note }}</p>
                            </div>
                        @endif
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

    {{-- Modal konfirmasi hapus cerita --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4" role="dialog"
            aria-modal="true" aria-labelledby="delete-story-title" wire:keydown.escape.window="cancelDelete">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="cancelDelete"></div>

            <div
                class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden">
                <div class="p-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="shrink-0 w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 id="delete-story-title"
                                class="text-base font-black text-slate-900 dark:text-white leading-tight">
                                Hapus cerita ini secara permanen?
                            </h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 break-words">
                                &ldquo;{{ $deleteStoryTitle }}&rdquo;
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-5 p-3.5 rounded-2xl bg-rose-500/5 border border-rose-500/20 text-[12px] leading-relaxed text-rose-700 dark:text-rose-300">
                        <p class="font-bold mb-2">Tindakan ini tidak dapat dibatalkan. Yang akan dihapus:</p>
                        <ul class="space-y-1.5 list-disc pl-4">
                            <li>Cerita beserta cover dan sinopsisnya</li>
                            <li><strong>{{ $deleteChaptersCount }}</strong> bab beserta isi, cover bab, dan semua
                                gambar di dalamnya</li>
                            <li><strong>{{ $deleteCharactersCount }}</strong> karakter beserta avatarnya</li>
                            <li>Seluruh file terkait di server</li>
                        </ul>
                    </div>

                    <p class="mt-4 text-[11px] text-slate-500 dark:text-slate-400">
                        Data yang sudah dihapus <span class="font-bold">tidak bisa dikembalikan</span>. Pastikan Anda
                        sudah menyimpan salinan tulisan Anda jika masih diperlukan.
                    </p>
                </div>

                <div
                    class="px-6 py-4 bg-slate-50 dark:bg-slate-950/40 border-t border-slate-100 dark:border-slate-800 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <button type="button" wire:click="cancelDelete" wire:loading.attr="disabled"
                        wire:target="deleteStory"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition disabled:opacity-50">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteStory" wire:loading.attr="disabled"
                        wire:target="deleteStory"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl bg-rose-600 hover:bg-rose-500 text-white shadow-sm transition disabled:opacity-60 flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="deleteStory" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteStory">Ya, Hapus Permanen</span>
                        <span wire:loading wire:target="deleteStory">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
