<div class="space-y-6">
    @php($periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [])

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Ringkasan PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Pusat kendali operasional PPDB</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Halaman ini dipakai untuk memantau kondisi periode aktif, progres seleksi, dan tindakan operasional harian. Grafik evaluasi dan pembacaan tren dipindahkan ke menu Analisa PPDB khusus superadmin.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:col-span-2 xl:col-span-5">
                <label class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Periode Kerja</label>
                <select wire:model.live="period" class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                    @foreach($availablePeriods as $periodOption)
                        <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.ppdb.applicants', $periodQuery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Verifikasi</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Data Pendaftar</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Review berkas, status, dan hasil seleksi.</p>
            </a>
            <a href="{{ route('admin.ppdb.tests', $periodQuery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Penilaian</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Panitia Tes</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Input nilai tes dasar, wawancara, dan berkas.</p>
            </a>
            <a href="{{ route('admin.ppdb.settings', $periodQuery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Konfigurasi</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Pengaturan PPDB</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Atur periode, jalur, dan kuota setiap jurusan.</p>
            </a>
            <a href="{{ route('admin.ppdb.re-registration', $periodQuery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Finalisasi</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Verifikasi Daftar Ulang</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Pisahkan verifikasi peserta lulus yang sudah konfirmasi hadir.</p>
            </a>
            <a href="{{ route('admin.ppdb.export', ['period_id' => $selectedPeriodId]) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Output</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Export Hasil</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Unduh rekap hasil seleksi dan daftar ulang periode aktif dalam format CSV.</p>
            </a>
            @if(auth()->user()?->hasRole('admin'))
                <a href="{{ route('admin.ppdb.analytics', $periodQuery) }}" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-left shadow-sm transition hover:border-rose-300 hover:shadow-md dark:border-rose-900/70 dark:bg-rose-950/30">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-500">Evaluasi</p>
                    <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Analisa PPDB</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Baca tren jangka pendek, menengah, dan panjang lintas gelombang atau tahun.</p>
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 xl:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Periode Dipilih</p>
            <h2 class="mt-3 text-xl font-black text-slate-900 dark:text-white">{{ $activePeriod?->nama_periode ?? 'Belum ada periode aktif' }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $activePeriod?->tahun_ajaran ?? 'Silakan siapkan periode PPDB terlebih dahulu.' }} @if($activePeriod?->gelombang_label) · {{ $activePeriod->gelombang_label }} @endif</p>
            @if($activePeriod)
                <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <div class="rounded-xl bg-slate-50 px-3 py-3 dark:bg-slate-800/60">
                        <p class="font-semibold text-slate-700 dark:text-slate-200">Pendaftaran</p>
                        <p class="mt-1">{{ $activePeriod->tanggal_mulai_pendaftaran?->translatedFormat('d M Y') ?? '-' }} s.d. {{ $activePeriod->tanggal_selesai_pendaftaran?->translatedFormat('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-3 py-3 dark:bg-slate-800/60">
                        <p class="font-semibold text-slate-700 dark:text-slate-200">Daftar Ulang</p>
                        <p class="mt-1">{{ $activePeriod->tanggal_mulai_daftar_ulang?->translatedFormat('d M Y') ?? '-' }} s.d. {{ $activePeriod->tanggal_selesai_daftar_ulang?->translatedFormat('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Pendaftar</p>
            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $summary['pendaftar'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Butuh Review</p>
            <p class="mt-3 text-3xl font-black text-amber-600">{{ $summary['review'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Sudah Dinilai</p>
            <p class="mt-3 text-3xl font-black text-blue-600">{{ $summary['scored'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Lulus / Cadangan</p>
            <p class="mt-3 text-3xl font-black text-emerald-600">{{ $summary['passed'] }} / {{ $summary['reserve'] }}</p>
        </div>
    </div>

    @if($activePeriod)
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.6fr_1fr]">
            <div class="rounded-2xl border {{ $activePeriod->isAnnouncementPublished() ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-950/30' : 'border-amber-200 bg-amber-50 dark:border-amber-900 dark:bg-amber-950/30' }} p-5">
                <p class="text-xs font-bold uppercase tracking-[0.25em] {{ $activePeriod->isAnnouncementPublished() ? 'text-emerald-600' : 'text-amber-600' }}">Pengumuman Resmi</p>
                <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $activePeriod->isAnnouncementPublished() ? 'Hasil sudah dipublikasikan' : 'Hasil belum dipublikasikan' }}</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $activePeriod->catatan_pengumuman ?: 'Publikasikan hasil resmi setelah proses verifikasi, penilaian, dan seleksi tahap 2 selesai.' }}</p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <button wire:click="runSelection" wire:loading.attr="disabled" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:opacity-60">
                        Proses Seleksi Tahap 2
                    </button>
                    @if(! $activePeriod->isAnnouncementPublished())
                        <button wire:click="publishAnnouncement" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                            Publikasikan Hasil Resmi
                        </button>
                    @else
                        <div class="rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm text-emerald-700 dark:border-emerald-900 dark:bg-slate-900 dark:text-emerald-300">
                            Dipublikasikan {{ $activePeriod->hasil_diumumkan_at?->translatedFormat('d M Y H:i') ?? '-' }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Daftar Ulang</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ $summary['re_registered'] }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Jumlah peserta yang sudah mengirim atau sudah diverifikasi pada tahap daftar ulang.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            @forelse($quotaOverview as $quota)
                <div wire:key="quota-overview-{{ $quota->id }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">{{ $quota->track->nama_jalur }}</p>
                            <h3 class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ $quota->programKeahlian->nama_jurusan }}</h3>
                        </div>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ $quota->kuota_terisi }}/{{ $quota->kuota }}</span>
                    </div>
                    <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-emerald-500" style="width: {{ $quota->kuota > 0 ? min(($quota->kuota_terisi / $quota->kuota) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 xl:col-span-3">
                    Belum ada konfigurasi kuota aktif untuk periode ini.
                </div>
            @endforelse
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Aktivitas Terbaru</p>
                    <h3 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Pendaftar terakhir masuk</h3>
                </div>
                <a href="{{ route('admin.ppdb.applicants', $periodQuery) }}" class="text-sm font-bold text-blue-600 transition hover:text-blue-700">Lihat semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/70">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Pendaftar</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Jalur</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Status</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Skor</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentApplications as $application)
                            <tr wire:key="recent-application-{{ $application->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $application->nama_lengkap }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $application->nomor_pendaftaran }} · {{ $application->asal_sekolah }}</p>
                                </td>
                                <td class="px-5 py-4 align-top text-slate-600 dark:text-slate-300">
                                    <p>{{ $application->track->nama_jalur }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $application->pilihanProgram1->nama_jurusan }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ in_array($application->status_pendaftaran, ['accepted', 'verified'], true) ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : ($application->status_pendaftaran === 'needs_revision' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300') }}">
                                        {{ str($application->status_pendaftaran)->replace('_', ' ')->title() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top text-slate-600 dark:text-slate-300">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ number_format((float) ($application->skor_seleksi ?? 0), 2) }}</p>
                                    <p class="mt-1 text-xs text-slate-400">Program akhir: {{ $application->programDiterima?->nama_jurusan ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right align-top">
                                    <a href="{{ route('admin.ppdb.applicants', $periodQuery) }}" class="text-sm font-bold text-blue-600 transition hover:text-blue-700">Buka review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada pendaftar pada periode aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
            Belum ada periode PPDB aktif dan terpublikasi. Siapkan periode terlebih dahulu pada menu Pengaturan PPDB.
        </div>
    @endif
</div>