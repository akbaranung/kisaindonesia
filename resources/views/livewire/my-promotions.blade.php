<div class="w-full p-4 max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800">Riwayat Promo Hero Banner</h1>
            <p class="text-xs text-slate-500">Pantau status pengajuan promosi cerita kamu di aplikasi.</p>
        </div>

        {{-- Tombol Ajukan Baru --}}
        <button onclick="Livewire.dispatch('openSubmitCarouselModal')"
            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition shadow-xs self-start sm:self-auto">
            <span>🫘</span>
            <span>Ajukan Promo Baru</span>
        </button>

        <livewire:submit-carousel-modal />
    </div>

    {{-- Filter Status --}}
    <div class="flex gap-2 p-1 bg-slate-100 rounded-xl w-fit">
        @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $key => $label)
            <button wire:click="$set('filterStatus', '{{ $key }}')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $filterStatus === $key ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Daftar Submissions --}}
    <div class="space-y-3">
        @forelse($submissions as $item)
            <div
                class="bg-white border border-slate-100 rounded-2xl p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">

                {{-- Info Cerita & Biaya --}}
                <div class="flex items-start gap-3">
                    <div class="w-12 h-16 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
                        @if ($item->story->cover_url)
                            <img src="{{ $item->story->cover_url }}" alt="{{ $item->story->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">📖</div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 line-clamp-1">
                            {{ $item->story->title ?? 'Cerita Tidak Ditemukan' }}
                        </h3>
                        <div class="text-xs font-extrabold text-amber-600 mt-0.5">
                            🪙 {{ number_format($item->cost_in_beans) }} Kisa Bean
                        </div>
                        <div class="text-[10px] text-slate-400 mt-1">
                            Diajukan pada: {{ $item->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                {{-- Status & Masa Tayang --}}
                <div
                    class="flex flex-col md:items-end justify-between gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                    <div>
                        @if ($item->status === 'pending')
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-amber-700 bg-amber-50 rounded-xl border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Persetujuan
                            </span>
                        @elseif($item->status === 'approved')
                            @if ($item->expires_at && $item->expires_at->isPast())
                                <span
                                    class="px-3 py-1 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl border border-slate-200">
                                    Selesai (Expired)
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Sedang Tayang
                                </span>
                            @endif
                        @else
                            <span
                                class="px-3 py-1 text-xs font-bold text-rose-700 bg-rose-50 rounded-xl border border-rose-200">
                                Ditolak & Refunded
                            </span>
                        @endif
                    </div>

                    {{-- Informasi Tambahan Berdasarkan Status --}}
                    @if ($item->status === 'approved' && $item->starts_at && $item->expires_at)
                        <div class="text-[11px] text-slate-500">
                            Tayang: <span class="font-semibold text-slate-700">{{ $item->starts_at->format('d M') }} -
                                {{ $item->expires_at->format('d M Y') }}</span>
                            @if ($item->expires_at->isFuture())
                                <span
                                    class="text-emerald-600 font-bold">({{ $item->expires_at->diffForHumans() }})</span>
                            @endif
                        </div>
                    @elseif($item->status === 'rejected' && $item->admin_note)
                        <div
                            class="text-[11px] text-rose-600 bg-rose-50/50 px-2.5 py-1 rounded-lg border border-rose-100 max-w-xs">
                            <span class="font-bold">Alasan Ditolak:</span> {{ $item->admin_note }}
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="p-8 text-center bg-white border border-slate-100 rounded-2xl">
                <p class="text-xs text-slate-400 font-medium">Belum ada riwayat pengajuan promo banner.</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $submissions->links() }}
        </div>
    </div>
</div>
