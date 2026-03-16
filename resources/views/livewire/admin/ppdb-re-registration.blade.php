<div class="space-y-6">
    @php
        $exportQuery = array_filter([
            'scope' => 're-registration',
            'period_id' => $selectedPeriodId,
            'search' => $search,
            'track_id' => $trackFilter,
            'registration_status' => $reRegistrationStatusFilter,
        ], fn ($value) => filled($value));
        $auditExportQuery = array_filter([
            'scope' => 're-registration-audit',
            'period_id' => $selectedPeriodId,
            'audit_officer' => $auditOfficerFilter,
            'audit_status' => $auditStatusFilter,
            'audit_date_from' => $auditDateFrom,
            'audit_date_to' => $auditDateTo,
        ], fn ($value) => filled($value));
        $periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [];
        $selectedIdStrings = array_map('strval', $selectedIds);
        $maxAuditTrend = max($auditTrend->max('total'), 1);
    @endphp

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Verifikasi Daftar Ulang PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Finalisasi peserta yang sudah dinyatakan lulus</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Halaman ini dipisahkan khusus untuk panitia finalisasi. Fokusnya hanya peserta lulus, konfirmasi daftar ulang, dan status verifikasi kehadiran agar tidak bercampur dengan review administrasi awal.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="period" class="min-w-[280px] rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                @foreach($availablePeriods as $periodOption)
                    <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.ppdb', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Ringkasan PPDB</a>
            <a href="{{ route('admin.ppdb.applicants', $periodQuery) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Data pendaftar</a>
            <a href="{{ route('admin.ppdb.export', $exportQuery) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Export hasil terfilter</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Peserta Lulus</p>
            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $summary['eligible'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Masuk Verifikasi</p>
            <p class="mt-3 text-3xl font-black text-blue-600">{{ $summary['submitted'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Terverifikasi</p>
            <p class="mt-3 text-3xl font-black text-emerald-600">{{ $summary['verified'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Belum Konfirmasi</p>
            <p class="mt-3 text-3xl font-black text-amber-600">{{ $summary['pending'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Perlu Tindak Lanjut</p>
            <p class="mt-3 text-3xl font-black text-red-600">{{ $summary['rejected'] }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="grid grid-cols-1 gap-3 xl:grid-cols-[1.5fr_repeat(2,minmax(0,1fr))]">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, nomor pendaftaran, atau sekolah..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
            <select wire:model.live="trackFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua jalur</option>
                @foreach($tracks as $track)
                    <option value="{{ $track->id }}">{{ $track->nama_jalur }}</option>
                @endforeach
            </select>
            <select wire:model.live="reRegistrationStatusFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                <option value="">Semua status daftar ulang</option>
                <option value="pending">Pending</option>
                <option value="submitted">Submitted</option>
                <option value="verified">Verified</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60 xl:grid-cols-[auto_1fr_1.1fr_auto]">
            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                <input wire:model.live="selectPage" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>Pilih semua di halaman ini</span>
            </label>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                {{ count($selectedIds) }} peserta dipilih dari total {{ $matchingCount }} hasil filter.
            </div>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,180px)_1fr]">
                <select wire:model="bulkVerificationStatus" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                    <option value="verified">Bulk Verify</option>
                    <option value="rejected">Bulk Reject</option>
                    <option value="submitted">Tandai Submitted</option>
                </select>
                <input wire:model="bulkVerificationNote" type="text" placeholder="Catatan massal opsional..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
            </div>
            <button wire:click="applyBulkVerification" wire:loading.attr="disabled" type="button" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                Terapkan Bulk Action
            </button>
        </div>

        <div class="mt-3 flex flex-wrap gap-3 text-sm">
            <button wire:click="selectAllMatchingResults" type="button" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-bold text-slate-700 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                Pilih semua hasil filter
            </button>
            <button wire:click="clearSelectedApplications" type="button" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-bold text-slate-700 transition hover:border-red-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                Bersihkan pilihan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.1fr_1.2fr]">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Antrean Finalisasi</p>
                <h3 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Peserta lulus dan status daftar ulang</h3>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($applications as $application)
                    <div wire:key="re-registration-application-{{ $application->id }}" class="flex items-start justify-between gap-4 px-5 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/40 {{ $selectedId === $application->id ? 'bg-blue-50 dark:bg-blue-950/30' : '' }} {{ in_array((string) $application->id, $selectedIdStrings, true) ? 'ring-1 ring-inset ring-blue-200 dark:ring-blue-800' : '' }}">
                        <div class="flex min-w-0 flex-1 items-start gap-3">
                            <input wire:model.live="selectedIds" type="checkbox" value="{{ $application->id }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <button wire:click="openApplication({{ $application->id }})" type="button" class="min-w-0 flex-1 text-left">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $application->nama_lengkap }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $application->nomor_pendaftaran }} · {{ $application->asal_sekolah }}</p>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $application->track->nama_jalur }} · {{ $application->programDiterima?->nama_jurusan ?? $application->pilihanProgram1?->nama_jurusan }}</p>
                            </button>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ match ($application->status_daftar_ulang) {
                                'verified' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                'submitted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                default => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                            } }}">{{ str($application->status_daftar_ulang)->replace('_', ' ')->title() }}</span>
                            <p class="mt-2 text-xs text-slate-400">{{ $application->daftar_ulang_at?->translatedFormat('d M Y H:i') ?? 'Belum ada konfirmasi' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-slate-400">Belum ada peserta lulus yang sesuai filter.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $applications->links() }}</div>
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
                                <p class="font-semibold text-slate-700 dark:text-slate-200">Program Diterima</p>
                                <p class="mt-1 text-slate-500 dark:text-slate-400">{{ $selectedApplication->programDiterima?->nama_jurusan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Konteks Daftar Ulang</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <p><span class="font-semibold text-slate-900 dark:text-white">Status saat ini:</span> {{ str($selectedApplication->status_daftar_ulang)->replace('_', ' ')->title() }}</p>
                            <p><span class="font-semibold text-slate-900 dark:text-white">Skor seleksi:</span> {{ number_format((float) ($selectedApplication->skor_seleksi ?? 0), 2) }}</p>
                            <p><span class="font-semibold text-slate-900 dark:text-white">Konfirmasi masuk:</span> {{ $selectedApplication->daftar_ulang_at?->translatedFormat('d M Y H:i') ?? 'Belum ada' }}</p>
                            <p><span class="font-semibold text-slate-900 dark:text-white">Diproses oleh:</span> {{ $selectedApplication->reRegistrationVerifier?->name ?? '-' }}</p>
                            <p><span class="font-semibold text-slate-900 dark:text-white">Diproses pada:</span> {{ $selectedApplication->verified_daftar_ulang_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                            <p class="leading-relaxed"><span class="font-semibold text-slate-900 dark:text-white">Catatan peserta/panitia:</span> {{ $selectedApplication->catatan_daftar_ulang ?: 'Belum ada catatan daftar ulang.' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Form Verifikasi</p>
                        <form wire:submit="saveVerification" class="mt-4 space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status Daftar Ulang</label>
                                <select wire:model="verificationStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                    <option value="pending">Pending</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="verified">Verified</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Catatan Verifikasi</label>
                                <textarea wire:model="verificationNote" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-800"></textarea>
                            </div>

                            <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-400">
                                <span>Periode: {{ $selectedApplication->period->nama_periode }}</span>
                                <span>TA: {{ $selectedApplication->period->tahun_ajaran }}</span>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Simpan Verifikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                        Pilih peserta lulus dari antrean untuk memverifikasi status daftar ulang.
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Riwayat Audit</p>
                                <h3 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Aktivitas finalisasi daftar ulang</h3>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-4">
                            <select wire:model.live="auditOfficerFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                                <option value="">Semua petugas</option>
                                @foreach($auditOfficers as $officer)
                                    <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="auditStatusFilter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                                <option value="">Semua status audit</option>
                                <option value="verified">Verified</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <input wire:model.live="auditDateFrom" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                            <input wire:model.live="auditDateTo" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('admin.ppdb.export', $auditExportQuery) }}" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Export audit terfilter</a>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 xl:grid-cols-3">
                            @forelse($auditOfficerStats as $stat)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $stat['name'] }}</p>
                                    <div class="mt-3 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em]">
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">Verified {{ $stat['verified'] }}</span>
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-red-700 dark:bg-red-900 dark:text-red-300">Rejected {{ $stat['rejected'] }}</span>
                                    </div>
                                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Total diproses: {{ $stat['total'] }}</p>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400 xl:col-span-3">
                                    Belum ada statistik petugas yang bisa ditampilkan untuk filter audit saat ini.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Tren 7 Hari</p>
                                    <h4 class="mt-1 text-base font-black text-slate-900 dark:text-white">Pergerakan audit finalisasi</h4>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-7">
                                @foreach($auditTrend as $trend)
                                    <div wire:key="audit-trend-{{ $trend['date'] }}" class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ $trend['label'] }}</p>
                                        <div class="mt-4 flex h-24 items-end gap-2">
                                            <div class="flex-1 rounded-t-lg bg-emerald-500/85" style="height: {{ $trend['verified'] > 0 ? max(($trend['verified'] / $maxAuditTrend) * 100, 8) : 0 }}%"></div>
                                            <div class="flex-1 rounded-t-lg bg-red-500/85" style="height: {{ $trend['rejected'] > 0 ? max(($trend['rejected'] / $maxAuditTrend) * 100, 8) : 0 }}%"></div>
                                        </div>
                                        <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                            <p>Verified: {{ $trend['verified'] }}</p>
                                            <p class="mt-1">Rejected: {{ $trend['rejected'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($auditHistory as $audit)
                                <div wire:key="audit-trail-{{ $audit->id }}" class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $audit->nama_lengkap }}</p>
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $audit->nomor_pendaftaran }} · {{ $audit->track?->nama_jalur }} · {{ $audit->programDiterima?->nama_jurusan ?? '-' }}</p>
                                        </div>
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $audit->status_daftar_ulang === 'verified' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">{{ str($audit->status_daftar_ulang)->replace('_', ' ')->title() }}</span>
                                    </div>
                                    <div class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                                        <p><span class="font-semibold text-slate-900 dark:text-white">Petugas:</span> {{ $audit->reRegistrationVerifier?->name ?? '-' }}</p>
                                        <p class="mt-1"><span class="font-semibold text-slate-900 dark:text-white">Waktu:</span> {{ $audit->verified_daftar_ulang_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                                        <p class="mt-1 leading-relaxed"><span class="font-semibold text-slate-900 dark:text-white">Catatan:</span> {{ $audit->catatan_daftar_ulang ?: '-' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                    Belum ada aktivitas verifikasi atau penolakan daftar ulang.
                                </div>
                            @endforelse
                        </div>

                        @if(method_exists($auditHistory, 'links'))
                            <div class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-800">
                                {{ $auditHistory->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>