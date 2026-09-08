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

        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-lg transition">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
