<div>
    {{-- Tabs --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('tab', 'produk')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $tab === 'produk' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Produk</button>
        <button wire:click="$set('tab', 'kategori')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $tab === 'kategori' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Kategori</button>
    </div>

    @if($tab === 'produk')
    {{-- Produk Tab --}}
    <div class="flex items-center justify-between mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari produk..." class="px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white w-64 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <button wire:click="createProduk" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Produk</button>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Jurusan</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Harga</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($produkList as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $item->nama_produk_jasa }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->programKeahlian->nama_jurusan ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->harga_estimasi ? 'Rp ' . number_format($item->harga_estimasi, 0, ',', '.') : '-' }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">{{ $item->status_ketersediaan }}</span></td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button wire:click="editProduk({{ $item->id }})" class="px-3 py-1 text-xs font-medium bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 transition">Edit</button>
                        <button wire:click="deleteProduk({{ $item->id }})" wire:confirm="Yakin hapus produk ini?" class="px-3 py-1 text-xs font-medium bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $produkList->links() }}</div>
    </div>
    @else
    {{-- Kategori Tab --}}
    <div class="flex justify-end mb-4">
        <button wire:click="createKategori" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Kategori</button>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Jumlah Produk</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($kategoriList as $kat)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $kat->nama_kategori }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $kat->produk_count }}</td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button wire:click="editKategori({{ $kat->id }})" class="px-3 py-1 text-xs font-medium bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 transition">Edit</button>
                        <button wire:click="deleteKategori({{ $kat->id }})" wire:confirm="Yakin hapus kategori ini?" class="px-3 py-1 text-xs font-medium bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    {{-- Produk Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Produk TEFA</h3>
            <form wire:submit="saveProduk" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Produk/Jasa *</label>
                    <input wire:model="nama_produk_jasa" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('nama_produk_jasa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Program Keahlian *</label>
                        <select wire:model="program_keahlian_id" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($programList as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->nama_jurusan }}</option>
                            @endforeach
                        </select>
                        @error('program_keahlian_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori *</label>
                        <select wire:model="kategori_id" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Estimasi</label>
                        <input wire:model="harga_estimasi" type="number" step="0.01" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                        <select wire:model="status_ketersediaan" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="tersedia">Tersedia</option>
                            <option value="pre-order">Pre-Order</option>
                            <option value="arsip">Arsip</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gambar Utama</label>
                    <input wire:model="gambar_utama" type="file" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Kategori Modal --}}
    @if($showKategoriModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editKategoriId ? 'Edit' : 'Tambah' }} Kategori</h3>
            <form wire:submit="saveKategori" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kategori *</label>
                    <input wire:model="nama_kategori" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('nama_kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="$set('showKategoriModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
