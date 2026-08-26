 <nav class="flex items-center justify-between w-full pb-5 border-b border-slate-100 mb-6">
     <button wire:click="switchAction('list')"
         class="text-slate-500 hover:text-slate-800 text-sm font-bold flex items-center gap-1">
         &larr; Batal
     </button>
     <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Buat Cerita Baru</h2>
     <div class="w-10"></div>
 </nav>

 <main class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-2xl shadow-slate-200/40">
     <form wire:submit.prevent="saveStory" class="flex flex-col gap-5">
         <div class="flex gap-4 items-center rounded-2xl">
             <div>
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Cover
                     Cerita (Opsional)</label>
                 <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">

                     {{-- Tampilkan Cover Baru yang sedang diupload ATAU Cover Lama yang sudah ada di DB --}}
                     @if ($cover)
                         <img src="{{ $cover->temporaryUrl() }}" class="w-16 h-20 object-cover rounded-xl shadow-md">
                     @elseif ($existingCover)
                         <img src="{{ asset('storage/' . $existingCover) }}"
                             class="w-16 h-20 object-cover rounded-xl shadow-md">
                     @else
                         <div
                             class="w-16 h-20 bg-slate-200 text-slate-400 rounded-xl flex items-center justify-center text-[10px] font-bold">
                             No Cover</div>
                     @endif

                     <div class="flex-1">
                         <input type="file" wire:model="cover"
                             class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-brand-600 file:transition-all">
                         <p class="text-[9px] text-slate-400 mt-1">Format: JPG, PNG (Max. 2MB)</p>
                     </div>
                 </div>
                 @error('cover')
                     <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
                 @enderror
             </div>
         </div>
         <div>
             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Judul
                 Novel / Cerita</label>
             <input type="text" wire:model="title" placeholder="Masukkan judul yang menarik..."
                 class="title w-full px-4 py-3.5 bg-slate-50 border @error('title') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-500 focus:bg-white transition-all shadow-2xs">
             @error('title')
                 <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
             @enderror
         </div>

         <div>
             <div class="flex items-center justify-between mb-1">
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Nama
                     Pena / Penulis *</label>
                 <div class="flex items-center">
                     @if ($pen_name_id)
                         <button type="button" wire:click="openEditPenNameModal"
                             class="p-2 bg-white/80 backdrop-blur-md rounded-xl shadow-sm text-amber-600 transition me-3">
                             <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                     d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                             </svg>
                         </button>
                     @endif
                     <button type="button" wire:click="openPenNameModal"
                         class="p-2 bg-white/80 backdrop-blur-md rounded-xl shadow-sm text-brand-600 transition">
                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                         </svg>

                     </button>
                 </div>

             </div>

             @if (session()->has('pen_name_success'))
                 <div class="mb-2 text-[11px] text-emerald-600 font-medium">
                     ✓ {{ session('pen_name_success') }}
                 </div>
             @endif

             <select wire:model="pen_name_id"
                 class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 focus:outline-none font-medium text-slate-700">
                 <option value="">-- Pilih Nama Pena --</option>
                 @foreach ($penNames as $penName)
                     <option value="{{ $penName->id }}">
                         {{ $penName->name }} {{ $penName->is_default ? '(Utama)' : '' }}
                     </option>
                 @endforeach
             </select>
             @error('pen_name_id')
                 <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>
             @enderror
         </div>

         <div>
             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Genre
                 / Kategori</label>
             <select wire:model="type"
                 class="select2 w-full px-4 py-3.5 bg-slate-50 border @error('genreId') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-500 focus:bg-white transition-all shadow-2xs text-slate-700">
                 <option value="">-- Pilih Type --</option>
                 <option value="novel" {{ $type === 'novel' ? 'selected' : '' }}>Novel</option>
                 <option value="puisi" {{ $type === 'puisi' ? 'selected' : '' }}>Puisi</option>
             </select>
             @error('genreId')
                 <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
             @enderror
         </div>

         <div>
             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Genre
                 / Kategori</label>
             <select wire:model="genreId"
                 class="select2 w-full px-4 py-3.5 bg-slate-50 border @error('genreId') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-500 focus:bg-white transition-all shadow-2xs text-slate-700">
                 <option value="">-- Pilih Genre --</option>
                 @foreach ($genres as $genre)
                     <option value="{{ $genre->id }}" {{ $genreId == $genre->id ? 'selected' : '' }}>
                         {{ $genre->name }}</option>
                 @endforeach
             </select>
             @error('genreId')
                 <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
             @enderror
         </div>
         <div>
             <label
                 class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1.5 px-1">Sinopsis</label>
             <textarea wire:model="synopsis" rows="5" placeholder="Tulis blurb atau sinopsis singkat cerita..."
                 class="w-full px-4 py-3.5 bg-slate-50 border @error('synopsis') border-rose-500 @else border-slate-100 @enderror rounded-2xl text-sm focus:outline-hidden focus:border-brand-500 focus:bg-white transition-all shadow-2xs resize-none"></textarea>
             @error('synopsis')
                 <span class="text-[10px] text-rose-500 mt-1 block font-bold px-1">{{ $message }}</span>
             @enderror
         </div>

         <button type="submit" wire:loading.attr="disabled"
             class="w-full bg-slate-900 hover:bg-brand-600 text-white font-bold text-sm py-4 rounded-2xl transition-all duration-300 shadow-lg mt-2 flex items-center justify-center gap-2">
             <span wire:loading.remove
                 wire:target="saveStory">{{ $storyId ? 'Simpan Perubahan Cerita' : 'Mulai Tulis Cerita' }}</span>
             <span wire:loading wire:target="saveStory">Sedang Menyimpan...</span>
         </button>
     </form>

     @if ($showPenNameModal)
         <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
             <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                 <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                     <h3 class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                         <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                         </svg>
                         Tambah Nama Pena Cepat
                     </h3>
                     <button type="button" wire:click="$set('showPenNameModal', false)"
                         class="text-slate-400 hover:text-slate-600">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M6 18L18 6M6 6l12 12" />
                         </svg>
                     </button>
                 </div>

                 <form wire:submit="saveQuickPenName" class="p-5 space-y-3.5">
                     <div>
                         <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pena *</label>
                         <input type="text" wire:model="new_pen_name" placeholder="Contoh: Kirana Senja"
                             class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                         @error('new_pen_name')
                             <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>
                         @enderror
                     </div>

                     <div>
                         <label class="block text-xs font-bold text-slate-700 mb-1">Bio Singkat (Opsional)</label>
                         <textarea wire:model="new_pen_bio" rows="2" placeholder="Spesialis genre romance & angst..."
                             class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                         @error('new_pen_bio')
                             <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>
                         @enderror
                     </div>

                     <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                         <button type="button" wire:click="$set('showPenNameModal', false)"
                             class="px-3.5 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-700">
                             Batal
                         </button>
                         <button type="submit"
                             class="px-4 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                             Simpan & Gunakan
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     @endif
 </main>
