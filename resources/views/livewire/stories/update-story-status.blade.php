<div class="inline-flex items-center">
    @if ($story->status === 'published')
        <span
            class="text-[9px] px-2.5 py-1 rounded-xl font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
            <i class="fa-regular fa-circle-check"></i> PUBLISHED
        </span>
    @elseif ($story->status === 'pending_review')
        <span
            class="text-[9px] px-2.5 py-1 rounded-xl font-black bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
            <i class="fa-solid fa-hourglass-half"></i> REVIEW ADMIN
        </span>
    @elseif ($story->status === 'rejected')
        <span
            class="text-[9px] px-2.5 py-1 rounded-xl font-black bg-rose-500/10 text-rose-500 border border-rose-500/20"
            title="{{ $story->rejection_reason ? 'Alasan ditolak: ' . $story->rejection_reason : 'Pengajuan ditolak oleh admin secara permanen' }}">
            <i class="fa-solid fa-circle-xmark"></i> DITOLAK
        </span>
    @elseif ($story->status === 'revisi')
        @if ($canSubmit)
            <button type="button" wire:click="submitForReview"
                wire:confirm="Ajukan ulang cerita ini untuk dipublikasikan setelah melakukan revisi?"
                title="{{ $story->rejection_reason ? 'Catatan revisi: ' . $story->rejection_reason : 'Silakan ajukan ulang setelah revisi' }}"
                class="text-[9px] px-2.5 py-1.5 rounded-xl font-black bg-amber-600 hover:bg-amber-500 text-white transition shadow-xs cursor-pointer flex items-center gap-1">
                <i class="fa-solid fa-rotate"></i>
                <span>Ajukan Ulang Publish</span>
            </button>
        @else
            <button type="button" disabled
                title="Minimal 5 bab terbit untuk mengajukan ulang (Saat ini: {{ $chapterCount }}/5 Bab)"
                class="text-[9px] px-2.5 py-1.5 rounded-xl font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 cursor-not-allowed">
                <i class="fa-solid fa-triangle-exclamation"></i> Revisi ({{ $chapterCount }}/5 Bab)
            </button>
        @endif
    @else
        {{-- Status 'draft' --}}
        @if ($canSubmit)
            <button type="button" wire:click="submitForReview"
                wire:confirm="Ajukan cerita ini untuk dipublikasikan? Admin akan memeriksa cerita Anda terlebih dahulu."
                class="text-[9px] px-2.5 py-1.5 rounded-xl font-black bg-slate-900 hover:bg-brand-600 dark:bg-brand-600 dark:hover:bg-brand-500 text-white transition shadow-xs cursor-pointer flex items-center gap-1">
                <i class="fa-regular fa-paper-plane"></i>
                <span>Ajukan Publish</span>
            </button>
        @else
            <button type="button" disabled
                title="Minimal 5 bab untuk mengajukan publish (Saat ini: {{ $chapterCount }}/5 Bab)"
                class="text-[9px] px-2.5 py-1.5 rounded-xl font-bold bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700 cursor-not-allowed">
                <i class="fa-solid fa-lock"></i> Publish ({{ $chapterCount }}/5 Bab)
            </button>
        @endif
    @endif
</div>
