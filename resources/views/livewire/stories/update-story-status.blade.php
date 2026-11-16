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
    @else
        {{-- Status 'draft' atau 'rejected' --}}
        @if ($canSubmit)
            <button type="button" wire:click="submitForReview"
                wire:confirm="Ajukan cerita ini untuk dipublikasikan? Admin akan memeriksa cerita Anda terlebih dahulu."
                class="text-[9px] px-2.5 py-1.5 rounded-xl font-black bg-slate-900 hover:bg-brand-600 dark:bg-brand-600 dark:hover:bg-brand-500 text-white transition shadow-xs cursor-pointer">
                {{ $story->status === 'rejected' ? '<i class="fa-solid fa-rotate"></i> Ajukan Publish Ulang' : '<i class="fa-regular fa-paper-plane"></i> Ajukan Publish' }}
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
