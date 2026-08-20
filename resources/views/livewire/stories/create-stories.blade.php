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
         <div class="flex gap-4 items-center p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
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
 </main>
