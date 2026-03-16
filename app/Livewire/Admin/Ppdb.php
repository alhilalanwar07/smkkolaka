<?php

namespace App\Livewire\Admin;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Support\PpdbPeriodResolver;
use App\Support\PpdbSelectionService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Ringkasan PPDB')]
class Ppdb extends Component
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

    public function runSelection(): void
    {
        $activePeriod = $this->resolveSelectedPeriod();

        if (! $activePeriod) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode PPDB aktif untuk diproses.');
            return;
        }

        $summary = app(PpdbSelectionService::class)->processPeriod($activePeriod);

        $this->dispatch('toast', type: 'success', message: "Seleksi selesai diproses. {$summary['passed']} lulus, {$summary['reserve']} cadangan, {$summary['failed']} tidak lulus.");
    }

    public function publishAnnouncement(): void
    {
        $activePeriod = $this->resolveSelectedPeriod();

        if (! $activePeriod) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode aktif yang bisa diumumkan.');
            return;
        }

        $activePeriod->update([
            'status_pengumuman' => 'published',
            'hasil_diumumkan_at' => now(),
            'catatan_pengumuman' => $activePeriod->catatan_pengumuman ?: 'Hasil resmi PPDB telah diumumkan. Peserta lulus dapat melanjutkan ke tahap daftar ulang.',
        ]);

        $this->dispatch('toast', type: 'success', message: 'Pengumuman resmi PPDB berhasil dipublikasikan.');
    }

    public function render()
    {
        $activePeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $activePeriod?->id;

        $summary = $this->buildSummary($activePeriod);

        $quotaOverview = $activePeriod
            ? PpdbQuota::with(['track', 'programKeahlian'])
                ->where('period_id', $activePeriod->id)
                ->where('status_aktif', true)
                ->orderBy('track_id')
                ->get()
            : collect();

        $recentApplications = $activePeriod
            ? PpdbApplication::with(['track', 'pilihanProgram1', 'programDiterima'])
                ->where('period_id', $activePeriod->id)
                ->latest('submitted_at')
                ->limit(6)
                ->get()
            : collect();

        return view('livewire.admin.ppdb', compact('activePeriod', 'summary', 'quotaOverview', 'recentApplications', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin($resolver->resolveInput($this->period));
    }

    protected function buildSummary(?PpdbPeriod $activePeriod): array
    {
        if (! $activePeriod) {
            return ['pendaftar' => 0, 'review' => 0, 'passed' => 0, 'reserve' => 0, 're_registered' => 0, 'scored' => 0];
        }

        return [
            'pendaftar' => PpdbApplication::where('period_id', $activePeriod->id)->count(),
            'review' => PpdbApplication::where('period_id', $activePeriod->id)->whereIn('status_pendaftaran', ['submitted', 'under_review', 'needs_revision'])->count(),
            'passed' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->count(),
            'reserve' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'reserve')->count(),
            're_registered' => PpdbApplication::where('period_id', $activePeriod->id)->whereIn('status_daftar_ulang', ['submitted', 'verified'])->count(),
            'scored' => PpdbApplication::where('period_id', $activePeriod->id)->whereNotNull('scored_at')->count(),
        ];
    }
}

