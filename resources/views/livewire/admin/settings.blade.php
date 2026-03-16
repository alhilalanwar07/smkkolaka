<div>
    <div class="flex justify-end mb-6">
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">+ Tambah Setting</button>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Key</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Value</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Type</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($settings as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 font-mono text-sm text-slate-800 dark:text-white">{{ $item->key }}</td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 truncate max-w-xs">{{ Str::limit($item->value, 60) }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $item->type }}</span></td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button wire:click="edit({{ $item->id }})" class="px-3 py-1 text-xs font-medium bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 transition">Edit</button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus?" class="px-3 py-1 text-xs font-medium bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada setting.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Setting</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Key *</label>
                    <input wire:model="key" type="text" placeholder="site_name" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-mono">
                    @error('key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type</label>
                    <select wire:model="type" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="string">String</option>
                        <option value="boolean">Boolean</option>
                        <option value="json">JSON</option>
                        <option value="image">Image Path</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Value</label>
                    <textarea wire:model="value" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-mono"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
