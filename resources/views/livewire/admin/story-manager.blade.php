<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Kelola Cerita</h1>
            <p class="text-xs text-slate-400 mt-1">Atur status publikasi, review pengajuan publish, blokir cerita,
                pilihan editor, serta cari
                cerita platform.</p>
        </div>

        @if (auth()->check() && auth()->user()->email === 'admin@kisaindonesia.id')
            <div class="flex items-center gap-2">
                <button type="button" wire:click="syncPeriodicViewsFromTotal"
                    wire:confirm="Isi periodic_views_count menggunakan data views_count saat ini?"
                    class="px-4 py-2 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-sync"></i>
                    <span>Copy dari Views Count</span>
                </button>

                <button type="button" wire:click="resetAllPeriodicViews"
                    wire:confirm="Apakah Anda yakin ingin mereset seluruh perhitungan views periodik ini ke angka 0?"
                    class="px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Reset Periodic Views</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Flash Message / Notifikasi -->
    @if (session()->has('message'))
        <div
            class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>✓ {{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Bar -->
    <div
        class="bg-slate-950/60 p-4 border border-slate-800 rounded-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">

        <!-- Search Input -->
        <div class="lg:col-span-2">
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Cari Judul / Penulis</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Ketik judul atau nama penulis..."
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400 placeholder:text-slate-500">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Kategori</label>
            <select wire:model.live="category"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Tipe (Gratis / Premium) -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Tipe Akses</label>
            <select wire:model.live="type"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Tipe</option>
                <option value="free">Gratis</option>
                <option value="premium">Premium</option>
            </select>
        </div>

        <!-- Filter Status (Termasuk Pengajuan Publish) -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Status Cerita</label>
            <select wire:model.live="statusFilter"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Status</option>
                <option value="pending_review">Menunggu Review Publish</option>
                <option value="published">Published</option>
                <option value="rejected">Ditolak Publish</option>
                <option value="draft">Draft</option>
                <option value="active">Aktif (Tidak Diblokir)</option>
                <option value="blocked">Diblokir Admin</option>
            </select>
        </div>

        <!-- Filter Badge -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Badge / Section</label>
            <div class="flex gap-2">
                <select wire:model.live="selectedBadge"
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                    <option value="">Semua Badge</option>
                    @foreach ($badges as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>

                <!-- Tombol Reset Filter -->
                @if ($search || $category || $type || $selectedBadge || $statusFilter)
                    <button type="button" wire:click="resetFilters"
                        class="px-3 py-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-xs rounded-xl font-bold transition shrink-0"
                        title="Reset Filter">
                        ✕
                    </button>
                @endif
            </div>
        </div>

    </div>

    <!-- Tabel Cerita -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead
                class="bg-slate-800/80 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800">
                <tr>
                    <th class="p-3">No.</th>
                    <th class="p-3">Judul Cerita</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Penulis</th>
                    <th class="p-3 text-center">Total Views</th>
                    <th class="p-3 text-center">Views Periode Ini</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3 text-center">Badge & Section Spesial</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($stories as $index => $story)
                    <tr class="hover:bg-slate-800/30 transition {{ $story->status === 'pending_review' ? 'bg-amber-500/5' : '' }}"
                        wire:key="story-row-{{ $story->id }}">
                        <td class="p-3 font-semibold text-slate-200">
                            {{ $stories->firstItem() + $index }}
                        </td>
                        <td class="p-3 font-semibold text-slate-200">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="openDetailModal({{ $story->id }})"
                                        class="text-left font-semibold text-slate-100 hover:text-brand-400 transition"
                                        title="Lihat Detail Bab">
                                        {{ $story->title }}
                                    </button>
                                    @if ($story->monetization_type === 'premium')
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30 shrink-0">
                                            Premium
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 font-normal">
                                    {{ $story->chapters_count ?? $story->chapters()->count() }} Bab
                                </span>
                            </div>
                        </td>
                        <td class="p-3 font-semibold text-slate-200">
                            <div class="flex flex-col items-start gap-1.5">
                                <select wire:change="updateStoryStatus({{ $story->id }}, $event.target.value)"
                                    class="px-2 py-1 rounded-lg bg-slate-800 border border-slate-700 text-xs font-bold focus:outline-none focus:border-brand-400">
                                    <option value="draft" {{ $story->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_review" {{ $story->status === 'pending_review' ? 'selected' : '' }}>Review Publish</option>
                                    <option value="published" {{ $story->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="revisi" {{ $story->status === 'revisi' ? 'selected' : '' }}>Revisi</option>
                                    <option value="rejected" {{ $story->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>

                                @if ($story->status === 'revisi')
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-amber-950 border border-amber-800 text-amber-400 text-[9px] font-bold"
                                        title="{{ $story->rejection_reason ? 'Catatan revisi: ' . $story->rejection_reason : '' }}">
                                        📝 Perlu Revisi
                                    </span>
                                @elseif ($story->status === 'rejected')
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-rose-950 border border-rose-800 text-rose-400 text-[9px] font-bold"
                                        title="{{ $story->rejection_reason ? 'Alasan ditolak: ' . $story->rejection_reason : '' }}">
                                        ✕ Ditolak
                                    </span>
                                @endif

                                @if ($story->is_blocked)
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-rose-950 border border-rose-800 text-rose-400 text-[9px] font-bold">
                                        🚫 Diblokir
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium text-slate-200">{{ $story->author->name ?? '-' }}</div>
                            <div class="text-[10px] text-slate-500">{{ $story->author->email ?? '-' }}</div>
                        </td>
                        <td class="p-3 text-center font-bold text-slate-300">{{ number_format($story->views_count) }}
                        </td>
                        <td class="p-3 text-center font-bold text-brand-400">
                            {{ number_format($story->periodic_views_count) }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-300 text-[10px]">
                                {{ $story->genre->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap items-center justify-center gap-1.5">
                                @foreach ($badges as $badge)
                                    @php
                                        $hasBadge = $story->badges->contains('id', $badge->id);
                                        $pivotOrder = $hasBadge ? $story->badges->find($badge->id)->pivot->order : 0;
                                    @endphp

                                    <div
                                        class="flex items-center gap-1 bg-slate-800 p-1 rounded-xl border border-slate-700">
                                        <button type="button"
                                            wire:click="toggleBadge({{ $story->id }}, {{ $badge->id }})"
                                            class="px-2 py-0.5 rounded-lg text-[10px] font-bold transition {{ $hasBadge ? 'bg-brand-500 text-slate-950' : 'text-slate-400 hover:text-slate-200' }}">
                                            {{ $badge->name }}
                                        </button>

                                        @if ($hasBadge)
                                            <input type="number" value="{{ $pivotOrder }}"
                                                wire:change="updateBadgeOrder({{ $story->id }}, {{ $badge->id }}, $event.target.value)"
                                                title="Urutan Tampil"
                                                class="w-8 px-1 py-0.5 bg-slate-900 border border-slate-700 rounded text-[10px] font-bold text-center text-slate-100 focus:outline-none focus:border-brand-400">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex flex-wrap items-center justify-center gap-1.5">
                                <!-- Tombol Approval, Revisi & Reject Pengajuan Publish -->
                                @if ($story->status === 'pending_review')
                                    <button type="button" wire:click="approvePublishStory({{ $story->id }})"
                                        wire:confirm="Setujui dan publikasikan cerita '{{ $story->title }}' sekarang?"
                                        class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                        title="Setujui Publikasi">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Approve</span>
                                    </button>

                                    <button type="button" wire:click="openRevisiPublishModal({{ $story->id }})"
                                        class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                        title="Minta Revisi Sebelum Publish">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Revisi</span>
                                    </button>

                                    <button type="button" wire:click="openRejectPublishModal({{ $story->id }})"
                                        class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                        title="Tolak Publikasi Permanen">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>Reject</span>
                                    </button>
                                @endif

                                <!-- Tombol Detail Bab & Koin -->
                                <button type="button" wire:click="openDetailModal({{ $story->id }})"
                                    class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm"
                                    title="Lihat Detail Bab & Pembelian">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>Detail Bab</span>
                                </button>

                                <!-- Tombol Blokir -->
                                @if (auth()->check() && auth()->user()->email === 'admin@kisaindonesia.id')
                                    @if ($story->is_blocked)
                                        <button type="button" wire:click="toggleBlockStory({{ $story->id }})"
                                            wire:confirm="Buka blokir cerita '{{ $story->title }}'?"
                                            class="px-2.5 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                            title="Buka Blokir">
                                            <i class="fa-solid fa-lock-open"></i>
                                            <span>Buka</span>
                                        </button>
                                    @else
                                        <button type="button" wire:click="toggleBlockStory({{ $story->id }})"
                                            wire:confirm="Yakin ingin memblokir cerita '{{ $story->title }}'?"
                                            class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                            title="Blokir Cerita">
                                            <i class="fa-solid fa-ban"></i>
                                            <span>Blokir</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-slate-500">Tidak ada data cerita.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stories->links('vendor.livewire.custom-pagination') }}
    </div>

    <!-- MODAL REVISI PUBLIKASI CERITA -->
    @if ($showRevisiPublishModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm p-4 flex items-center justify-center"
            x-data @keydown.window.escape="$wire.closeRevisiPublishModal()">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl animate-fade-in"
                @click.away="$wire.closeRevisiPublishModal()">

                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                    <h3 class="text-sm font-bold text-amber-400 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Minta Revisi Sebelum Publikasi</span>
                    </h3>
                    <button type="button" wire:click="closeRevisiPublishModal"
                        class="text-slate-400 hover:text-slate-200 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="revisiPublishStory" class="space-y-4">
                    <div>
                        <p class="text-[11px] text-slate-400 mb-2">Penulis dapat melihat catatan revisi ini dan <strong>dapat mengajukan publish ulang</strong> setelah memperbaiki ceritanya.</p>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Catatan Revisi untuk Penulis <span class="text-amber-400">*</span>
                        </label>
                        <textarea wire:model="publishRevisiReason" rows="4"
                            placeholder="Jelaskan poin-poin yang harus diperbaiki oleh penulis (misal: perbaiki typo pada bab 1-3, lengkapi sinopsis cerita)..."
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-amber-500 placeholder:text-slate-600 leading-relaxed"></textarea>
                        @error('publishRevisiReason')
                            <span class="text-[11px] text-rose-400 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="closeRevisiPublishModal"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 disabled:opacity-50">
                            <span wire:loading.remove wire:target="revisiPublishStory">Kirim Catatan Revisi</span>
                            <span wire:loading wire:target="revisiPublishStory">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL PENOLAKAN PUBLIKASI CERITA (PERMANEN) -->
    @if ($showRejectPublishModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm p-4 flex items-center justify-center"
            x-data @keydown.window.escape="$wire.closeRejectPublishModal()">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl animate-fade-in"
                @click.away="$wire.closeRejectPublishModal()">

                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                    <h3 class="text-sm font-bold text-rose-400 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Tolak Pengajuan Publikasi (Permanen)</span>
                    </h3>
                    <button type="button" wire:click="closeRejectPublishModal"
                        class="text-slate-400 hover:text-slate-200 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="rejectPublishStory" class="space-y-4">
                    <div>
                        <p class="text-[11px] text-rose-400/90 mb-2">Perhatian: Penolakan ini bersifat permanen. Penulis <strong>tidak dapat mengajukan ulang</strong> cerita ini untuk dipublikasikan.</p>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Alasan Penolakan <span class="text-rose-400">*</span>
                        </label>
                        <textarea wire:model="publishRejectionReason" rows="4"
                            placeholder="Jelaskan alasan penolakan cerita (misal: melanggar pedoman komunitas/SARA/pornografi)..."
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-rose-500 placeholder:text-slate-600 leading-relaxed"></textarea>
                        @error('publishRejectionReason')
                            <span class="text-[11px] text-rose-400 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="closeRejectPublishModal"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 disabled:opacity-50">
                            <span wire:loading.remove wire:target="rejectPublishStory">Kirim Penolakan</span>
                            <span wire:loading wire:target="rejectPublishStory">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Detail Pembelian Bab Berbayar -->
    @if ($showDetailModal && !empty($selectedStoryData))
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm p-4 flex items-center justify-center"
            x-data @keydown.window.escape="$wire.closeDetailModal()">

            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-fade-in my-auto"
                @click.away="$wire.closeDetailModal()">

                <!-- Header Modal -->
                <div
                    class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between gap-4 shrink-0 bg-slate-900">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <!-- Cover Cerita Utama -->
                        <div
                            class="w-12 h-16 rounded-xl bg-slate-800 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center shadow-md">
                            @if (!empty($selectedStoryData['cover_path']))
                                <img src="{{ asset('storage/' . $selectedStoryData['cover_path']) }}"
                                    class="w-full h-full object-cover" alt="Cover">
                            @else
                                <span class="text-xs text-slate-500 font-bold">No Cover</span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-base font-bold text-slate-100 flex items-center gap-2 truncate">
                                <i class="fa-solid fa-coins text-amber-400 shrink-0"></i>
                                <span class="truncate">{{ $selectedStoryData['title'] }}</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5 truncate">
                                Penulis: <span
                                    class="text-slate-300 font-medium">{{ $selectedStoryData['author_name'] }}</span>
                                •
                                Kategori: <span
                                    class="text-slate-300 font-medium">{{ $selectedStoryData['genre_name'] }}</span>
                            </p>
                        </div>
                    </div>

                    <button type="button" wire:click="closeDetailModal"
                        class="p-1.5 text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded-lg transition shrink-0"
                        title="Tutup (Esc)">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <!-- 4 Kartu Ringkasan Sederhana -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-4 bg-slate-950/50 border-b border-slate-800 shrink-0">
                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                        <span class="text-[11px] text-slate-400 font-medium block">Bab Berbayar</span>
                        <span class="text-base font-bold text-slate-100 mt-1 block">
                            {{ $purchaseSummary['total_paid_chapters'] ?? 0 }} <span
                                class="text-xs font-normal text-slate-500">Bab</span>
                        </span>
                    </div>

                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                        <span class="text-[11px] text-slate-400 font-medium block">Sudah Dibeli</span>
                        <span class="text-base font-bold text-emerald-400 mt-1 block">
                            {{ $purchaseSummary['purchased_paid_chapters_count'] ?? 0 }} <span
                                class="text-xs font-normal text-slate-500">Bab</span>
                        </span>
                    </div>

                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                        <span class="text-[11px] text-slate-400 font-medium block">Total Pembelian</span>
                        <span class="text-base font-bold text-blue-400 mt-1 block">
                            {{ number_format($purchaseSummary['total_purchases'] ?? 0) }} <span
                                class="text-xs font-normal text-slate-500">Kali</span>
                        </span>
                    </div>

                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                        <span class="text-[11px] text-slate-400 font-medium block">Total Koin</span>
                        <span
                            class="text-base font-bold text-amber-400 mt-1 block flex items-center justify-center gap-1">
                            <i class="fa-solid fa-coins text-xs"></i>
                            <span>{{ number_format($purchaseSummary['total_beans'] ?? 0) }}</span>
                        </span>
                    </div>
                </div>

                <!-- Toolbar Sederhana: Toggle & Search -->
                <div
                    class="px-4 py-3 bg-slate-900 border-b border-slate-800 flex items-center justify-between gap-3 shrink-0">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 select-none">
                        <input type="checkbox" wire:model.live="onlyPaidChapters"
                            class="rounded bg-slate-800 border-slate-700 text-brand-500 focus:ring-brand-400 focus:ring-offset-slate-900">
                        <span>Hanya Bab Berbayar</span>
                    </label>

                    <div class="relative w-48 sm:w-56">
                        <input type="text" wire:model.live.debounce.250ms="modalSearch"
                            placeholder="Cari judul/nomor bab..."
                            class="w-full pl-3 pr-7 py-1 bg-slate-950/60 border border-slate-700 rounded-lg text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-brand-400">
                        @if ($modalSearch)
                            <button type="button" wire:click="$set('modalSearch', '')"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-xs">✕</button>
                        @endif
                    </div>
                </div>

                <!-- Tabel Daftar Bab Sederhana -->
                <div class="flex-1 min-h-0 overflow-y-auto">
                    <table class="w-full text-xs text-left text-slate-300">
                        <thead
                            class="bg-slate-800/80 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800 sticky top-0 z-10 backdrop-blur-md">
                            <tr>
                                <th class="p-3 text-center w-14">Bab</th>
                                <th class="p-3">Judul Bab & Cover</th>
                                <th class="p-3 text-center w-28">Status Bab</th>
                                <th class="p-3 text-center w-20">Harga</th>
                                <th class="p-3 text-center w-24">Pembelian</th>
                                <th class="p-3 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse ($filteredChapterPurchases as $chap)
                                <tr class="hover:bg-slate-800/40 transition"
                                    wire:key="modal-chap-{{ $chap['id'] }}">
                                    <td class="p-3 text-center font-bold text-slate-300">
                                        {{ $chap['order_number'] }}
                                    </td>
                                    <td class="p-3 font-medium text-slate-200">
                                        <div class="flex items-center gap-2.5">
                                            <!-- Thumbnail Cover Per Bab -->
                                            <div
                                                class="w-8 h-10 rounded-lg bg-slate-800 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if (!empty($chap['cover_path']))
                                                    <img src="{{ asset('storage/' . $chap['cover_path']) }}"
                                                        class="w-full h-full object-cover" alt="Bab">
                                                @else
                                                    <span class="text-[8px] text-slate-500">No Img</span>
                                                @endif
                                            </div>
                                            <span
                                                class="truncate max-w-[180px] sm:max-w-xs">{{ $chap['title'] }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center">
                                        <!-- Dropdown Ubah Status Bab -->
                                        <select
                                            wire:change="updateChapterStatus({{ $chap['id'] }}, $event.target.value)"
                                            class="px-2 py-1 rounded-lg bg-slate-950 border border-slate-700 text-[11px] font-bold focus:outline-none focus:border-brand-400
                            {{ $chap['status'] === 'published' ? 'text-emerald-400' : 'text-amber-400' }}">
                                            <option value="draft"
                                                {{ $chap['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published"
                                                {{ $chap['status'] === 'published' ? 'selected' : '' }}>Published
                                            </option>
                                        </select>
                                    </td>
                                    <td class="p-3 text-center">
                                        @if ($chap['is_paid'])
                                            <span
                                                class="px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-[10px]">
                                                {{ $chap['bean_price'] }} Bean
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[10px]">
                                                Gratis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <span
                                            class="text-slate-300 font-semibold">{{ $chap['purchases_count'] }}x</span>
                                        <span
                                            class="text-slate-500 text-[10px]">({{ number_format($chap['total_beans']) }}
                                            koin)</span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Baca / Preview -->
                                            <a href="{{ route('admin.chapters.preview', $chap['id']) }}"
                                                target="_blank"
                                                class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 text-[10px] font-bold rounded-lg transition"
                                                title="Baca Bab">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-500">
                                        Tidak ada bab yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Modal -->
                <div
                    class="p-3.5 border-t border-slate-800 flex items-center justify-between gap-3 shrink-0 bg-slate-900">
                    <span class="text-xs text-slate-400">
                        Total: <strong class="text-slate-200">{{ count($filteredChapterPurchases) }}</strong> bab
                    </span>

                    <button type="button" wire:click="closeDetailModal"
                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
