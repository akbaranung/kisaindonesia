<div>
    <!-- Header Page -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-100">Review Cerita Premium</h1>
            <p class="text-xs text-slate-400">Persetujuan pengajuan status cerita premium dari para penulis.</p>
        </div>
    </div>

    <!-- Alert Flash Message -->
    @if (session()->has('message'))
        <div
            class="mb-4 p-3 bg-emerald-950/80 border border-emerald-800/80 text-emerald-300 text-xs rounded-xl flex items-center justify-between">
            <span>✓ {{ session('message') }}</span>
        </div>
    @endif

    <!-- Card Filter & Tabel -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">

        <!-- Filter Bar -->
        <div class="mb-4 flex flex-col sm:flex-row gap-3 justify-between items-center">
            <!-- Search -->
            <div class="w-full sm:w-72">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul cerita / penulis..."
                    class="w-full px-3.5 py-2 bg-slate-800/60 border border-slate-700/80 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Status Filter Tab -->
            <div
                class="flex items-center gap-1.5 p-1 bg-slate-800/60 rounded-xl border border-slate-700/80 w-full sm:w-auto">
                <button wire:click="$set('statusFilter', 'pending')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ $statusFilter === 'pending' ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-slate-200' }}">
                    Pending
                </button>
                <button wire:click="$set('statusFilter', 'approved')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-slate-200' }}">
                    Disetujui
                </button>
                <button wire:click="$set('statusFilter', 'rejected')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ $statusFilter === 'rejected' ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-slate-200' }}">
                    Ditolak
                </button>
                <button wire:click="$set('statusFilter', '')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ $statusFilter === '' ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-slate-200' }}">
                    Semua
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-400">
                <thead
                    class="bg-slate-800/40 text-slate-300 font-bold uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Judul Cerita</th>
                        <th class="p-3">Tanggal Pengajuan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-800/20 transition">
                            <!-- Penulis -->
                            <td class="p-3">
                                <div class="font-bold text-slate-200">{{ $req->user->name ?? 'User Terhapus' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $req->user->email ?? '-' }}</div>
                            </td>

                            <!-- Judul Cerita -->
                            <td class="p-3">
                                <div class="font-bold text-amber-400">⭐ {{ $req->story->title ?? 'Cerita Terhapus' }}
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="p-3 text-slate-500">
                                {{ $req->created_at->format('d M Y, H:i') }}
                            </td>

                            <!-- Status Badge -->
                            <td class="p-3">
                                @if ($req->status === 'pending')
                                    <span
                                        class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold rounded-lg text-[10px]">
                                        PENDING
                                    </span>
                                @elseif($req->status === 'approved')
                                    <span
                                        class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold rounded-lg text-[10px]">
                                        DISETUJUI
                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold rounded-lg text-[10px]">
                                        DITOLAK
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="p-3 text-right flex items-center justify-end gap-1.5">
                                <button wire:click="openDetailModal({{ $req->id }})"
                                    class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition"
                                    title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                @if ($req->status === 'pending')
                                    <button wire:click="approve({{ $req->id }})"
                                        wire:confirm="Setujui cerita ini menjadi Premium?"
                                        class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition text-[11px]"
                                        title="Setujui">
                                        ✓ Approve
                                    </button>
                                    <button wire:click="openRejectModal({{ $req->id }})"
                                        class="px-2.5 py-1.5 bg-rose-950 hover:bg-rose-900 border border-rose-800 text-rose-300 font-bold rounded-lg transition text-[11px]"
                                        title="Tolak">
                                        ✕ Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">Tidak ada pengajuan cerita
                                premium.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links('vendor.livewire.custom-pagination') }}
        </div>
    </div>

    <!-- MODAL DETAIL CERITA -->
    @if ($isDetailModalOpen && $selectedRequest)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div
                class="w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl max-h-[90vh] flex flex-col">

                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 shrink-0">
                    <div>
                        <h3 class="text-base font-bold text-slate-100">Review Pengajuan Monetisasi</h3>
                        <p class="text-xs text-slate-400">Penulis: <strong
                                class="text-slate-200">{{ $selectedRequest->user->name ?? '-' }}</strong>
                            ({{ $selectedRequest->user->email ?? '-' }})</p>
                    </div>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="space-y-4 overflow-y-auto py-4 text-xs text-slate-300 pr-1">

                    <!-- Ringkasan Cerita & Bank -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 bg-slate-800/40 rounded-xl border border-slate-800">
                        <div>
                            <span class="text-slate-500 font-semibold block mb-0.5">Judul Cerita:</span>
                            <p class="font-bold text-amber-400 text-sm">{{ $selectedRequest->story->title ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-slate-500 font-semibold block mb-0.5">Rekening Pencairan:</span>
                            <p class="text-slate-200 font-medium">
                                {{ $selectedRequest->bank_name ?? '-' }} -
                                {{ $selectedRequest->account_number ?? '-' }}
                            </p>
                            <p class="text-[11px] text-slate-400">a.n
                                {{ $selectedRequest->account_holder_name ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Sinopsis -->
                    <div>
                        <span class="text-slate-400 font-bold block mb-1">Sinopsis:</span>
                        <p
                            class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/60 leading-relaxed text-slate-300">
                            {{ $selectedRequest->story->synopsis ?? 'Tidak ada sinopsis.' }}
                        </p>
                    </div>

                    <!-- Daftar Bab & Tombol Baca -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-slate-200 font-bold">Daftar Bab (Total:
                                {{ $selectedRequest->story->chapters->count() }} Bab)</span>
                            <span class="text-[11px] text-amber-400 font-medium">✨ Bab 6+ Otomatis Premium</span>
                        </div>

                        <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                            @forelse($selectedRequest->story->chapters as $chapter)
                                @php
                                    $wordCount = method_exists($chapter, 'calculateWordCount')
                                        ? $chapter->calculateWordCount()
                                        : 0;
                                    $estimatedBean = method_exists($chapter, 'calculateKisaBean')
                                        ? $chapter->calculateKisaBean()
                                        : 0;
                                    $isPremiumTarget = $chapter->order_number > 5;
                                @endphp

                                <div
                                    class="flex items-center justify-between p-3 bg-slate-800/40 border {{ $isPremiumTarget ? 'border-amber-500/30' : 'border-slate-800' }} rounded-xl text-xs hover:bg-slate-800/70 transition">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-300">Bab {{ $chapter->order_number }}:</span>
                                        <span class="font-medium text-slate-200">{{ $chapter->title }}</span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <!-- Badge Premium / Gratis -->
                                        @if ($isPremiumTarget)
                                            <span
                                                class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[10px] font-bold rounded-md">
                                                PREMIUM (🫘 {{ $estimatedBean }})
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 bg-slate-700/50 text-slate-400 text-[10px] font-bold rounded-md">
                                                GRATIS
                                            </span>
                                        @endif

                                        <!-- Count Kata -->
                                        <span
                                            class="text-slate-400 text-[11px] min-w-[70px] text-right">{{ number_format($wordCount) }}
                                            kata</span>

                                        <!-- Tombol Buka di Tab Baru -->
                                        <a href="{{ route('admin.chapters.preview', $chapter->id) }}" target="_blank"
                                            class="px-2.5 py-1 bg-slate-700 hover:bg-slate-600 text-slate-200 text-[11px] font-bold rounded-lg transition flex items-center gap-1">
                                            <span>👁️</span> Baca Bab
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-500 bg-slate-800/30 rounded-xl">
                                    Belum ada bab dalam cerita ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Status Info Pemproses -->
                    @if ($selectedRequest->processor)
                        <div
                            class="p-3 bg-slate-800/80 rounded-xl border border-slate-700/60 flex items-center justify-between">
                            <span class="text-slate-400">Diproses oleh Admin:</span>
                            <span class="font-bold text-emerald-400">{{ $selectedRequest->processor->name }}
                                ({{ $selectedRequest->processor->email }})</span>
                        </div>
                    @endif

                    @if ($selectedRequest->status === 'rejected' && $selectedRequest->rejection_reason)
                        <div class="p-3 bg-rose-950/40 border border-rose-800/60 rounded-xl text-rose-300">
                            <span class="font-bold block mb-1">Alasan Penolakan:</span>
                            {{ $selectedRequest->rejection_reason }}
                        </div>
                    @endif
                </div>

                <!-- Modal Footer (Actions) -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-800 shrink-0 mt-2">
                    <button type="button" wire:click="closeModals"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition">Tutup</button>
                    @if ($selectedRequest->status === 'pending')
                        <button type="button" wire:click="openRejectModal({{ $selectedRequest->id }})"
                            class="px-4 py-2 bg-rose-950 hover:bg-rose-900 border border-rose-800 text-rose-300 font-bold text-xs rounded-xl transition">✕
                            Reject</button>
                        <button type="button" wire:click="approve({{ $selectedRequest->id }})"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition">✓
                            Approve & Monetize</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL ALASAN PENOLAKAN -->
    @if ($isRejectModalOpen && $selectedRequest)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-rose-400">Tolak Pengajuan Premium</h3>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-200">✕</button>
                </div>

                <form wire:submit.prevent="reject" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Alasan Penolakan (akan dibaca
                            oleh Penulis)</label>
                        <textarea wire:model="rejectionReason" rows="3"
                            placeholder="Misal: Cerita masih terlalu pendek (kurang dari 5 bab) atau tidak memenuhi standar kualitas."
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-rose-500"></textarea>
                        @error('rejectionReason')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="closeModals"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl transition">Kirim
                            Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
