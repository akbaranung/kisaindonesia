<div class="bg-slate-950 text-slate-100 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Kelola Paket Koin (Beans)</h1>
            <p class="text-xs text-slate-400">Atur daftar paket pembelian koin/beans untuk pengguna.</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition flex items-center gap-2 self-start md:self-auto">
            <i class="fas fa-plus"></i> Tambah Paket
        </button>
    </div>

    <!-- Filter & Search -->
    <div
        class="bg-slate-900 border border-slate-800 rounded-2xl p-4 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama paket atau badge..."
                class="w-full pl-9 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-xs"></i>
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <label class="text-xs text-slate-400">Status:</label>
            <select wire:model.live="filterStatus"
                class="px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-800/60 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-center">Urutan</th>
                        <th class="px-4 py-3">Nama Paket</th>
                        <th class="px-4 py-3">Beans (Utama + Bonus)</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Badge Label</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse ($packages as $item)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-4 py-3.5 text-center font-bold text-amber-500">
                                #{{ $item->order_priority }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-100">{{ $item->name }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5 font-semibold text-slate-200">
                                    <i class="fas fa-coins text-amber-400"></i>
                                    <span>{{ number_format($item->beans) }}</span>
                                    @if ($item->bonus_beans > 0)
                                        <span
                                            class="text-emerald-400 font-bold">(+{{ number_format($item->bonus_beans) }})</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($item->discount_price && $item->discount_price > 0)
                                    <div>
                                        <span class="font-bold text-emerald-400">Rp
                                            {{ number_format($item->discount_price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-slate-500 line-through ml-1">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <span class="font-bold text-slate-200">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($item->badge_label)
                                    <span
                                        class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-semibold text-[10px] rounded-lg">
                                        {{ $item->badge_label }}
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <button wire:click="toggleStatus({{ $item->id }})"
                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $item->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500/20' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEditModal({{ $item->id }})"
                                        class="p-1.5 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})"
                                        class="p-1.5 bg-slate-800 hover:bg-slate-700 text-rose-400 rounded-lg transition"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                <i class="fas fa-box-open text-3xl mb-2 block"></i>
                                Belum ada paket koin yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $packages->links() }}
        </div>
    </div>

    <!-- Modal Form (Create / Edit) -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div
                class="w-full max-w-lg bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">
                        {{ $isEditMode ? 'Edit Paket Koin' : 'Tambah Paket Koin Baru' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Nama Paket -->
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Nama Paket <span
                                class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Misal: Paket Pemula / Super Value"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                        @error('name')
                            <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jumlah Beans & Bonus Beans -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Jumlah Beans <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" wire:model="beans" min="1"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('beans')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Bonus Beans</label>
                            <input type="number" wire:model="bonus_beans" min="0"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('bonus_beans')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga Normal & Harga Diskon -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Harga Normal (Rp) <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" wire:model="price" step="0.01" min="0"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('price')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Harga Diskon (Rp)</label>
                            <input type="number" wire:model="discount_price" step="0.01" min="0"
                                placeholder="Opsional"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('discount_price')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Badge Label & Urutan Prioritas -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Badge Label</label>
                            <input type="text" wire:model="badge_label" placeholder="Misal: POPULAR / BEST VALUE"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            @error('badge_label')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Urutan Prioritas <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" wire:model="order_priority" min="0"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                            <span class="text-[10px] text-slate-500">Angka lebih kecil tampil lebih awal</span>
                            @error('order_priority')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="is_active" wire:model="is_active"
                            class="rounded border-slate-700 bg-slate-800 text-brand-500 focus:ring-0">
                        <label for="is_active" class="text-xs text-slate-300">Aktifkan paket koin ini</label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-800">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-slate-950 font-bold text-xs rounded-xl shadow-md transition disabled:opacity-50">
                            <span wire:loading.remove>{{ $isEditMode ? 'Simpan Perubahan' : 'Tambah Paket' }}</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-2xl space-y-4">
                <div class="flex items-center gap-3 text-rose-500">
                    <div class="p-2 bg-rose-500/10 rounded-xl border border-rose-500/20">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-100">Hapus Paket Koin</h3>
                        <p class="text-[10px] text-slate-400">Konfirmasi penghapusan data</p>
                    </div>
                </div>

                <p class="text-xs text-slate-300">Apakah Anda yakin ingin menghapus paket koin ini? Tindakan ini tidak
                    dapat dibatalkan.</p>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" wire:click="delete"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
