<div class="space-y-6">
    @php($periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [])

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Panitia Tes PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Halaman penilaian cepat untuk panitia tes</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Fokus halaman ini adalah input nilai. Daftar peserta di kiri, detail dan form penilaian di kanan, sehingga panitia bisa memproses antrean tanpa membuka modal review administrasi.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="period" class="min-w-[280px] rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                @foreach($availablePeriods as $periodOption)
                    <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.ppdb', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Ringkasan PPDB</a>
            <a href="{{ route('admin.ppdb.applicants', $periodQuery) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Data Pendaftar</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Antrean Tes</p>
            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Sudah Dinilai</p>
            <p class="mt-3 text-3xl font-black text-blue-600">{{ $summary['scored'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Siap Seleksi</p>
            <p class="mt-3 text-3xl font-black text-emerald-600">{{ $summary['ready'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Perlu Revisi</p>
            <p class="mt-3 text-3xl font-black text-amber-600">{{ $summary['needs_revision'] }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="grid grid-cols-1 gap-3 xl:grid-cols-[1.4fr_repeat(2,minmax(0,1fr))]">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, nomor pendaftaran, atau sekolah..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
            <select wire:model.live="trackFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua jalur</option>
                @foreach($tracks as $track)
                    <option value="{{ $track->id }}">{{ $track->nama_jalur }}</option>
                @endforeach
            </select>
            <select wire:model.live="scoreStatusFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua status nilai</option>
                <option value="unscored">Belum dinilai</option>
                <option value="scored">Sudah dinilai</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.1fr_1.2fr]">
        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Antrean Peserta</p>
                    <h3 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Pilih peserta untuk dinilai</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($candidates as $candidate)
                        <button wire:key="candidate-{{ $candidate->id }}" wire:click="openCandidate({{ $candidate->id }})" class="flex w-full items-start justify-between gap-4 px-5 py-4 text-left transition hover:bg-slate-50 dark:hover:bg-slate-800/40 {{ $selectedId === $candidate->id ? 'bg-blue-50 dark:bg-blue-950/30' : '' }}">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $candidate->nama_lengkap }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $candidate->nomor_pendaftaran }} · {{ $candidate->asal_sekolah }}</p>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $candidate->track->nama_jalur }} · {{ $candidate->pilihanProgram1->nama_jurusan }}</p>
                            </div>
                            <div class="text-right">
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $candidate->scored_at ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">{{ $candidate->scored_at ? 'Sudah dinilai' : 'Belum dinilai' }}</span>
                                <p class="mt-2 text-xs text-slate-400">{{ str($candidate->status_pendaftaran)->replace('_', ' ')->title() }}</p>
                            </div>
                        </button>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada peserta yang sesuai filter.</div>
                    @endforelse
                </div>

                <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $candidates->links() }}</div>
            </div>
        </div>

        <div>
            @if($selectedApplication)
                <div class="sticky top-24 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Peserta Dipilih</p>
                        <h3 class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ $selectedApplication->nama_lengkap }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $selectedApplication->nomor_pendaftaran }} · {{ $selectedApplication->asal_sekolah }}</p>

                        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm dark:bg-slate-800/60">
                                <p class="font-semibold text-slate-700 dark:text-slate-200">Jalur</p>
                                <p class="mt-1 text-slate-500 dark:text-slate-400">{{ $selectedApplication->track->nama_jalur }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm dark:bg-slate-800/60">
                                <p class="font-semibold text-slate-700 dark:text-slate-200">Pilihan 1</p>
                                <p class="mt-1 text-slate-500 dark:text-slate-400">{{ $selectedApplication->pilihanProgram1->nama_jurusan }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Dokumen</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek kelengkapan sebelum menyimpan nilai.</p>
                            </div>
                            <a href="{{ route('admin.ppdb.applicants') }}" class="text-sm font-bold text-blue-600">Buka review penuh</a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($selectedApplication->documents as $document)
                                <div wire:key="test-document-{{ $document->id }}" class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
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

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Form Penilaian</p>
                        <form wire:submit="saveScoring" class="mt-4 space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Pendaftaran</label>
                                    <select wire:model="scoreStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                        <option value="under_review">Under Review</option>
                                        <option value="needs_revision">Needs Revision</option>
                                        <option value="verified">Verified</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Berkas</label>
                                    <select wire:model="scoreBerkasStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
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
                                    <input wire:model="scoreAkademik" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Prestasi</label>
                                    <input wire:model="scorePrestasi" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Afirmasi</label>
                                    <input wire:model="scoreAfirmasi" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Tes Dasar</label>
                                    <input wire:model="scoreTesDasar" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Wawancara</label>
                                    <input wire:model="scoreWawancara" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Skor Berkas</label>
                                    <input wire:model="scoreBerkas" type="number" step="0.01" min="0" max="100" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Catatan Panitia Tes</label>
                                <textarea wire:model="scoreNote" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                            </div>

                            <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-400">
                                <span>Skor seleksi terakhir: {{ number_format((float) ($selectedApplication->skor_seleksi ?? 0), 2) }}</span>
                                <span>Dinilai: {{ $selectedApplication->scored_at?->translatedFormat('d M Y H:i') ?? 'Belum ada' }}</span>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Nilai Peserta</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                    Pilih peserta dari daftar antrean untuk mulai input penilaian.
                </div>
            @endif
        </div>
    </div>
</div>