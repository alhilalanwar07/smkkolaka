<div class="space-y-6">
    @php($periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [])

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Pengaturan PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Kelola periode, jalur, dan kuota</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Semua konfigurasi strategis PPDB dipusatkan di sini agar dashboard utama tetap ringkas dan panitia bisa fokus pada tugas masing-masing.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="period" class="min-w-[320px] rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                @foreach($availablePeriods as $periodOption)
                    <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.ppdb', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Ringkasan PPDB</a>
            <a href="{{ route('admin.ppdb.tests', $periodQuery) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Panitia Tes</a>
        </div>
    </div>

    @if($activePeriod)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Periode</p>
                <p class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $activePeriod->nama_periode }}</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $activePeriod->tahun_ajaran }} · {{ $activePeriod->gelombang_label }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status Periode</p>
                <p class="mt-2 text-lg font-black {{ $activePeriod->is_active ? 'text-blue-600' : 'text-slate-900 dark:text-white' }}">{{ str($activePeriod->status)->title() }}{{ $activePeriod->is_active ? ' · Aktif Default' : '' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Kuota Aktif</p>
                <p class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $quotaOverview->count() }} kombinasi jalur-jurusan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.2fr_1fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Kontrol Periode</p>
                <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">Aktivasi dan navigasi antar gelombang</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pilih periode yang ingin dikelola, lalu jadikan aktif bila ingin dipakai sebagai default publik untuk pendaftaran baru.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <button wire:click="activateSelectedPeriod" type="button" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">Jadikan aktif default</button>
                    <span class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">Registrasi {{ $activePeriod->isRegistrationOpen() ? 'sedang dibuka' : 'tidak sedang dibuka' }}</span>
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-blue-200 bg-blue-50 p-5 dark:border-blue-900 dark:bg-blue-950/20">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">Rencana Tahun Berikutnya</p>
                <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">Buat periode baru dari template saat ini</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Gunakan form di bawah untuk menyiapkan gelombang baru atau tahun ajaran berikutnya sambil menyalin jalur dan kuota dari periode yang sedang dipilih.</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                <details class="group" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Buat Periode atau Gelombang Baru</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Siapkan tahun ajaran berikutnya atau gelombang lanjutan tanpa mengubah data periode lama.</p>
                        </div>
                        <span class="text-slate-400 transition group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6">
                        <form wire:submit="createPeriod" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="xl:col-span-2">
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Periode</label>
                                    <input wire:model="newPeriodForm.nama_periode" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('newPeriodForm.nama_periode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Ajaran</label>
                                    <input wire:model="newPeriodForm.tahun_ajaran" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('newPeriodForm.tahun_ajaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                                    <select wire:model="newPeriodForm.status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="closed">Closed</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Mulai</label>
                                    <input wire:model="newPeriodForm.tahun_mulai" type="number" min="2020" max="2100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('newPeriodForm.tahun_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Selesai</label>
                                    <input wire:model="newPeriodForm.tahun_selesai" type="number" min="2020" max="2101" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('newPeriodForm.tahun_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor Gelombang</label>
                                    <input wire:model="newPeriodForm.gelombang_ke" type="number" min="1" max="20" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Label Gelombang</label>
                                    <input wire:model="newPeriodForm.gelombang_label" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Mulai Pendaftaran</label>
                                    <input wire:model="newPeriodForm.tanggal_mulai_pendaftaran" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Selesai Pendaftaran</label>
                                    <input wire:model="newPeriodForm.tanggal_selesai_pendaftaran" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal Pengumuman</label>
                                    <input wire:model="newPeriodForm.tanggal_pengumuman" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Mulai Daftar Ulang</label>
                                    <input wire:model="newPeriodForm.tanggal_mulai_daftar_ulang" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Selesai Daftar Ulang</label>
                                    <input wire:model="newPeriodForm.tanggal_selesai_daftar_ulang" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi</label>
                                <textarea wire:model="newPeriodForm.deskripsi" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Salin jalur dan kuota dari periode terpilih</span>
                                    <input wire:model="newPeriodForm.clone_template" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </label>
                                <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Jadikan aktif default setelah dibuat</span>
                                    <input wire:model="newPeriodForm.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Buat Periode Baru</button>
                            </div>
                        </form>
                    </div>
                </details>

                <details class="group" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Periode dan Masa Pendaftaran</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Kelola jadwal pendaftaran, pengumuman, dan daftar ulang tanpa mengubah data peserta.</p>
                        </div>
                        <span class="text-slate-400 transition group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6">
                        <form wire:submit="savePeriodSettings" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="xl:col-span-2">
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Periode</label>
                                    <input wire:model="periodForm.nama_periode" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.nama_periode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Ajaran</label>
                                    <input wire:model="periodForm.tahun_ajaran" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tahun_ajaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Mulai</label>
                                    <input wire:model="periodForm.tahun_mulai" type="number" min="2020" max="2100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tahun_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Selesai</label>
                                    <input wire:model="periodForm.tahun_selesai" type="number" min="2020" max="2101" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tahun_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor Gelombang</label>
                                    <input wire:model="periodForm.gelombang_ke" type="number" min="1" max="20" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.gelombang_ke') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Label Gelombang</label>
                                    <input wire:model="periodForm.gelombang_label" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.gelombang_label') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Pengumuman</label>
                                    <select wire:model="periodForm.status_pengumuman" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                    @error('periodForm.status_pengumuman') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Periode</label>
                                    <select wire:model="periodForm.status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="closed">Closed</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Mulai Pendaftaran</label>
                                    <input wire:model="periodForm.tanggal_mulai_pendaftaran" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tanggal_mulai_pendaftaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Selesai Pendaftaran</label>
                                    <input wire:model="periodForm.tanggal_selesai_pendaftaran" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tanggal_selesai_pendaftaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal Pengumuman</label>
                                    <input wire:model="periodForm.tanggal_pengumuman" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tanggal_pengumuman') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Mulai Daftar Ulang</label>
                                    <input wire:model="periodForm.tanggal_mulai_daftar_ulang" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tanggal_mulai_daftar_ulang') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Selesai Daftar Ulang</label>
                                    <input wire:model="periodForm.tanggal_selesai_daftar_ulang" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    @error('periodForm.tanggal_selesai_daftar_ulang') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Catatan Pengumuman</label>
                                    <textarea wire:model="periodForm.catatan_pengumuman" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                                    @error('periodForm.catatan_pengumuman') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Deskripsi Periode</label>
                                    <textarea wire:model="periodForm.deskripsi" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                                    @error('periodForm.deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gunakan sebagai periode aktif default</span>
                                <input wire:model="periodForm.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            </label>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Pengaturan Periode</button>
                            </div>
                        </form>
                    </div>
                </details>

                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Jalur PPDB</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Atur jalur yang tampil, kebutuhan verifikasi, dan urutan tampilan di portal publik.</p>
                        </div>
                        <span class="text-slate-400 transition group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6">
                        <form wire:submit="saveTrackSettings" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
                                @foreach($activePeriod->tracks as $track)
                                    <div wire:key="track-setting-{{ $track->id }}" class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/40">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">{{ $track->slug }}</p>
                                            <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $track->nama_jalur }}</h3>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Urutan Tampil</label>
                                            <input wire:model="trackSettings.{{ $track->id }}.urutan" type="number" min="1" max="99" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-900">
                                            @error('trackSettings.' . $track->id . '.urutan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Tampilkan di portal</span>
                                            <input wire:model="trackSettings.{{ $track->id }}.status_tampil" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                        <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Butuh verifikasi khusus</span>
                                            <input wire:model="trackSettings.{{ $track->id }}.requires_verification" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Pengaturan Jalur</button>
                            </div>
                        </form>
                    </div>
                </details>

                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Kuota per Jurusan</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Perbarui kuota dan status aktif kombinasi jalur-jurusan yang sedang dipakai.</p>
                        </div>
                        <span class="text-slate-400 transition group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6">
                        <form wire:submit="saveQuotaSettings" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                                @foreach($activePeriod->quotas as $quota)
                                    <div wire:key="quota-setting-{{ $quota->id }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/40">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">{{ $quota->track->nama_jalur }}</p>
                                                <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $quota->programKeahlian->nama_jurusan }}</h3>
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Terisi {{ $quota->kuota_terisi }} dari {{ $quota->kuota }} kuota</p>
                                            </div>
                                            <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                                                Aktif
                                                <input wire:model="quotaSettings.{{ $quota->id }}.status_aktif" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            </label>
                                        </div>
                                        <div class="mt-4">
                                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Kuota</label>
                                            <input wire:model="quotaSettings.{{ $quota->id }}.kuota" type="number" min="0" max="500" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-900">
                                            @error('quotaSettings.' . $quota->id . '.kuota') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Pengaturan Kuota</button>
                            </div>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
            Belum ada periode PPDB aktif dan terpublikasi. Buat atau aktifkan periode terlebih dahulu agar pengaturan dapat dikelola di sini.
        </div>
    @endif
</div>