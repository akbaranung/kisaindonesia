<div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-1 gap-6 items-start animate-fade-in">
    <div class="md:col-span-1 space-y-4">
        <div class="bg-white border border-slate-100 rounded-[2rem] p-5 shadow-xs space-y-4">
            <h3 class="text-xs font-black text-slate-800">⚙️ Pengaturan Bab</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Judul
                        Bab</label>
                    <input type="text" wire:model="chapterTitle" placeholder="Bab 1"
                        class="w-full p-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                    @error('chapterTitle')
                        <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Tipe</label>
                    <select wire:model.live="type"
                        class="w-full p-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                        <option value="regular">📝 Novel Regular</option>
                        <option value="chat">💬 Chat Fic</option>
                    </select>
                    @error('type')
                        <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label
                        class="block text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Status</label>
                    <select wire:model="status"
                        class="w-full p-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                        <option value="draft">📂 Draft</option>
                        <option value="published">🚀 Terbit</option>
                    </select>
                    @error('status')
                        <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- PANEL DATABASE TOKOH CEPAT --}}
        @if ($type === 'chat')
            <div class="bg-white border border-slate-100 rounded-[2rem] p-5 shadow-xs space-y-3">
                <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">👥 Tokoh Terdaftar
                </h4>
                <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                    @foreach ($story->characters as $char)
                        <span
                            class="text-[9px] font-bold bg-slate-50 text-slate-600 border border-slate-100 px-2 py-1 rounded-lg">{{ $char->name }}</span>
                    @endforeach
                </div>
                <div class="flex gap-1.5 border-t border-slate-50 pt-2.5">
                    <input type="text" wire:model="charName" placeholder="Nama Tokoh..."
                        class="p-2 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-bold text-slate-700 focus:outline-none flex-1">
                    <button type="button" wire:click="addCharacter"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs px-3 rounded-xl transition">+</button>
                </div>
            </div>
        @endif
    </div>


    @if ($type === 'regular')
        <div class="space-y-2 animate-fade-in">
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider px-1">Isi
                Cerita Novel</label>
            <div wire:ignore class="rounded-2xl overflow-hidden border border-slate-100">
                <div id="quill-editor" style="min-height: 350px;"></div>
            </div>
            @error('content')
                <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
            @enderror
        </div>
    @else
        <div class="md:col-span-1 w-full">
            <div
                class="flex flex-col bg-slate-950 rounded-[2.5rem] border border-slate-800 shadow-xl overflow-hidden h-[580px]">

                <div
                    class="bg-slate-900/80 backdrop-blur-md px-6 py-3 border-b border-slate-800/60 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black text-slate-200">{{ $title ?: 'Judul Bab' }}</h4>
                        <span class="text-[8px] font-black text-slate-600 uppercase tracking-wider">Studio
                            Kerja Kreatif</span>
                    </div>
                    <button type="button" wire:click="switchChapterAction('list')"
                        class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-3 py-1 rounded-lg">Keluar</button>
                </div>

                <div class="flex-1 p-5 overflow-y-auto flex flex-col gap-3.5 custom-scrollbar">
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
                            $isLeft = ($row['position'] ?? 'left') === 'left';
                            $rowType = $row['type'] ?? 'chat';
                        @endphp

                        <div
                            class="group relative w-full flex items-center {{ $isLeft ? 'justify-start' : 'justify-end' }}">

                            <div
                                class="absolute z-10 hidden group-hover:flex items-center gap-1 bg-slate-900 border border-slate-800 p-1 rounded-lg shadow-xl top-[-14px] {{ $isLeft ? 'left-10' : 'right-10' }} animate-fade-in">
                                <button type="button" wire:click="editRowFromPreview({{ $index }})"
                                    class="p-1 text-[8px] font-black text-sky-400 hover:bg-slate-800 rounded">EDIT</button>
                                <span class="text-slate-800 text-[8px]">•</span>
                                <button type="button" wire:click="removeRowFromPreview({{ $index }})"
                                    wire:confirm="Yakin ingin menghapus baris obrolan ini?"
                                    class="p-1 text-[8px] font-black text-rose-400 hover:bg-slate-800 rounded">HAPUS</button>
                            </div>

                            @if ($rowType === 'chat')
                                <div class="flex gap-2 items-end max-w-[75%] {{ $isLeft ? '' : 'flex-row-reverse' }}">
                                    <img src="{{ $avatar }}"
                                        class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">
                                    <div class="flex flex-col {{ $isLeft ? 'items-start' : 'items-end' }}">
                                        <span
                                            class="text-[7px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                        <div
                                            class="p-2.5 rounded-xl text-[11px] font-semibold leading-normal tracking-wide shadow-sm transition group-hover:border-slate-700 border border-transparent
                                                {{ $isLeft ? 'bg-slate-900 text-slate-300 rounded-tl-none' : 'bg-emerald-600 text-white rounded-tr-none' }}">
                                            {{ $row['message'] }}
                                        </div>
                                    </div>
                                </div>
                            @elseif ($rowType === 'image')
                                <div class="flex gap-2 items-end max-w-[65%] {{ $isLeft ? '' : 'flex-row-reverse' }}">
                                    <img src="{{ $avatar }}"
                                        class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">
                                    <div class="flex flex-col {{ $isLeft ? 'items-start' : 'items-end' }}">
                                        <span
                                            class="text-[7px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                        <div
                                            class="p-1.5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm overflow-hidden {{ $isLeft ? 'rounded-tl-none' : 'rounded-tr-none' }}">
                                            @if (isset($row['image_temp']) && is_object($row['image_temp']))
                                                <img src="{{ $row['image_temp']->temporaryUrl() }}"
                                                    class="w-full max-h-56 object-cover rounded-xl mb-1">
                                            @elseif(!empty($row['image_path']))
                                                <img src="{{ asset('storage/' . $row['image_path']) }}"
                                                    class="w-full max-h-56 object-cover rounded-xl mb-1">
                                            @endif

                                            @if (!empty($row['message']))
                                                <p class="text-[10px] font-semibold text-slate-300 px-1 py-0.5">
                                                    {{ $row['message'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif (in_array($rowType, ['call_incoming', 'call_outgoing']))
                                <div
                                    class="flex items-center gap-2 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-xl text-[10px] text-slate-400 text-left min-w-[180px] group-hover:border-slate-700 transition">
                                    <span
                                        class="text-xs {{ $rowType === 'call_incoming' ? 'text-emerald-400' : 'text-sky-400' }}">●</span>
                                    <div>
                                        <p class="font-black text-slate-500 uppercase text-[6px] tracking-wider">
                                            {{ $rowType === 'call_incoming' ? '📳 Panggilan Masuk' : '📞 Panggilan Keluar' }}
                                        </p>
                                        <p class="font-bold text-slate-300">{{ $charName }} <span
                                                class="text-slate-600">({{ $row['duration'] ?: '00:00' }})</span>
                                        </p>
                                    </div>
                                </div>
                            @elseif ($rowType === 'call_missed')
                                <div
                                    class="flex items-center gap-2 bg-rose-950/20 border border-rose-900/30 px-3 py-1.5 rounded-xl text-[10px] text-rose-400 text-left min-w-[180px] group-hover:border-rose-800 transition">
                                    <span class="text-xs text-rose-500">●</span>
                                    <div>
                                        <p class="font-black text-rose-500 uppercase text-[6px] tracking-wider">
                                            🚫 Tak Terjawab</p>
                                        <p class="font-bold text-slate-300">{{ $charName }}</p>
                                    </div>
                                </div>
                            @elseif ($rowType === 'description')
                                <p
                                    class="text-[9px] font-medium text-slate-500 bg-slate-900/40 border border-slate-900 rounded-lg py-1 px-2.5 inline-block mx-auto text-center max-w-[85%] group-hover:border-slate-800 transition">
                                    {{ $row['message'] }}
                                </p>
                            @endif

                        </div>
                    @empty
                        <div
                            class="text-center text-slate-700 text-[10px] font-bold py-32 flex flex-col items-center justify-center gap-2">
                            <div class="text-xl">💬</div>
                            <p>Skrip kosong. Ketik pesan pertamamu di bar bawah, Bro!</p>
                        </div>
                    @endforelse
                </div>

                <div class="bg-slate-900 p-4 border-t border-slate-800/80 shadow-2xl flex flex-col gap-3">
                    @if ($editingIndex !== null)
                        <div
                            class="flex items-center justify-between bg-sky-950/60 border border-sky-800/80 px-3 py-1.5 rounded-xl text-[10px]">
                            <span class="font-bold text-sky-300 flex items-center gap-1.5">
                                ✏️ Memperbarui Baris Ke-{{ $editingIndex + 1 }}
                            </span>
                            <button type="button" wire:click="cancelEdit"
                                class="font-black text-rose-400 hover:text-rose-300">
                                Batal Edit ✕
                            </button>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-2 items-center">
                        <select wire:model.live="inputType"
                            class="p-2 bg-slate-800 border border-slate-700 rounded-xl text-[11px] font-bold text-slate-200 focus:outline-none">
                            <option value="chat">Kirim Chat</option>
                            <option value="image">Kirim Gambar</option>
                            <option value="call_incoming">Panggilan Masuk</option>
                            <option value="call_outgoing">Panggilan Keluar</option>
                            <option value="call_missed">Tak Terjawab</option>
                            <option value="description">Narasi Tengah</option>
                        </select>

                        {{-- Dropdown Tokoh (Hilang otomatis jika memilih tipe narasi deskripsi) --}}
                        @if ($inputType !== 'description')
                            <select wire:model.live="inputCharacterId"
                                class="p-2 bg-slate-800 border border-slate-700 rounded-xl text-[11px] font-bold text-slate-200 focus:outline-none max-w-[150px]">
                                <option value="">-- Pilih Tokoh --</option>
                                @foreach ($story->characters as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>

                            {{-- Pilihan Sisi Semburan Balon Chat --}}
                            @if (in_array($inputType, ['chat', 'image']))
                                <select wire:model.live="inputPosition"
                                    class="p-2 bg-slate-800 border border-slate-700 rounded-xl text-[11px] font-black text-slate-300 focus:outline-none">
                                    <option value="left">⬅️ Kiri</option>
                                    <option value="right">Kanan ➡️</option>
                                </select>
                            @endif
                        @endif

                        {{-- Error Indicator ringkas --}}
                        @error('inputCharacterId')
                            <span
                                class="text-[10px] text-rose-400 font-bold px-1 animate-pulse">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-2 items-center">
                        <div class="flex-1">
                            @if (in_array($inputType, ['call_incoming', 'call_outgoing']))
                                <input type="text" wire:model="inputDuration"
                                    placeholder="Contoh Durasi: 02:40 atau Berjalan..."
                                    class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs font-medium text-slate-100 focus:outline-none focus:border-emerald-500">
                            @elseif ($inputType === 'call_missed')
                                <input type="text" disabled
                                    placeholder="Status Panggilan Tak Terjawab Aktif (Tinggal klik kirim)"
                                    class="w-full p-2.5 bg-slate-800/40 border border-slate-800 rounded-xl text-xs font-bold text-slate-600 italic">
                            @elseif ($inputType === 'image')
                                <div class="flex flex-col gap-2 flex-1">
                                    <div class="flex items-center gap-2">
                                        @if ($inputImage)
                                            <div class="relative group shrink-0">
                                                <img src="{{ $inputImage->temporaryUrl() }}"
                                                    class="w-12 h-12 object-cover rounded-xl border-2 border-emerald-500/50 shadow-md">

                                                {{-- Tombol Batal Pilih Gambar --}}
                                                <button type="button" wire:click="$set('inputImage', null)"
                                                    class="absolute -top-1.5 -right-1.5 bg-rose-500 hover:bg-rose-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-black shadow-lg transition">
                                                    ✕
                                                </button>
                                            </div>
                                        @elseif ($editingIndex !== null && isset($chatRows[$editingIndex]))
                                            @php $currentRow = $chatRows[$editingIndex]; @endphp
                                            <div class="relative group shrink-0">
                                                @if (isset($currentRow['image_temp']) && is_object($currentRow['image_temp']))
                                                    <img src="{{ $currentRow['image_temp']->temporaryUrl() }}"
                                                        class="w-12 h-12 object-cover rounded-xl border border-sky-500/50">
                                                @elseif (!empty($currentRow['image_path']))
                                                    <img src="{{ asset('storage/' . $currentRow['image_path']) }}"
                                                        class="w-12 h-12 object-cover rounded-xl border border-sky-500/50">
                                                @endif
                                                <span
                                                    class="absolute -bottom-1 -right-1 bg-sky-900 text-sky-200 text-[7px] px-1 rounded font-bold">Lama</span>
                                            </div>

                                        @endif
                                    </div>
                                    <input type="file" wire:model="inputImage" accept="image/*"
                                        class="w-full mb-1 p-1.5 bg-slate-800 border border-slate-700 rounded-xl text-xs font-medium text-slate-300 focus:outline-none file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600">
                                    <input type="text" wire:model="inputMessage"
                                        placeholder="Caption/Keterangan (opsional)..."
                                        class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs font-medium text-slate-100 focus:outline-none focus:border-emerald-500">
                                </div>
                            @else
                                <input type="text" wire:model="inputMessage"
                                    placeholder="{{ $inputType === 'description' ? 'Ketik teks narasi peristiwa tengah...' : 'Ketik kalimat obrolan tokoh...' }}"
                                    class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs font-medium text-slate-100 focus:outline-none focus:border-emerald-500">
                            @endif
                            @error('inputMessage')
                                <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                            @error('inputImage')
                                <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="button" wire:click="submitNewRow" wire:loading.attr="disabled"
                        class="bg-emerald-600 hover:bg-emerald-500 text-white text-center font-black text-xs px-6 py-2.5 rounded-xl transition shadow-md shrink-0 items-center gap-1.5">
                        <span wire:loading.remove wire:target="inputImage, submitNewRow"
                            class="text-cente">Kirim</span>
                        <span wire:loading wire:target="inputImage, submitNewRow"
                            class="animate-pulse">Proses...</span>
                    </button>
                </div>
            </div>

            @error('content')
                <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
            @enderror
    @endif
    <button type="button" wire:click="saveChapter"
        class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-black text-[10px] py-3 rounded-xl transition shadow-xs mt-3">
        Simpan Bab Utama ✨
    </button>
</div>
