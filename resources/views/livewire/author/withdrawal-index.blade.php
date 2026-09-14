<div class="bg-slate-950 text-slate-100 min-h-screen pt-2 pb-20">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header & Informasi Saldo -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div>
                <h1 class="text-xl font-bold text-slate-100">Penarikan Saldo Kisa</h1>
                <p class="text-xs text-slate-400 mt-0.5">Tukarkan koin Kisa Anda ke Rupiah melalui rekening bank atau
                    e-wallet.</p>
            </div>
            <div class="bg-slate-800 border border-slate-700/60 rounded-xl px-4 py-3 text-right">
                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Saldo Kisa
                    Anda</span>
                <span class="text-xl font-black text-amber-400">{{ number_format(auth()->user()->earned_beans) }}
                    Kisa</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Pengajuan Penarikan -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                <h2 class="text-sm font-bold text-slate-200 mb-4 border-b border-slate-800 pb-3">Form Pengajuan
                    Pencairan</h2>

                <form wire:submit.prevent="submitWithdrawal" class="space-y-4">

                    <!-- Opsi Pilih / Toggle Input Bank Manual -->
                    @if ($has_saved_bank)
                        <div
                            class="flex items-center justify-between bg-slate-800/40 p-3 rounded-xl border border-slate-800">
                            <span class="text-xs text-slate-300">Gunakan Rekening Premium?</span>
                            <button type="button" wire:click="toggleManualBank"
                                class="text-[11px] font-semibold text-brand-400 hover:underline">
                                {{ $is_manual_bank ? 'Gunakan Rekening Bawaan' : 'Ubah / Input Manual' }}
                            </button>
                        </div>
                    @endif

                    @if (!$is_manual_bank && $has_saved_bank)
                        <!-- Tampilan Rekening Bawaan dari Premium Request -->
                        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-3 space-y-1 text-xs">
                            <span class="text-[10px] text-slate-400 font-semibold block uppercase">Rekening Tujuan
                                Penarikan</span>
                            <div class="font-bold text-slate-200">{{ strtoupper($bank_name) }} - {{ $account_number }}
                            </div>
                            <div class="text-slate-400">a.n. {{ $account_name }}</div>
                        </div>
                    @else
                        <!-- Form Input Manual Rekening Bank / E-Wallet -->
                        <div class="space-y-3 bg-slate-800/30 p-3 rounded-xl border border-slate-800">
                            <span class="text-[10px] text-amber-400 font-semibold block uppercase">Detail Rekening
                                Tujuan</span>

                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Nama Bank / E-Wallet <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" wire:model="bank_name"
                                    placeholder="Contoh: BCA, Mandiri, Dana, OVO"
                                    class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                                @error('bank_name')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Nomor Rekening / HP <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" wire:model="account_number"
                                    placeholder="Nomor Rekening atau HP E-Wallet"
                                    class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                                @error('account_number')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Nama Pemilik Rekening <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" wire:model="account_name" placeholder="Nama Sesuai Rekening Bank"
                                    class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                                @error('account_name')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Input Kisa -->
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">
                            Jumlah Kisa yang ditarik <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" wire:model.live="kisa_amount" min="{{ $min_kisa }}"
                            placeholder="Minimal {{ $min_kisa }} Kisa"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-500">
                        @error('kisa_amount')
                            <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rincian Kalkulasi -->
                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-3 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-400">
                            <span>Rate Konversi (1 Kisa)</span>
                            <span class="text-slate-200 font-medium">Rp {{ number_format($rate, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Kotor (Gross)</span>
                            <span class="text-slate-200 font-medium">Rp
                                {{ number_format($this->grossAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Biaya Admin</span>
                            <span class="text-rose-400 font-medium">- Rp
                                {{ number_format($admin_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-800 pt-2 flex justify-between font-bold text-slate-100">
                            <span>Total Diterima (Net)</span>
                            <span class="text-emerald-400">Rp {{ number_format($this->netAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-brand-500 hover:bg-brand-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        <span wire:loading.remove>Kirim Pengajuan Pencairan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            </div>

            <!-- Catatan / Ketentuan -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-200 mb-3 border-b border-slate-800 pb-3">Syarat & Ketentuan
                        Penarikan</h2>
                    <ul class="space-y-2 text-xs text-slate-400 list-disc list-inside leading-relaxed">
                        <li>Minimal pencairan adalah <strong class="text-slate-200">{{ $min_kisa }} Kisa</strong>.
                        </li>
                        <li>Nilai konversi saat ini: <strong class="text-slate-200">1 Kisa = Rp
                                {{ number_format($rate, 0, ',', '.') }}</strong>.</li>
                        <li>Setiap pengajuan penarikan dikenakan biaya penanganan admin sebesar <strong
                                class="text-slate-200">Rp {{ number_format($admin_fee, 0, ',', '.') }}</strong>.</li>
                        <li>Pastikan nomor rekening atau e-wallet yang dimasukkan sudah benar dan aktif.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Penarikan -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl" x-data="{ openProofModal: false, selectedProof: '', openReasonModal: false, selectedReason: '' }">
            <h2 class="text-sm font-bold text-slate-200 mb-4">Riwayat Penarikan Saldo</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-300">
                    <thead class="bg-slate-800/60 text-slate-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Kisa</th>
                            <th class="p-3">Total Diterima</th>
                            <th class="p-3">Rekening</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Detail / Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($withdrawals as $item)
                            <tr>
                                <td class="p-3 text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-3 font-semibold text-amber-400">{{ number_format($item->kisa_amount) }}
                                    Kisa</td>
                                <td class="p-3 font-bold text-emerald-400">Rp
                                    {{ number_format($item->net_amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-slate-300">{{ strtoupper($item->bank_name) }} -
                                    {{ $item->account_number }} (a.n {{ $item->account_name }})</td>
                                <td class="p-3">
                                    @if ($item->status === 'pending')
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-[10px]">Menunggu</span>
                                    @elseif($item->status === 'approved')
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px]">Berhasil</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[10px]">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-slate-500">Belum ada riwayat penarikan
                                    saldo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $withdrawals->links() }}
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

    </div>
</div>
