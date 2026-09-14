<div class="bg-slate-950 text-slate-100 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Kelola Pencairan Dana</h1>
            <p class="text-xs text-slate-400">Verifikasi dan proses permintaan penarikan saldo penulis.</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div
        class="bg-slate-900 border border-slate-800 rounded-2xl p-4 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari No. Referensi / Nama Penulis..."
                class="w-full pl-9 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-xs"></i>
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <label class="text-xs text-slate-400">Status:</label>
            <select wire:model.live="filterStatus"
                class="px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Konfirmasi</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl" x-data="{ openProofModal: false, selectedProof: '', openReasonModal: false, selectedReason: '' }">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-800/60 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3">No. Ref / Tanggal</th>
                        <th class="px-4 py-3">Penulis</th>
                        <th class="px-4 py-3">Rekening Tujuan</th>
                        <th class="px-4 py-3">Jumlah Penarikan</th>
                        <th class="px-4 py-3">Admin Fee</th>
                        <th class="px-4 py-3">Net Amount</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse ($requests as $item)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-100 block">{{ $item->reference_no }}</span>
                                <span
                                    class="text-[10px] text-slate-400">{{ $item->created_at->format('d M Y H:i') }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-semibold text-slate-200 block">{{ $item->user->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->user->email }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-amber-400 uppercase block">{{ $item->bank_name }}</span>
                                <span class="font-mono text-slate-200 text-xs block">{{ $item->account_number }}</span>
                                <span class="text-[10px] text-slate-400">a.n {{ $item->account_name }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-brand-400 text-sm">Rp
                                    {{ number_format($item->gross_amount, 0, ',', '.') . ' (' . number_format($item->kisa_amount) . ' Kisa)' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-rose-400 text-sm">Rp
                                    {{ number_format($item->admin_fee, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-brand-400 text-sm">Rp
                                    {{ number_format($item->net_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($item->status === 'pending')
                                    <span
                                        class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 font-semibold text-[10px] rounded-lg">
                                        Pending
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span
                                        class="px-2.5 py-1 bg-brand-500/10 text-brand-400 border border-brand-500/30 font-semibold text-[10px] rounded-lg">
                                        Disetujui
                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/30 font-semibold text-[10px] rounded-lg">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($item->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="openProcessModal({{ $item->id }}, 'approve')"
                                            class="px-2.5 py-1 bg-brand-600 hover:bg-brand-500 text-white font-bold text-[10px] rounded-lg transition">
                                            Setujui
                                        </button>
                                        <button wire:click="openProcessModal({{ $item->id }}, 'reject')"
                                            class="px-2.5 py-1 bg-rose-600 hover:bg-rose-500 text-white font-bold text-[10px] rounded-lg transition">
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-500 text-[10px]">Diproses oleh
                                        {{ $item->processor?->name ?? 'Sistem' }}</span>
                                @endif
                                <div class="my-2">
                                    @if ($item->status === 'approved' && $item->proof_file_path)
                                        <button type="button"
                                            @click="selectedProof='{{ asset('storage/' . $item->proof_file_path) }}'; openProofModal = true"
                                            class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg text-[11px] font-medium text-emerald-400 transition">
                                            Lihat Bukti
                                        </button>
                                    @elseif($item->status === 'rejected' && $item->rejection_reason)
                                        <button type="button"
                                            @click="selectedReason='{{ addslashes($item->rejection_reason) }}'; openReasonModal = true"
                                            class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/40 border border-rose-800/60 rounded-lg text-[11px] font-medium text-rose-300 transition">
                                            Alasan Penolakan
                                        </button>
                                    @else
                                        <span class="text-slate-500 text-[10px]">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                Belum ada permintaan pencairan dana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $requests->links() }}
        </div>

        <div x-show="openProofModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.away="openProofModal = false"
                class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-5 space-y-4 shadow-2xl">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">Bukti Transfer Admin</h3>
                    <button @click="openProofModal=false"
                        class="text-slate-400 hover:text-slate-200 text-xs font-bold">&times;</button>
                </div>
                <div class="flex justify-center bg-slate-950 p-2 rounded-xl border border-slate-800">
                    <img :src="selectedProof" alt="Bukti Transfer" class="max-h-96 rounded-lg object-contain">
                </div>
                <button @click="openProofModal=false"
                    class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-xl text-slate-200">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Modal Alasan Penolakan -->
        <div x-show="openReasonModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.away="openReasonModal = false"
                class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-5 space-y-4 shadow-2xl">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-rose-400">Detail Alasan Penolakan</h3>
                    <button @click="openReasonModal=false"
                        class="text-slate-400 hover:text-slate-200 text-xs font-bold">&times;</button>
                </div>
                <div
                    class="bg-rose-950/30 border border-rose-900/50 p-4 rounded-xl text-xs text-rose-200 leading-relaxed">
                    <p x-text="selectedReason"></p>
                </div>
                <p class="text-[11px] text-slate-400">Catatan: Saldo Kisa Anda telah dikembalikan secara otomatis
                    ke akun Anda.</p>
                <button @click="openReasonModal=false"
                    class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-xl text-slate-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Proses (Approve / Reject) -->
    @if ($showProcessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">
                        {{ $actionType === 'approve' ? 'Setujui Pencairan' : 'Tolak Pencairan' }}
                    </h3>
                    <button wire:click="$set('showProcessModal', false)" class="text-slate-400 hover:text-slate-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-3 bg-slate-800/50 border border-slate-700/50 rounded-xl space-y-1 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Penulis:</span>
                        <span class="font-semibold text-slate-200">{{ $selectedRequest->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Bank Tujuan:</span>
                        <span class="font-semibold text-amber-400">{{ strtoupper($selectedRequest->bank_name) }} -
                            {{ $selectedRequest->account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nominal:</span>
                        <span class="font-bold text-brand-400">Rp
                            {{ number_format($item->gross_amount, 0, ',', '.') . ' (' . number_format($item->kisa_amount) . ' Kisa)' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Admin:</span>
                        <span class="font-bold text-rose-400">Rp
                            {{ number_format($item->admin_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Net Amount:</span>
                        <span class="font-bold text-brand-400">Rp
                            {{ number_format($item->net_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit.prevent="processRequest" class="space-y-4">
                    @if ($actionType === 'approve')
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Unggah Bukti Transfer <span
                                    class="text-rose-500">*</span></label>
                            <input type="file" wire:model="proofFile" accept="image/*"
                                class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700">
                            @error('proofFile')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Alasan Penolakan <span
                                    class="text-rose-500">*</span></label>
                            <textarea wire:model="rejectionReason" rows="3"
                                placeholder="Contoh: Nomor rekening tidak valid / nama tidak sesuai"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500"></textarea>
                            @error('rejectionReason')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                        <button type="button" wire:click="$set('showProcessModal', false)"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 {{ $actionType === 'approve' ? 'bg-brand-600 hover:bg-brand-500' : 'bg-rose-600 hover:bg-rose-500' }} text-white font-bold text-xs rounded-xl shadow-md transition disabled:opacity-50">
                            <span wire:loading.remove>Konfirmasi</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
