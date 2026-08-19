<div class="max-w-md mx-auto flex flex-col gap-4 animate-fade-in">

    {{-- Header Atas Pratinjau --}}
    <div class="flex items-center justify-between bg-white border border-slate-100 p-4 rounded-2xl shadow-3xs mb-2">
        <div>
            <h3 class="text-xs font-black text-slate-800">{{ $chapterTitle }}</h3>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Format:
                {{ $type === 'chat' ? 'Chat Fic' : 'Novel Regular' }}</span>
        </div>
        <button type="button" wire:click="switchChapterAction('list')"
            class="text-[10px] font-black text-slate-500 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition">
            Kembali
        </button>
    </div>

    {{-- KONDISI 1: JIKA YANG DILIHAT ADALAH NOVEL REGULAR (TEXT) --}}
    @if ($type === 'regular')
        <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 shadow-xs">
            <div class="prose max-w-none text-xs leading-relaxed text-slate-600">
                {!! $content !!}
            </div>
        </div>

        {{-- KONDISI 2: JIKA YANG DILIHAT ADALAH CHAT FIC (VISUAL SMARTPHONE) --}}
    @else
        <div
            class="flex flex-col bg-slate-950 rounded-[2.5rem] border border-slate-800 shadow-xl overflow-hidden h-[550px]">

            {{-- Top Notch Simulasi Hp --}}
            <div class="bg-slate-900/40 border-b border-slate-900 py-2 text-center">
                <span class="text-[8px] font-black text-slate-600 uppercase tracking-widest">📱 Mode Membaca
                    Interaktif</span>
            </div>

            {{-- Kontainer Alur Obrolan --}}
            <div class="flex-1 p-5 overflow-y-auto flex flex-col gap-3.5 custom-scrollbar">
                @forelse ($chatRows as $row)
                    @php
                        // Cari karakter berdasarkan ID yang tersimpan di baris JSON
                        $char = !empty($row['character_id'])
                            ? $story->characters->firstWhere('id', $row['character_id'])
                            : null;
                        $charName = $char ? $char->name : 'Unknown';
                        $avatar =
                            $char && $char->avatar_path
                                ? asset('storage/' . $char->avatar_path)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($charName) . '&background=random';
                        $isLeft = ($row['position'] ?? 'left') === 'left';
                        $rowType = $row['type'] ?? 'chat';
                    @endphp

                    {{-- Render: Balon Chat --}}
                    @if ($rowType === 'chat')
                        <div
                            class="flex gap-2 items-end max-w-[75%] {{ $isLeft ? 'justify-start' : 'justify-end flex-row-reverse ml-auto' }}">
                            <img src="{{ $avatar }}"
                                class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">
                            <div class="flex flex-col {{ $isLeft ? 'items-start' : 'items-end' }}">
                                <span
                                    class="text-[7px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                <div
                                    class="p-2.5 rounded-xl text-[11px] font-semibold leading-normal tracking-wide shadow-sm
                                        {{ $isLeft ? 'bg-slate-900 text-slate-300 rounded-tl-none border border-slate-800/60' : 'bg-emerald-600 text-white rounded-tr-none' }}">
                                    {{ $row['message'] }}
                                </div>
                            </div>
                        </div>

                        {{-- Render: Panggilan Masuk / Keluar --}}
                    @elseif (in_array($rowType, ['call_incoming', 'call_outgoing']))
                        <div class="w-full flex justify-center my-0.5 animate-fade-in">
                            <div
                                class="flex items-center gap-2 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-xl text-[10px] text-slate-400 text-left min-w-[180px]">
                                <span
                                    class="text-xs {{ $rowType === 'call_incoming' ? 'text-emerald-400' : 'text-sky-400' }}">●</span>
                                <div>
                                    <p class="font-black text-slate-500 uppercase text-[6px] tracking-wider">
                                        {{ $rowType === 'call_incoming' ? '📳 Masuk' : '📞 Keluar' }}</p>
                                    <p class="font-bold text-slate-300">{{ $charName }} <span
                                            class="text-slate-600">({{ $row['duration'] ?: '00:00' }})</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif ($rowType === 'image')
                        <div
                            class="flex gap-2 items-end max-w-[75%] {{ $isLeft ? 'justify-start' : 'justify-end flex-row-reverse ml-auto' }}">
                            <img src="{{ $avatar }}"
                                class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">
                            <div class="flex flex-col {{ $isLeft ? 'items-start' : 'items-end' }}">
                                <span
                                    class="text-[7px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                <div
                                    class="p-1.5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm overflow-hidden {{ $isLeft ? 'rounded-tl-none' : 'rounded-tr-none' }}">
                                    <img src="{{ asset('storage/' . $row['image_path']) }}"
                                        class="w-full max-h-56 object-cover rounded-xl mb-1">
                                    @if (!empty($row['message']))
                                        <p class="text-[10px] font-semibold text-slate-300 px-1 py-0.5">
                                            {{ $row['message'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Render: Panggilan Tak Terjawab --}}
                    @elseif ($rowType === 'call_missed')
                        <div class="w-full flex justify-center my-0.5 animate-fade-in">
                            <div
                                class="flex items-center gap-2 bg-rose-950/20 border border-rose-900/30 px-3 py-1.5 rounded-xl text-[10px] text-rose-400 text-left min-w-[180px]">
                                <span class="text-xs text-rose-500">●</span>
                                <div>
                                    <p class="font-black text-rose-500 uppercase text-[6px] tracking-wider">🚫
                                        Tak Terjawab</p>
                                    <p class="font-bold text-slate-300">{{ $charName }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Render: Deskripsi Narasi Tengah --}}
                    @elseif ($rowType === 'description')
                        <p
                            class="text-[9px] font-medium text-slate-500 bg-slate-900/40 border border-slate-900 rounded-lg py-1 px-2.5 inline-block mx-auto text-center max-w-[85%]">
                            {{ $row['message'] }}
                        </p>
                    @endif

                @empty
                    <div class="text-center text-slate-600 text-[10px] font-bold py-32">
                        Bab ini belum memiliki konten skrip.
                    </div>
                @endforelse
            </div>

        </div>
    @endif

</div>
