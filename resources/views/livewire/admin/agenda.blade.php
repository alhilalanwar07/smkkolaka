<div>
    <div class="flex items-center justify-between mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari agenda..." class="px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white w-64 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Agenda</button>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kegiatan</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Waktu</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Lokasi</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Peserta</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $item->nama_kegiatan }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $item->waktu_mulai->format('d M Y H:i') }} — {{ $item->waktu_selesai->format('H:i') }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->lokasi_pelaksanaan ?? '-' }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">{{ $item->kategori_peserta }}</span></td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button wire:click="edit({{ $item->id }})" class="px-3 py-1 text-xs font-medium bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 transition">Edit</button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus?" class="px-3 py-1 text-xs font-medium bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada agenda.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">{{ $items->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Agenda</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kegiatan *</label>
                    <input wire:model="nama_kegiatan" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('nama_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi_kegiatan" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Lokasi</label>
                    <input wire:model="lokasi_pelaksanaan" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai *</label>
                        <input wire:model="waktu_mulai" type="datetime-local" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('waktu_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai *</label>
                        <input wire:model="waktu_selesai" type="datetime-local" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('waktu_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori Peserta</label>
                    <select wire:model="kategori_peserta" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="umum">Umum</option>
                        <option value="siswa">Siswa</option>
                        <option value="staf">Staf</option>
                    </select>
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
