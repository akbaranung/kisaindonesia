<div class="inline-flex items-center">
    @if ($status === 'published' || strtolower($status) === 'published')
        <button wire:click="setStatus('draft')" wire:loading.attr="disabled"
            onclick="confirm('Sembunyikan cerita ini dari publik?') || event.stopImmediatePropagation()"
            title="Klik untuk ubah ke Draft"
            class="px-2 py-0.5 text-[10px] rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-bold transition-all flex items-center gap-1.5 disabled:opacity-50">
            <span wire:loading.remove wire:target="setStatus('draft')">
                <span>Draft Cerita</span>
            </span>
            <span wire:loading wire:target="setStatus('draft')" class="flex items-center gap-1">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </span>
        </button>
    @else
        <button wire:click="setStatus('published')" wire:loading.attr="disabled" title="Klik untuk publikasikan"
            class="px-2 py-0.5 text-[10px] rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 disabled:opacity-50">
            <span wire:loading.remove wire:target="setStatus('published')">
                <span>Publish Cerita</span>
            </span>
            <span wire:loading wire:target="setStatus('published')" class="flex items-center gap-1">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </span>
        </button>
    @endif
</div>
