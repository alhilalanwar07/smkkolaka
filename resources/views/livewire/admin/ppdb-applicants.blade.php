<div class="space-y-6">
    @php($periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [])

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Data Pendaftar PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Verifikasi dan keputusan berkas pendaftar</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Halaman ini dikhususkan untuk verifikasi administrasi, review dokumen, dan keputusan manual hasil seleksi. Proses daftar ulang dipisah ke menu khusus agar alur panitia lebih rapi.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="period" class="min-w-[280px] rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                @foreach($availablePeriods as $periodOption)
                    <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.ppdb', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Kembali ke ringkasan</a>
            <a href="{{ route('admin.ppdb.tests', $periodQuery) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Buka panitia tes</a>
            <a href="{{ route('admin.ppdb.re-registration', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Verifikasi daftar ulang</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Pendaftar</p>
            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $summary['pendaftar'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Butuh Review</p>
            <p class="mt-3 text-3xl font-black text-amber-600">{{ $summary['review'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Lulus</p>
            <p class="mt-3 text-3xl font-black text-emerald-600">{{ $summary['passed'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Cadangan</p>
            <p class="mt-3 text-3xl font-black text-orange-600">{{ $summary['reserve'] }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="grid grid-cols-1 gap-3 xl:grid-cols-[1.6fr_repeat(3,minmax(0,1fr))]">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, nomor pendaftaran, atau sekolah..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
            <select wire:model.live="statusFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua status pendaftaran</option>
                <option value="submitted">Submitted</option>
                <option value="under_review">Under Review</option>
                <option value="needs_revision">Needs Revision</option>
                <option value="verified">Verified</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>
            <select wire:model.live="trackFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua jalur</option>
                @foreach($tracks as $track)
                    <option value="{{ $track->id }}">{{ $track->nama_jalur }}</option>
                @endforeach
            </select>
            <select wire:model.live="selectionFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua hasil seleksi</option>
                <option value="pending">Pending</option>
                <option value="passed">Lulus</option>
                <option value="reserve">Cadangan</option>
                <option value="failed">Tidak lulus</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.7fr_1fr]">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Pendaftar</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Jalur / Pilihan 1</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Skor / Ranking</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Hasil Seleksi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($applications as $application)
                            <tr wire:key="application-row-{{ $application->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                <td class="px-4 py-3 align-top">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $application->nama_lengkap }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $application->nomor_pendaftaran }} · {{ $application->asal_sekolah }}</p>
                                </td>
                                <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                    <p>{{ $application->track->nama_jalur }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $application->pilihanProgram1->nama_jurusan }}</p>
                                </td>
                                <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ number_format((float) ($application->skor_seleksi ?? 0), 2) }}</p>
                                    <p class="mt-1 text-xs text-slate-400">Jalur #{{ $application->ranking_jalur ?? '-' }} · Program #{{ $application->ranking_program ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ match ($application->hasil_seleksi) {
                                        'passed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                        'reserve' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                                        'failed' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                    } }}">{{ str($application->hasil_seleksi)->replace('_', ' ')->title() }}</span>
                                    <p class="mt-2 text-xs text-slate-400">{{ $application->programDiterima?->nama_jurusan ?? 'Belum ada program final' }}</p>
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ in_array($application->status_pendaftaran, ['accepted', 'verified'], true) ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : ($application->status_pendaftaran === 'needs_revision' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' : ($application->status_pendaftaran === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300')) }}">
                                        {{ str($application->status_pendaftaran)->replace('_', ' ')->title() }}
                                    </span>
                                    <p class="mt-2 text-xs text-slate-400">Berkas: {{ str($application->status_berkas)->replace('_', ' ')->title() }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="openReview({{ $application->id }})" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Review</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-400">Belum ada data pendaftar yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $applications->links() }}</div>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Periode Aktif</p>
                <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $activePeriod?->nama_periode ?? 'Belum ada' }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $activePeriod?->tahun_ajaran ?? '-' }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Kuota Aktif</p>
                <div class="mt-4 space-y-3">
                    @forelse($quotaOverview as $quota)
                        <div wire:key="side-quota-{{ $quota->id }}" class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $quota->track->nama_jalur }}</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $quota->programKeahlian->nama_jurusan }}</p>
                                </div>
                                <span class="text-sm font-black text-slate-900 dark:text-white">{{ $quota->kuota_terisi }}/{{ $quota->kuota }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada kuota aktif.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if($showReviewModal && $selectedApplication)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/60 py-8 backdrop-blur-sm">
            <div class="mx-4 w-full max-w-5xl space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Review Pendaftar PPDB</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $selectedApplication->nama_lengkap }} · {{ $selectedApplication->nomor_pendaftaran }}</p>
                    </div>
                    <button wire:click="$set('showReviewModal', false)" class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm dark:bg-slate-800">Tutup</button>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="space-y-4 rounded-2xl bg-slate-50 p-5 text-sm dark:bg-slate-800/60">
                        <p><span class="font-semibold">Jalur:</span> {{ $selectedApplication->track->nama_jalur }}</p>
                        <p><span class="font-semibold">Pilihan 1:</span> {{ $selectedApplication->pilihanProgram1->nama_jurusan }}</p>
                        <p><span class="font-semibold">Pilihan 2:</span> {{ $selectedApplication->pilihanProgram2->nama_jurusan ?? '-' }}</p>
                        <p><span class="font-semibold">Program Diterima:</span> {{ $selectedApplication->programDiterima->nama_jurusan ?? '-' }}</p>
                        <p><span class="font-semibold">Skor Seleksi:</span> {{ number_format((float) ($selectedApplication->skor_seleksi ?? 0), 2) }}</p>
                        <p><span class="font-semibold">Ranking:</span> Jalur #{{ $selectedApplication->ranking_jalur ?? '-' }} / Program #{{ $selectedApplication->ranking_program ?? '-' }}</p>
                        <p><span class="font-semibold">No HP:</span> {{ $selectedApplication->nomor_hp }}</p>
                        <p><span class="font-semibold">Orang Tua:</span> {{ $selectedApplication->nama_ayah ?: '-' }} / {{ $selectedApplication->nama_ibu ?: '-' }}</p>
                        <p><span class="font-semibold">Alamat:</span> {{ $selectedApplication->alamat_lengkap }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800/60">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Dokumen</p>
                        <div class="space-y-3">
                            @forelse($selectedApplication->documents as $document)
                                <div wire:key="document-{{ $document->id }}" class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $document->jenis_dokumen }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ str($document->status_verifikasi)->title() }}</p>
                                    </div>
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-sm font-bold text-blue-600">Lihat</a>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada dokumen terunggah.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-slate-700 dark:border-blue-900 dark:bg-blue-950/30 dark:text-slate-200">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Hasil Tahap 2</p>
                        <p><span class="font-semibold">Hasil Seleksi:</span> {{ str($selectedApplication->hasil_seleksi)->replace('_', ' ')->title() }}</p>
                        <p class="mt-2 leading-relaxed">{{ $selectedApplication->selection_notes ?: 'Belum ada catatan hasil seleksi.' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-200">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Finalisasi</p>
                        <p><span class="font-semibold">Status Daftar Ulang:</span> {{ str($selectedApplication->status_daftar_ulang)->replace('_', ' ')->title() }}</p>
                        <p class="mt-2"><span class="font-semibold">Waktu Konfirmasi:</span> {{ $selectedApplication->daftar_ulang_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                        <a href="{{ route('admin.ppdb.re-registration') }}" class="mt-3 inline-flex text-sm font-bold text-blue-600 transition hover:text-blue-700">Buka halaman verifikasi daftar ulang</a>
                    </div>
                </div>

                <form wire:submit="saveReview" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Pendaftaran</label>
                            <select wire:model="reviewStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="needs_revision">Needs Revision</option>
                                <option value="verified">Verified</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Berkas</label>
                            <select wire:model="reviewBerkasStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                <option value="pending">Pending</option>
                                <option value="incomplete">Incomplete</option>
                                <option value="complete">Complete</option>
                                <option value="revision">Revision</option>
                                <option value="verified">Verified</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Akademik</label>
                            <input wire:model="reviewSkorAkademik" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Prestasi</label>
                            <input wire:model="reviewSkorPrestasi" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Afirmasi</label>
                            <input wire:model="reviewSkorAfirmasi" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Tes Dasar</label>
                            <input wire:model="reviewSkorTesDasar" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Wawancara</label>
                            <input wire:model="reviewSkorWawancara" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Berkas</label>
                            <input wire:model="reviewSkorBerkas" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Catatan Verifikator</label>
                        <textarea wire:model="reviewNote" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showReviewModal', false)" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Review</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>