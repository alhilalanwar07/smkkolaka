<div>
    @if(!$activeAlbum)
    {{-- Album List --}}
    <div class="flex items-center justify-between mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari album..." class="px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white w-64 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <button wire:click="createAlbum" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Album</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($albums as $album)
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">{{ $album->judul_album }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ $album->items_count }} item &middot; {{ $album->tanggal_kegiatan?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="flex gap-1">
                    <button wire:click="editAlbum({{ $album->id }})" class="p-1 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900 rounded transition text-xs">Edit</button>
                    <button wire:click="deleteAlbum({{ $album->id }})" wire:confirm="Yakin hapus album ini?" class="p-1 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 rounded transition text-xs">Hapus</button>
                </div>
            </div>
            @if($album->deskripsi_singkat)
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $album->deskripsi_singkat }}</p>
            @endif
            <button wire:click="openAlbum({{ $album->id }})" class="w-full py-2 text-sm font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Kelola Item →</button>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400">Belum ada album.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $albums->links() }}</div>

    @else
    {{-- Album Items --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <button wire:click="backToAlbums" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $activeAlbum->judul_album }}</h3>
        </div>
        <button wire:click="addItem" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Item</button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($activeAlbum->items as $item)
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden group">
            @if($item->tipe_file === 'foto')
                <img src="{{ Storage::url($item->file_path) }}" class="w-full h-40 object-cover" alt="{{ $item->caption }}">
            @else
                <div class="w-full h-40 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            @endif
            <div class="p-3">
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $item->caption ?? 'Tanpa caption' }}</p>
                <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Hapus item ini?" class="mt-1 text-xs text-red-500 hover:text-red-700 transition">Hapus</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400">Belum ada item.</div>
        @endforelse
    </div>
    @endif

    {{-- Album Modal --}}
    @if($showAlbumModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editAlbumId ? 'Edit' : 'Tambah' }} Album</h3>
            <form wire:submit="saveAlbum" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Album *</label>
                    <input wire:model="judul_album" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('judul_album') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi_singkat" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Kegiatan</label>
                    <input wire:model="tanggal_kegiatan" type="date" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="$set('showAlbumModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Item Modal --}}
    @if($showItemModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Tambah Item</h3>
            <form wire:submit="saveItem" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe</label>
                    <select wire:model.live="tipe_file" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="foto">Foto</option>
                        <option value="video_url">Video URL</option>
                    </select>
                </div>
                @if($tipe_file === 'foto')
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Foto *</label>
                    <input wire:model="file_path" type="file" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300">
                    @error('file_path') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Video URL *</label>
                    <input wire:model="video_url" type="url" placeholder="https://youtube.com/..." class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('video_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Caption</label>
                    <input wire:model="caption" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="$set('showItemModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Upload</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
