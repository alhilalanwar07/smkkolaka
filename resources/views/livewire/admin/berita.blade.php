<div>
    <div class="mb-6 flex gap-2">
        <button wire:click="$set('tab', 'berita')" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $tab === 'berita' ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">Berita</button>
        <button wire:click="$set('tab', 'kategori')" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $tab === 'kategori' ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">Kategori</button>
    </div>

    @if($tab === 'berita')
        <div class="mb-6 rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Manajemen Berita</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Kelola artikel dan kategori secara terpisah</h2>
                    <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">Penulisan artikel sekarang memakai halaman editor penuh agar proses tambah dan edit berita lebih fokus, nyaman, dan tidak bertabrakan dengan daftar data.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white sm:w-72">
                    <a href="{{ route('admin.berita.create') }}" class="rounded-2xl bg-blue-600 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-700">+ Tulis Berita Baru</a>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Judul</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Views</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($beritaList as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3">
                                <p class="max-w-xs truncate font-medium text-slate-800 dark:text-white">{{ $item->judul }}</p>
                                <p class="text-xs text-slate-400">{{ $item->created_at->format('d M Y') }} &middot; {{ $item->user->name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $item->status_publikasi === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : ($item->status_publikasi === 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400') }}">{{ $item->status_publikasi }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->view_count }}</td>
                            <td class="space-x-1 px-4 py-3 text-right">
                                <a href="{{ route('admin.berita.edit', $item->id) }}" class="rounded-lg bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700 transition hover:bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300">Edit</a>
                                <a href="{{ route('admin.berita.create', ['source' => $item->id]) }}" class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 transition hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300">Duplicate</a>
                                <button wire:click="deleteBerita({{ $item->id }})" wire:confirm="Yakin hapus berita ini?" class="rounded-lg bg-red-50 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-100 dark:bg-red-900 dark:text-red-300">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-700">{{ $beritaList->links() }}</div>
        </div>
    @else
        <div class="mb-4 flex justify-end">
            <button wire:click="createKategori" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">+ Tambah Kategori</button>
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Jumlah Berita</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($kategoriList as $kat)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $kat->nama_kategori }}</td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $kat->berita_count }}</td>
                            <td class="space-x-1 px-4 py-3 text-right">
                                <button wire:click="editKategori({{ $kat->id }})" class="rounded-lg bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700 transition hover:bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300">Edit</button>
                                <button wire:click="deleteKategori({{ $kat->id }})" wire:confirm="Yakin hapus?" class="rounded-lg bg-red-50 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-100 dark:bg-red-900 dark:text-red-300">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($showKategoriModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="mx-4 w-full max-w-sm rounded-xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-4 text-lg font-bold text-slate-800 dark:text-white">{{ $editKategoriId ? 'Edit' : 'Tambah' }} Kategori</h3>
                <form wire:submit="saveKategori" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Kategori *</label>
                        <input wire:model="nama_kategori" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        @error('nama_kategori') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showKategoriModal', false)" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">Batal</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>