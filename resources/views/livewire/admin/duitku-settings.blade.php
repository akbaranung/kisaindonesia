<div class="w-full mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Duitku Payment Gateway</h1>
        <p class="text-sm text-gray-500">Kelola kredensial API dan konfigurasi transaksi Duitku.</p>
    </div>

    <form wire:submit.prevent="save" class="border border-slate-800/80 bg-slate-900 p-6 rounded-xl shadow-sm space-y-6">

        {{-- Status Mode (Sandbox / Production) --}}
        <div class="p-4 rounded-lg bg-slate-900 border border-slate-800/80 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-slate-200">Environment Mode</h4>
                <p class="text-xs text-slate-400">Aktifkan mode Sandbox untuk testing, atau matikan untuk Production.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="isSandbox" class="sr-only peer">
                <div
                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600">
                </div>
                <span class="ml-3 text-sm font-semibold text-slate-400">
                    {{ $isSandbox ? 'Sandbox (Testing)' : 'Production (Live)' }}
                </span>
            </label>
        </div>

        {{-- Merchant Code --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-1">Merchant Code</label>
            <input type="text" wire:model="merchantCode" placeholder="Contoh: DS12345"
                class="w-full px-4 py-2 border border-slate-800/80 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none @error('merchantCode') border-red-500 @enderror">
            @error('merchantCode')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        {{-- API Key --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-1">API Key / Secret Key</label>
            <input type="password" wire:model="apiKey" placeholder="Masukkan API Key Duitku"
                class="w-full px-4 py-2 border border-slate-800/80 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none @error('apiKey') border-red-500 @enderror">
            @error('apiKey')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        {{-- Expiry Period --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-1">Masa Kadaluarsa Pembayaran (Menit)</label>
            <input type="number" wire:model="expiryPeriod" placeholder="60"
                class="w-full px-4 py-2 border border-slate-800/80 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none @error('expiryPeriod') border-red-500 @enderror">
            <p class="text-xs text-gray-400 mt-1">Default: 60 menit. Batas waktu bagi pengguna untuk menyelesaikan
                pembayaran.</p>
            @error('expiryPeriod')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="rounded-2xl shadow-sm mt-6">
            <h3 class="font-bold text-slate-200 text-lg mb-1">Metode Pembayaran Aktif (Real-time dari Duitku)</h3>
            <p class="text-xs text-slate-500 mb-4">Pilih metode pembayaran yang ingin kamu aktifkan untuk pengguna.</p>

            @if (count($apiPaymentMethods) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($apiPaymentMethods as $item)
                        <label
                            class="flex items-center justify-between p-3 border rounded-xl cursor-pointer bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" value="{{ $item['paymentMethod'] }}" wire:model="enabledMethods"
                                    class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <img src="{{ $item['paymentImage'] }}" alt="{{ $item['paymentName'] }}"
                                    class="h-6 object-contain">
                                <span class="text-sm font-semibold text-gray-800">{{ $item['paymentName'] }}</span>
                            </div>
                            <span class="text-xs text-gray-400">Fee: Rp {{ number_format($item['totalFee']) }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="p-4 bg-amber-50 text-amber-700 text-xs rounded-xl border border-amber-200">
                    Gagal mengambil data dari API Duitku. Pastikan Merchant Code dan API Key sudah diisi dengan benar.
                </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-lg transition">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
