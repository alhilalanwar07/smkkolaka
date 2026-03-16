<?php

namespace App\Livewire\Admin;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Support\PpdbPeriodResolver;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Analisa PPDB')]
class PpdbAnalytics extends Component
{
    #[Url(as: 'periode')]
    public string $period = '';

    public function mount(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        if ($selectedPeriod && $this->period === '') {
            $this->period = (string) $selectedPeriod->id;
        }
    }

    public function render()
    {
        $selectedPeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $selectedPeriod?->id;

        $snapshot = $this->buildSnapshot($selectedPeriod);
        $shortTermTrend = $this->buildReRegistrationTrend($selectedPeriod);
        $slaMetrics = $this->buildSlaMetrics($selectedPeriod);
        $funnel = $this->buildFunnel($selectedPeriod);
        $quotaPerformance = $this->buildQuotaPerformance($selectedPeriod);
        $periodComparison = $this->buildPeriodComparison($selectedPeriod);

        return view('livewire.admin.ppdb-analytics', compact(
            'availablePeriods',
            'selectedPeriod',
            'selectedPeriodId',
            'snapshot',
            'shortTermTrend',
            'slaMetrics',
            'funnel',
            'quotaPerformance',
            'periodComparison',
        ));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin($resolver->resolveInput($this->period));
    }

    protected function buildSnapshot(?PpdbPeriod $selectedPeriod): array
    {
        if (! $selectedPeriod) {
            return [
                'pendaftar' => 0,
                'lulus' => 0,
                'verified_daftar_ulang' => 0,
                'selection_rate' => 0.0,
                're_registration_rate' => 0.0,
                'quota_fill_rate' => 0.0,
                'quota_total' => 0,
                'quota_filled' => 0,
            ];
        }

        $applications = PpdbApplication::query()->where('period_id', $selectedPeriod->id);
        $pendaftar = (clone $applications)->count();
        $lulus = (clone $applications)->where('hasil_seleksi', 'passed')->count();
        $verifiedDaftarUlang = (clone $applications)->where('status_daftar_ulang', 'verified')->count();
        $quotaTotals = PpdbQuota::query()
            ->where('period_id', $selectedPeriod->id)
            ->where('status_aktif', true)
            ->selectRaw('COALESCE(SUM(kuota), 0) as total_kuota, COALESCE(SUM(kuota_terisi), 0) as total_terisi')
            ->first();

        $quotaTotal = (int) ($quotaTotals?->total_kuota ?? 0);
        $quotaFilled = (int) ($quotaTotals?->total_terisi ?? 0);

        return [
            'pendaftar' => $pendaftar,
            'lulus' => $lulus,
            'verified_daftar_ulang' => $verifiedDaftarUlang,
            'selection_rate' => $pendaftar > 0 ? round(($lulus / $pendaftar) * 100, 1) : 0.0,
            're_registration_rate' => $lulus > 0 ? round(($verifiedDaftarUlang / $lulus) * 100, 1) : 0.0,
            'quota_fill_rate' => $quotaTotal > 0 ? round(($quotaFilled / $quotaTotal) * 100, 1) : 0.0,
            'quota_total' => $quotaTotal,
            'quota_filled' => $quotaFilled,
        ];
    }

    protected function buildReRegistrationTrend(?PpdbPeriod $selectedPeriod): Collection
    {
        return collect(range(6, 0))->map(function (int $daysAgo) use ($selectedPeriod) {
            $date = now()->subDays($daysAgo)->toDateString();

            return [
                'date' => $date,
                'label' => now()->subDays($daysAgo)->translatedFormat('d M'),
                'submitted' => $selectedPeriod
                    ? PpdbApplication::where('period_id', $selectedPeriod->id)
                        ->whereDate('daftar_ulang_at', $date)
                        ->whereIn('status_daftar_ulang', ['submitted', 'verified'])
                        ->count()
                    : 0,
                'processed' => $selectedPeriod
                    ? PpdbApplication::where('period_id', $selectedPeriod->id)
                        ->whereDate('verified_daftar_ulang_at', $date)
                        ->whereIn('status_daftar_ulang', ['verified', 'rejected'])
                        ->count()
                    : 0,
            ];
        });
    }

    protected function buildSlaMetrics(?PpdbPeriod $selectedPeriod): array
    {
        if (! $selectedPeriod) {
            return [
                'average_hours' => 0,
                'fastest_minutes' => 0,
                'within_24_hours' => 0,
                'processed_total' => 0,
            ];
        }

        $processedApplications = PpdbApplication::query()
            ->where('period_id', $selectedPeriod->id)
            ->whereIn('status_daftar_ulang', ['verified', 'rejected'])
            ->whereNotNull('daftar_ulang_at')
            ->whereNotNull('verified_daftar_ulang_at')
            ->get()
            ->map(fn (PpdbApplication $application) => $application->daftar_ulang_at->diffInMinutes($application->verified_daftar_ulang_at, true));

        if ($processedApplications->isEmpty()) {
            return [
                'average_hours' => 0,
                'fastest_minutes' => 0,
                'within_24_hours' => 0,
                'processed_total' => 0,
            ];
        }

        return [
            'average_hours' => round($processedApplications->avg() / 60, 1),
            'fastest_minutes' => (int) $processedApplications->min(),
            'within_24_hours' => $processedApplications->filter(fn (int $minutes) => $minutes <= 1440)->count(),
            'processed_total' => $processedApplications->count(),
        ];
    }

    protected function buildFunnel(?PpdbPeriod $selectedPeriod): Collection
    {
        if (! $selectedPeriod) {
            return collect();
        }

        $applications = PpdbApplication::query()->where('period_id', $selectedPeriod->id);
        $total = (clone $applications)->count();
        $steps = [
            [
                'label' => 'Berkas Masuk',
                'value' => $total,
                'description' => 'Total formulir yang sudah tercatat pada periode ini.',
            ],
            [
                'label' => 'Lolos Verifikasi',
                'value' => (clone $applications)->whereIn('status_pendaftaran', ['verified', 'accepted'])->count(),
                'description' => 'Peserta yang sudah lolos cek berkas administrasi.',
            ],
            [
                'label' => 'Sudah Dinilai',
                'value' => (clone $applications)->whereNotNull('scored_at')->count(),
                'description' => 'Peserta yang sudah mendapat skor tes atau penilaian akhir.',
            ],
            [
                'label' => 'Lulus Seleksi',
                'value' => (clone $applications)->where('hasil_seleksi', 'passed')->count(),
                'description' => 'Peserta yang lolos ke tahap pengumuman.',
            ],
            [
                'label' => 'Daftar Ulang Terverifikasi',
                'value' => (clone $applications)->where('status_daftar_ulang', 'verified')->count(),
                'description' => 'Peserta yang benar-benar terkunci menjadi intake final.',
            ],
        ];

        return collect($steps)->map(function (array $step) use ($total) {
            $step['percentage'] = $total > 0 ? round(($step['value'] / $total) * 100, 1) : 0.0;

            return $step;
        });
    }

    protected function buildQuotaPerformance(?PpdbPeriod $selectedPeriod): Collection
    {
        if (! $selectedPeriod) {
            return collect();
        }

        return PpdbQuota::query()
            ->with(['track', 'programKeahlian'])
            ->where('period_id', $selectedPeriod->id)
            ->where('status_aktif', true)
            ->orderByDesc('kuota_terisi')
            ->get()
            ->map(function (PpdbQuota $quota) {
                $fillRate = $quota->kuota > 0 ? round(($quota->kuota_terisi / $quota->kuota) * 100, 1) : 0.0;

                return [
                    'id' => $quota->id,
                    'track' => $quota->track?->nama_jalur ?? '-',
                    'program' => $quota->programKeahlian?->nama_jurusan ?? '-',
                    'filled' => $quota->kuota_terisi,
                    'quota' => $quota->kuota,
                    'remaining' => max($quota->kuota - $quota->kuota_terisi, 0),
                    'fill_rate' => $fillRate,
                ];
            });
    }

    protected function buildPeriodComparison(?PpdbPeriod $selectedPeriod): Collection
    {
        $periods = PpdbPeriod::query()
            ->publiclyVisible()
            ->orderForSelection()
            ->limit(6)
            ->get();

        if ($periods->isEmpty()) {
            return collect();
        }

        $periodIds = $periods->pluck('id');
        $applications = PpdbApplication::query()
            ->whereIn('period_id', $periodIds)
            ->get(['period_id', 'hasil_seleksi', 'status_daftar_ulang']);
        $quotaTotals = PpdbQuota::query()
            ->whereIn('period_id', $periodIds)
            ->where('status_aktif', true)
            ->selectRaw('period_id, COALESCE(SUM(kuota), 0) as total_kuota, COALESCE(SUM(kuota_terisi), 0) as total_terisi')
            ->groupBy('period_id')
            ->get()
            ->keyBy('period_id');

        return $periods->map(function (PpdbPeriod $period) use ($applications, $quotaTotals, $selectedPeriod) {
            $periodApplications = $applications->where('period_id', $period->id);
            $applicantCount = $periodApplications->count();
            $passedCount = $periodApplications->where('hasil_seleksi', 'passed')->count();
            $verifiedReRegistration = $periodApplications->where('status_daftar_ulang', 'verified')->count();
            $quota = $quotaTotals->get($period->id);
            $quotaTotal = (int) ($quota?->total_kuota ?? 0);
            $quotaFilled = (int) ($quota?->total_terisi ?? 0);

            return [
                'id' => $period->id,
                'label' => $period->full_label,
                'is_selected' => $selectedPeriod?->id === $period->id,
                'pendaftar' => $applicantCount,
                'lulus' => $passedCount,
                'verified_daftar_ulang' => $verifiedReRegistration,
                'selection_rate' => $applicantCount > 0 ? round(($passedCount / $applicantCount) * 100, 1) : 0.0,
                're_registration_rate' => $passedCount > 0 ? round(($verifiedReRegistration / $passedCount) * 100, 1) : 0.0,
                'quota_fill_rate' => $quotaTotal > 0 ? round(($quotaFilled / $quotaTotal) * 100, 1) : 0.0,
            ];
        });
    }
}