<div class="p-6 bg-slate-900 rounded-xl border border-slate-800 text-slate-200 max-w-2xl">
    <h2 class="text-lg font-bold mb-1">Pengaturan Akses Menu</h2>
    <p class="text-xs text-slate-400 mb-6">Atur menu mana saja yang dapat diakses oleh {{ $user->name }}
        ({{ $user->email }})</p>

    @if (session()->has('success'))
        <div
            class="p-3 mb-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="saveAccess" class="space-y-4">
        <div class="space-y-2">
            @foreach ($menus as $menu)
                <label
                    class="flex items-center justify-between p-3 bg-slate-800/60 hover:bg-slate-800 rounded-lg cursor-pointer transition border border-slate-700/50">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="selectedMenus" value="{{ $menu->id }}"
                            class="rounded border-slate-600 bg-slate-900 text-brand-500 focus:ring-brand-500">
                        <div>
                            <div class="font-bold text-sm text-slate-200">{{ $menu->name }}</div>
                            <div class="text-[11px] text-slate-500">Route: {{ $menu->route_name }}</div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-lg transition disabled:opacity-50 flex items-center gap-2">
            <span wire:loading.remove>Simpan Perubahan</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>
</div>
