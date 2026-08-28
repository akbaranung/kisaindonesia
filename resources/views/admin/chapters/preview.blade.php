<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Bab: {{ $chapter->title }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen py-10 px-4 antialiased">

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header Info -->
        <div
            class="p-6 bg-slate-900 border border-slate-800 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block mb-1">
                    {{ $chapter->story->title ?? 'Cerita' }}
                </span>
                <h1 class="text-xl font-bold text-slate-100">
                    Bab {{ $chapter->order_number }}: {{ $chapter->title }}
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Total Kata: <strong class="text-slate-200">{{ number_format($chapter->word_count) }}
                        {{ $chapter->type === 'chat' ? 'bubble' : 'kata' }}</strong>
                </p>
            </div>
            <div>
                <button onclick="window.close()"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl transition">
                    ✕ Tutup Tab
                </button>
            </div>
        </div>

        <!-- Isi Konten Bab -->
        <div
            class="p-8 bg-slate-900 border border-slate-800 rounded-2xl text-slate-200 text-sm leading-relaxed whitespace-pre-line shadow-xl">
            @if (in_array($chapter->type, ['regular', 'puisi']))
                @php
                    $regularContent = $chapter->parseJsonData();
                @endphp
                {!! $regularContent['content'] !!}
            @else
                <div class="flex-1 p-5 overflow-y-auto flex flex-col gap-3.5 custom-scrollbar">
                    @php
                        $content = $chapter->parseJsonData();
                        $chatRows = $content['bubbles'];
                        $story = $chapter->story;
                    @endphp

                    @forelse ($chatRows as $index => $row)
                        @php
                            $char = !empty($row['character_id'])
                                ? $story->characters->firstWhere('id', $row['character_id'])
                                : null;
                            $charName = $char ? $char->name : 'Unknown';
                            $avatar =
                                $char && $char->avatar_path
                                    ? asset('storage/' . $char->avatar_path)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($charName) . '&background=random';
                            $isRight = ($char['default_position'] ?? 'right') === 'right';
                            $type = $row['message_type'] ?? 'text';
                        @endphp

                        <div
                            class="group relative w-full flex items-center {{ $isRight ? 'justify-end' : 'justify-start' }}">

                            @if ($type === 'center_text')
                                <div
                                    class="my-3 px-4 py-2 bg-slate-200/60 backdrop-blur-xs rounded-2xl text-center max-w-[90%] mx-auto shadow-2xs border border-slate-300/40">
                                    <p class="text-xs font-semibold italic text-slate-600 leading-relaxed">
                                        {{ $row['message'] ?? ($row['center_text'] ?? '') }}
                                    </p>
                                </div>
                            @elseif($type === 'call')
                                @php
                                    $isMissed = $row['call_type'] === 'missed';
                                    $isOutgoing = $row['call_type'] === 'outgoing';
                                    $isIncoming = $row['call_type'] === 'incoming';
                                @endphp

                                <div class="flex items-center justify-center my-2">
                                    <div
                                        class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-xs font-bold shadow-2xs {{ $isMissed ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-slate-900 border-slate-800 text-white' }}">

                                        {{-- Icon Panggilan --}}
                                        @if ($isMissed)
                                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M16 8l-8 8m0-8l8 8M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        @elseif($isOutgoing)
                                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        @endif

                                        {{-- Teks & Durasi Telepon --}}
                                        <div class="flex items-center gap-1.5">
                                            <span>
                                                @if ($isMissed)
                                                    Panggilan Tak Terjawab
                                                @elseif($isOutgoing)
                                                    Panggilan Keluar
                                                @else
                                                    Panggilan Masuk
                                                @endif
                                            </span>

                                            @if (!empty($row['duration']))
                                                <span
                                                    class="opacity-60 text-[11px] font-medium">({{ $row['duration'] }})</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @elseif($type === 'image')
                                <div
                                    class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                    {{-- Avatar --}}
                                    <img src="{{ $avatar }}"
                                        class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                    {{-- Gelembung Pesan --}}
                                    <div
                                        class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                        <span
                                            class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                        @if (!$isRight && !empty($row['character_name']))
                                            <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                                {{ $row['character_name'] }}
                                            </span>
                                        @endif

                                        <div
                                            class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-amber-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                            @if (($row['message_type'] ?? 'text') === 'image' && !empty($row['image_url']))
                                                <img src="{{ asset('storage/' . $row['image_url']) }}" alt="Chat Image"
                                                    class="rounded-lg max-w-xs my-1 object-cover cursor-pointer hover:opacity-95 transition"
                                                    onclick="window.open(this.src, '_blank')">
                                            @endif
                                            @if (!empty($row['message']))
                                                <p class="text-[10px] font-semibold text-slate-300 px-1 py-0.5">
                                                    {{ $row['message'] }}</p>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @else
                                <div
                                    class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                    {{-- Avatar --}}
                                    <img src="{{ $avatar }}"
                                        class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                    {{-- Gelembung Pesan --}}
                                    <div
                                        class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                        <span
                                            class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                        @if (!$isRight && !empty($row['character_name']))
                                            <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                                {{ $row['character_name'] }}
                                            </span>
                                        @endif

                                        <div
                                            class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-amber-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                            <p class="whitespace-pre-line">{{ $row['message'] ?? '' }}</p>
                                        </div>
                                    </div>

                                </div>
                            @endif

                        </div>
                    @empty
                        <div
                            class="text-center text-slate-700 font-bold py-32 flex flex-col items-center justify-center gap-2">
                            <div class="text-xl">💬</div>
                            <p>Skrip kosong. Ketik pesan pertamamu di bar bawah, Bro!</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

</body>

</html>
