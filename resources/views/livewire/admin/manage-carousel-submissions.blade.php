<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl p-6 space-y-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Manajemen Pengajuan Hero Carousel</h1>
            <p class="text-xs text-slate-400 mt-1">Persetujuan promosi cerita menggunakan Kisa Bean</p>
        </div>

        {{-- Filter Status --}}
        <div class="flex gap-2 p-1 bg-slate-800/60 rounded-xl border border-slate-700/80">
            <button wire:click="$set('statusFilter', '')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $statusFilter === '' ? 'bg-brand-400 text-slate-800 shadow-xs' : 'text-slate-500' }}">
                Semua
            </button>
            <button wire:click="$set('statusFilter', 'pending')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $statusFilter === 'pending' ? 'bg-brand-400 text-slate-800 shadow-xs' : 'text-slate-500' }}">
                Menunggu (Pending)
            </button>
            <button wire:click="$set('statusFilter', 'approved')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $statusFilter === 'approved' ? 'bg-brand-400 text-slate-800 shadow-xs' : 'text-slate-500' }}">
                Disetujui (Active)
            </button>
            <button wire:click="$set('statusFilter', 'rejected')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $statusFilter === 'rejected' ? 'bg-brand-400 text-slate-800 shadow-xs' : 'text-slate-500' }}">
                Ditolak
            </button>
        </div>
    </div>

    {{-- Alert Flash Message --}}
    @if (session()->has('message'))
        <div class="p-3 mb-4 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- Tabel Pengajuan --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-400">
            <thead class="bg-slate-800/40 text-slate-300 font-bold uppercase tracking-wider border-b border-slate-800">
                <tr>
                    <th class="p-3">Pemohon & Cerita</th>
                    <th class="p-3">Biaya (Bean)</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Masa Tayang</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                @forelse($submissions as $item)
                    <tr class="hover:bg-slate-800/20 transition">
                        <td class="p-4">
                            <div class="font-bold text-slate-200">{{ $item->story->title ?? 'Cerita Dihapus' }}</div>
                            <div class="text-[11px] text-slate-500">Oleh: {{ $item->user->name ?? 'User Unknown' }}
                            </div>
                        </td>
                        <td class="p-4 font-extrabold text-amber-600">
                            🫘 {{ number_format($item->cost_in_beans) }} Bean
                        </td>
                        <td class="p-4">
                            @if ($item->status === 'pending')
                                <span
                                    class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold rounded-lg text-[10px]">Pending</span>
                            @elseif($item->status === 'approved')
                                <span
                                    class="px-2.5 py-1 bg-brand-500/10 border border-brand-500/30 text-brand-400 font-bold rounded-lg text-[10px]">Approved</span>
                            @else
                                <span
                                    class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold rounded-lg text-[10px]">Rejected</span>
                            @endif
                        </td>
                        <td class="p-4 text-[11px] text-slate-500">
                            @if ($item->starts_at && $item->expires_at)
                                <div>{{ $item->starts_at->format('d/m/Y') }} - {{ $item->expires_at->format('d/m/Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $item->expires_at->isFuture() ? 'Berakhir ' . $item->expires_at->diffForHumans() : 'Selesai' }}
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if ($item->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="approve({{ $item->id }})"
                                        class="px-3 py-1 text-[11px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition shadow-xs">
                                        Approve
                                    </button>
                                    <button wire:click="openRejectModal({{ $item->id }})"
                                        class="px-3 py-1 text-[11px] font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition shadow-xs">
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                            Tidak ada data pengajuan dalam status ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $submissions->links('vendor.livewire.custom-pagination') }}
        </div>
    </div>

    {{-- Modal Reject Reason --}}
    @if ($isRejectModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="w-full max-w-sm p-6 bg-white shadow-xl rounded-2xl">
                <h3 class="text-base font-black text-slate-800 mb-2">Tolak Pengajuan Banner</h3>
                <p class="text-xs text-slate-500 mb-4">Kisa Bean akan otomatis dikembalikan ke saldo user.</p>

                <textarea wire:model="rejectReason" placeholder="Tuliskan alasan penolakan..."
                    class="w-full text-slate-500 p-3 text-xs border rounded-xl border-slate-200 focus:ring-2 focus:ring-rose-500 h-24 mb-3"></textarea>
                @error('rejectReason')
                    <span class="text-[11px] text-red-500 font-medium block mb-3">{{ $message }}</span>
                @enderror

                <div class="flex items-center justify-end gap-2">
                    <button wire:click="closeRejectModal"
                        class="px-3 py-1.5 text-xs font-bold text-slate-600 rounded-xl bg-slate-100 hover:bg-slate-200">
                        Batal
                    </button>
                    <button wire:click="reject"
                        class="px-3 py-1.5 text-xs font-bold text-white rounded-xl bg-rose-600 hover:bg-rose-700">
                        Tolak & Refund
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
