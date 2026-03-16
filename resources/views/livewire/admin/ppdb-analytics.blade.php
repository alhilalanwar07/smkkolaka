<div class="space-y-6">
    @php
        $periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [];
        $maxTrend = max(max($shortTermTrend->max('submitted'), $shortTermTrend->max('processed')), 1);
        $maxFunnel = max($funnel->max('value') ?? 0, 1);
    @endphp

    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-500">Analisa PPDB</p>
            <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Pusat evaluasi jangka pendek, menengah, dan panjang</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Halaman ini khusus superadmin untuk membaca pola, kecepatan kerja tim, daya serap kuota, dan perbandingan antar gelombang atau tahun ajaran. Operasional harian tetap dipisahkan ke ringkasan PPDB.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:w-[30rem]">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:col-span-2">
                <label class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Periode Analisa</label>
                <select wire:model.live="period" class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950">
                    @foreach($availablePeriods as $periodOption)
                        <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.ppdb', $periodQuery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-rose-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 sm:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Operasional</p>
                <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Kembali ke Ringkasan PPDB</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Gunakan dashboard utama untuk verifikasi, seleksi, dan tindak lanjut panitia.</p>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Pendaftar</p>
            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $snapshot['pendaftar'] }}</p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Basis volume intake untuk periode evaluasi ini.</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Selection Rate</p>
            <p class="mt-3 text-3xl font-black text-emerald-600">{{ number_format($snapshot['selection_rate'], 1) }}%</p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $snapshot['lulus'] }} peserta lolos dari seluruh pendaftar.</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Daftar Ulang Final</p>
            <p class="mt-3 text-3xl font-black text-blue-600">{{ number_format($snapshot['re_registration_rate'], 1) }}%</p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $snapshot['verified_daftar_ulang'] }} peserta sudah tervalidasi sampai akhir.</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Serapan Kuota</p>
            <p class="mt-3 text-3xl font-black text-rose-600">{{ number_format($snapshot['quota_fill_rate'], 1) }}%</p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $snapshot['quota_filled'] }}/{{ $snapshot['quota_total'] }} kursi telah terisi.</p>
        </div>
    </div>

    @if($selectedPeriod)
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.35fr_1fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-500">Jangka Pendek</p>
                        <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Ritme masuk dan proses daftar ulang 7 hari terakhir</h2>
                    </div>
                    <a href="{{ route('admin.ppdb.re-registration', $periodQuery) }}" class="text-sm font-bold text-rose-600 transition hover:text-rose-700">Buka audit</a>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-7">
                    @foreach($shortTermTrend as $trend)
                        <div wire:key="analysis-trend-{{ $trend['date'] }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ $trend['label'] }}</p>
                            <div class="mt-4 flex h-24 items-end gap-2">
                                <div class="flex-1 rounded-t-lg bg-rose-500/85" style="height: {{ $trend['submitted'] > 0 ? max(($trend['submitted'] / $maxTrend) * 100, 8) : 0 }}%"></div>
                                <div class="flex-1 rounded-t-lg bg-sky-500/85" style="height: {{ $trend['processed'] > 0 ? max(($trend['processed'] / $maxTrend) * 100, 8) : 0 }}%"></div>
                            </div>
                            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                <p>Masuk: {{ $trend['submitted'] }}</p>
                                <p class="mt-1">Diproses: {{ $trend['processed'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-500">Jangka Pendek</p>
                <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Kualitas SLA verifikasi daftar ulang</h2>

                <div class="mt-4 space-y-3">
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Rata-rata</p>
                        <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $slaMetrics['average_hours'] }} jam</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Tercepat</p>
                        <p class="mt-1 text-2xl font-black text-emerald-600">{{ $slaMetrics['fastest_minutes'] }} menit</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Selesai < 24 Jam</p>
                        <p class="mt-1 text-2xl font-black text-sky-600">{{ $slaMetrics['within_24_hours'] }}/{{ $slaMetrics['processed_total'] }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1fr_1.2fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-500">Jangka Menengah</p>
                <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Corong konversi sampai intake final</h2>

                <div class="mt-4 space-y-3">
                    @foreach($funnel as $step)
                        <div wire:key="analysis-funnel-{{ \Illuminate\Support\Str::slug($step['label']) }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $step['label'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $step['description'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $step['value'] }}</p>
                                    <p class="text-xs font-semibold text-slate-400">{{ number_format($step['percentage'], 1) }}%</p>
                                </div>
                            </div>
                            <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-white dark:bg-slate-900">
                                <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-rose-500" style="width: {{ $step['value'] > 0 ? max(($step['value'] / $maxFunnel) * 100, 8) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-500">Jangka Menengah</p>
                        <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Serapan kuota per jalur dan jurusan</h2>
                    </div>
                    <a href="{{ route('admin.ppdb.settings', $periodQuery) }}" class="text-sm font-bold text-amber-600 transition hover:text-amber-700">Buka pengaturan</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($quotaPerformance as $quota)
                        <div wire:key="analysis-quota-{{ $quota['id'] }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ $quota['track'] }}</p>
                                    <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $quota['program'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $quota['filled'] }}/{{ $quota['quota'] }}</p>
                                    <p class="text-xs font-semibold {{ $quota['fill_rate'] >= 90 ? 'text-emerald-600' : ($quota['fill_rate'] >= 70 ? 'text-amber-600' : 'text-rose-600') }}">{{ number_format($quota['fill_rate'], 1) }}%</p>
                                </div>
                            </div>
                            <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-white dark:bg-slate-900">
                                <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-sky-500" style="width: {{ min($quota['fill_rate'], 100) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Sisa kapasitas: {{ $quota['remaining'] }} kursi.</p>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                            Belum ada data kuota aktif untuk periode ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-500">Jangka Panjang</p>
            <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Perbandingan performa antar gelombang dan tahun ajaran</h2>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                Tabel ini dipakai untuk membaca tren intake dari periode ke periode: volume pendaftar, tingkat kelulusan, konversi daftar ulang, dan daya serap kuota.
            </p>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Periode</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Pendaftar</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Selection Rate</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Daftar Ulang Final</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Serapan Kuota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($periodComparison as $comparison)
                            <tr wire:key="analysis-period-{{ $comparison['id'] }}" class="{{ $comparison['is_selected'] ? 'bg-rose-50 dark:bg-rose-950/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40' }}">
                                <td class="px-4 py-4 align-top">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $comparison['label'] }}</p>
                                    @if($comparison['is_selected'])
                                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.2em] text-rose-500">Periode aktif analisa</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-slate-900 dark:text-white">{{ $comparison['pendaftar'] }}</td>
                                <td class="px-4 py-4 text-right text-emerald-600">{{ number_format($comparison['selection_rate'], 1) }}%</td>
                                <td class="px-4 py-4 text-right text-sky-600">{{ number_format($comparison['re_registration_rate'], 1) }}%</td>
                                <td class="px-4 py-4 text-right text-rose-600">{{ number_format($comparison['quota_fill_rate'], 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
            Belum ada periode PPDB yang bisa dianalisa. Siapkan atau aktifkan periode terlebih dahulu pada menu Pengaturan PPDB.
        </div>
    @endif
</div>