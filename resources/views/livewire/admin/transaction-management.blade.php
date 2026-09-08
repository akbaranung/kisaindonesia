<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-100">Manajemen Transaksi Kisa Bean</h1>
        <p class="text-xs text-slate-400">Kelola dan pantau seluruh transaksi pembayaran Duitku pengguna.</p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-900 p-5 rounded-xl border border-slate-800/80 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Pendapatan</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1">Rp {{ number_format($stats['total_revenue']) }}</p>
        </div>
        <div class="bg-slate-900 p-5 rounded-xl border border-slate-800/80 shadow-sm">
            <p class="text-xs text-slate-500 font-medium uppercase">Transaksi Sukses</p>
            <p class="text-2xl font-bold text-slate-100 mt-1">{{ number_format($stats['total_success']) }}</p>
        </div>
        <div class="bg-slate-900 p-5 rounded-xl border border-slate-800/80 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Menunggu Pembayaran</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['total_pending']) }}</p>
        </div>
        <div class="bg-slate-900 p-5 rounded-xl border border-slate-800/80 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Expired / Gagal</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['total_failed']) }}</p>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div
        class="bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-800/80 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Cari Ref Code, Nama, atau Email User..."
                class="w-full px-4 py-2 border border-slate-800/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
        </div>
        <div class="w-full md:w-auto flex gap-2">
            <select wire:model.live="statusFilter"
                class="px-4 py-2 border border border-slate-800/80 rounded-lg text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">Semua Status</option>
                <option value="success">Sukses</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
            </select>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="bg-slate-900 shadow-sm border border-slate-800/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-slate-800/40 text-slate-300 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Merchant Ref / Tanggal</th>
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Beans / Nominal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-800/20 transition">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-200">{{ $tx->reference_code }}</span>
                                <p class="text-xs text-slate-500">{{ $tx->created_at->format('d M Y, H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold  text-slate-200">{{ $tx->user->name ?? 'User Terhapus' }}</p>
                                <p class="text-xs text-slate-500">{{ $tx->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="uppercase font-semibold text-xs bg-gray-100 px-2 py-1 rounded">
                                    {{ $tx->payment_method ?? 'DUITKU' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-brand-600">+{{ number_format($tx->amount) }} Beans</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($tx->gross_amount) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($tx->status === 'success')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-medium">Sukses</span>
                                @elseif($tx->status === 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full font-medium">Pending</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded-full font-medium">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="openDetail({{ $tx->id }})"
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition">
                                        Detail
                                    </button>

                                    @if ($tx->status === 'pending')
                                        <button wire:click="syncStatus({{ $tx->id }})"
                                            class="px-3 py-1 bg-brand-50 hover:bg-brand-100 text-brand-600 text-xs font-semibold rounded transition"
                                            title="Cek Ulang Status ke Duitku">
                                            Sync Status
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada transaksi
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800/80">
            {{ $transactions->links() }}
        </div>
    </div>

    {{-- Modal Detail Transaksi --}}
    @if ($showDetailModal && $selectedTransaction)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-slate-900 rounded-xl shadow-xl max-w-lg w-full p-6 relative">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Transaksi
                    #{{ $selectedTransaction->reference_code }}</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Nama User</span>
                        <span class="font-semibold">{{ $selectedTransaction->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Email User</span>
                        <span class="font-semibold">{{ $selectedTransaction->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Jumlah Beans</span>
                        <span class="font-bold text-brand-600">+{{ number_format($selectedTransaction->amount) }} Kisa
                            Beans</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Total Harga</span>
                        <span class="font-bold">Rp {{ number_format($selectedTransaction->gross_amount) }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Status Transaksi</span>
                        <span class="font-semibold capitalize">{{ $selectedTransaction->status }}</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
