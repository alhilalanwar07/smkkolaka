<div>
    <div class="flex items-center justify-between mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pengumuman..." class="px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white w-64 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Pengumuman</button>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Judul</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Periode Tampil</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Lampiran</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800 dark:text-white truncate max-w-xs">{{ $item->judul_pengumuman }}</p>
                        <p class="text-xs text-slate-400">{{ $item->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">
                        {{ $item->tanggal_mulai_tampil?->format('d/m/Y') ?? '-' }} — {{ $item->tanggal_akhir_tampil?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->file_lampiran_path ? '📎' : '-' }}</td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button wire:click="edit({{ $item->id }})" class="px-3 py-1 text-xs font-medium bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 transition">Edit</button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus?" class="px-3 py-1 text-xs font-medium bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $items->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm overflow-y-auto py-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Pengumuman</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul *</label>
                    <input wire:model="judul_pengumuman" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('judul_pengumuman') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Isi Pengumuman *</label>
                    <textarea wire:model="isi_pengumuman" rows="5" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                    @error('isi_pengumuman') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mulai Tampil</label>
                        <input wire:model="tanggal_mulai_tampil" type="date" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Akhir Tampil</label>
                        <input wire:model="tanggal_akhir_tampil" type="date" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">File Lampiran</label>
                    <input wire:model="file_lampiran" type="file" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
