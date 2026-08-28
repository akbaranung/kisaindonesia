<div class="w-full mt-12 pt-8 border-t border-slate-100 max-w-2xl mx-auto px-3">
    <h3 class="text-lg font-bold text-slate-800 mb-6">
        Komentar
    </h3>

    {{-- ALERT MESSAGES --}}
    @if (session()->has('comment_status'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-semibold">
            {{ session('comment_status') }}
        </div>
    @endif

    {{-- FORM INPUT KOMENTAR UTAMA --}}
    @auth
        <form wire:submit.prevent="postComment" class="mb-8">
            <div class="flex gap-3">
                <div
                    class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-600 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <textarea wire:model="body" rows="3" placeholder="Tulis pendapat atau kesanmu tentang bab ini..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                    @error('body')
                        <span class="text-xs text-red-500 block mt-1">{{ $message }}</span>
                    @enderror

                    <div class="mt-2 flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                            Kirim Komentar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-center mb-8">
            <p class="text-xs text-slate-500 mb-2">Kamu harus login terlebih dahulu untuk menulis komentar.</p>
            <a href="{{ route('login') }}" class="text-xs font-bold text-indigo-600 hover:underline">Login Sekarang</a>
        </div>
    @endauth

    {{-- DAFTAR KOMENTAR --}}
    <div class="space-y-6 divide-y divide-slate-100">
        @forelse($comments as $comment)
            <div class="pt-4 first:pt-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-xs text-indigo-600 flex-shrink-0">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ $comment->user->name }}</span>
                                <span
                                    class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $comment->body }}</p>

                            {{-- Akses Balas --}}
                            @auth
                                <button wire:click="setReply({{ $comment->id }})"
                                    class="text-[11px] font-semibold text-indigo-600 hover:underline mt-2">
                                    {{ $replyToId === $comment->id ? 'Batal' : 'Balas' }}
                                </button>
                            @endauth
                        </div>
                    </div>

                    @if (auth()->check() && auth()->id() === $comment->user_id)
                        <button wire:click="deleteComment({{ $comment->id }})"
                            class="text-[11px] text-red-400 hover:text-red-600 font-medium">
                            Hapus
                        </button>
                    @endif
                </div>

                {{-- FORM BALAS KOMENTAR --}}
                @if ($replyToId === $comment->id)
                    <div class="ml-11 mt-3">
                        <form wire:submit.prevent="postReply({{ $comment->id }})">
                            <textarea wire:model="replyBody" rows="2" placeholder="Balas komentar ini..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                            @error('replyBody')
                                <span class="text-xs text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                            <div class="mt-2 flex justify-end gap-2">
                                <button type="button" wire:click="setReply({{ $comment->id }})"
                                    class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">Batal</button>
                                <button type="submit"
                                    class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold">Kirim
                                    Balasan</button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- DAFTAR BALASAN (REPLIES) --}}
                @if ($comment->replies->count() > 0)
                    <div class="ml-11 mt-3 space-y-3 pl-3 border-l-2 border-slate-100">
                        @foreach ($comment->replies as $reply)
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-2.5">
                                    <div
                                        class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center font-bold text-[10px] text-slate-500 flex-shrink-0">
                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-xs font-bold text-slate-800">{{ $reply->user->name }}</span>
                                            <span
                                                class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $reply->body }}
                                        </p>
                                    </div>
                                </div>
                                @if (auth()->check() && auth()->id() === $reply->user_id)
                                    <button wire:click="deleteComment({{ $reply->id }})"
                                        class="text-[10px] text-red-400 hover:text-red-600">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-xs text-slate-400 text-center py-4">Belum ada komentar. Jadi yang pertama berkomentar!</p>
        @endforelse
    </div>
</div>
