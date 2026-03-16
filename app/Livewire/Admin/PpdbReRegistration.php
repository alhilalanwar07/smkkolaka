<?php

namespace App\Livewire\Admin;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbTrack;
use App\Models\User;
use App\Support\PpdbPeriodResolver;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Verifikasi Daftar Ulang PPDB')]
class PpdbReRegistration extends Component
{
    use WithPagination;

    #[Url(as: 'periode')]
    public string $period = '';

    public string $search = '';
    public string $trackFilter = '';
    public string $reRegistrationStatusFilter = '';
    public ?int $selectedId = null;
    public array $selectedIds = [];
    public bool $selectPage = false;
    public string $verificationStatus = 'submitted';
    public string $verificationNote = '';
    public string $bulkVerificationStatus = 'verified';
    public string $bulkVerificationNote = '';
    public string $auditOfficerFilter = '';
    public string $auditStatusFilter = '';
    public string $auditDateFrom = '';
    public string $auditDateTo = '';

    public function mount(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        if ($selectedPeriod && $this->period === '') {
            $this->period = (string) $selectedPeriod->id;
        }
    }

    public function updatingPeriod(): void
    {
        $this->resetPage();
        $this->resetPage('auditPage');
        $this->resetSelectionState();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->resetSelectionState();
    }

    public function updatingTrackFilter(): void
    {
        $this->resetPage();
        $this->resetSelectionState();
    }

    public function updatingReRegistrationStatusFilter(): void
    {
        $this->resetPage();
        $this->resetSelectionState();
    }

    public function updatingAuditOfficerFilter(): void
    {
        $this->resetPage('auditPage');
    }

    public function updatingAuditStatusFilter(): void
    {
        $this->resetPage('auditPage');
    }

    public function updatingAuditDateFrom(): void
    {
        $this->resetPage('auditPage');
    }

    public function updatingAuditDateTo(): void
    {
        $this->resetPage('auditPage');
    }

    public function updatedSelectPage(bool $value): void
    {
        if ($value) {
            $activePeriod = $this->resolveSelectedPeriod();

            $this->selectedIds = $this->getOrderedApplicationsQuery($activePeriod)
                ->orderByDesc('daftar_ulang_at')
                ->orderBy('nama_lengkap')
                ->forPage($this->getPage(), 10)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all();

            return;
        }

        $this->selectedIds = [];
    }

    public function selectAllMatchingResults(): void
    {
        $activePeriod = $this->resolveSelectedPeriod();

        $this->selectedIds = $this->getOrderedApplicationsQuery($activePeriod)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $this->selectPage = false;
    }

    public function clearSelectedApplications(): void
    {
        $this->resetSelectionState();
    }

    public function openApplication(int $id): void
    {
        $application = PpdbApplication::with(['track', 'period', 'pilihanProgram1', 'programDiterima', 'reRegistrationVerifier'])
            ->where('hasil_seleksi', 'passed')
            ->findOrFail($id);

        $this->selectedId = $application->id;
        $this->verificationStatus = in_array($application->status_daftar_ulang, ['pending', 'submitted', 'verified', 'rejected'], true)
            ? $application->status_daftar_ulang
            : 'submitted';
        $this->verificationNote = $application->catatan_daftar_ulang ?? '';
    }

    public function saveVerification(): void
    {
        $this->validate([
            'verificationStatus' => 'required|in:pending,submitted,verified,rejected',
            'verificationNote' => 'nullable|string',
        ]);

        $application = PpdbApplication::where('hasil_seleksi', 'passed')->findOrFail($this->selectedId);
        $processedAt = in_array($this->verificationStatus, ['verified', 'rejected'], true) ? now() : null;

        $application->update([
            'status_daftar_ulang' => $this->verificationStatus,
            'catatan_daftar_ulang' => $this->verificationNote,
            'daftar_ulang_at' => in_array($this->verificationStatus, ['submitted', 'verified'], true)
                ? ($application->daftar_ulang_at ?? now())
                : null,
            'verified_daftar_ulang_by' => $processedAt ? auth()->id() : null,
            'verified_daftar_ulang_at' => $processedAt,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Verifikasi daftar ulang berhasil disimpan.');
    }

    public function applyBulkVerification(): void
    {
        $this->validate([
            'bulkVerificationStatus' => 'required|in:submitted,verified,rejected',
            'bulkVerificationNote' => 'nullable|string',
        ]);

        if ($this->selectedIds === []) {
            $this->dispatch('toast', type: 'error', message: 'Pilih minimal satu peserta untuk diproses massal.');
            return;
        }

        $applications = PpdbApplication::query()
            ->whereIn('id', $this->selectedIds)
            ->where('hasil_seleksi', 'passed')
            ->get();

        foreach ($applications as $application) {
            $processedAt = in_array($this->bulkVerificationStatus, ['verified', 'rejected'], true) ? now() : null;

            $application->update([
                'status_daftar_ulang' => $this->bulkVerificationStatus,
                'catatan_daftar_ulang' => $this->bulkVerificationNote !== '' ? $this->bulkVerificationNote : $application->catatan_daftar_ulang,
                'daftar_ulang_at' => in_array($this->bulkVerificationStatus, ['submitted', 'verified'], true)
                    ? ($application->daftar_ulang_at ?? now())
                    : null,
                'verified_daftar_ulang_by' => $processedAt ? auth()->id() : null,
                'verified_daftar_ulang_at' => $processedAt,
            ]);
        }

        $processedCount = $applications->count();

        $this->resetSelectionState();
        $this->bulkVerificationNote = '';

        $this->dispatch('toast', type: 'success', message: "{$processedCount} peserta daftar ulang berhasil diperbarui.");
    }

    public function render()
    {
        $activePeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $activePeriod?->id;

        $tracks = $activePeriod
            ? PpdbTrack::where('period_id', $activePeriod->id)->visible()->get()
            : collect();

        $applicationsQuery = $this->getApplicationsQuery($activePeriod);
        $matchingCount = (clone $applicationsQuery)->count();

        $applications = $this->getOrderedApplicationsQuery($activePeriod)
            ->orderByDesc('daftar_ulang_at')
            ->orderBy('nama_lengkap')
            ->paginate(10);

        $selectedApplication = $this->selectedId
            ? PpdbApplication::with(['track', 'period', 'pilihanProgram1', 'programDiterima', 'reRegistrationVerifier'])
                ->where('hasil_seleksi', 'passed')
                ->find($this->selectedId)
            : null;

        $auditHistoryQuery = $activePeriod
            ? $this->getAuditQuery($activePeriod, true)
            : null;

        $auditHistory = $auditHistoryQuery
            ? (clone $auditHistoryQuery)->latest('verified_daftar_ulang_at')->paginate(6, ['*'], 'auditPage')
            : collect();

        $auditStatsBaseQuery = $activePeriod
            ? $this->getAuditQuery($activePeriod, false)
            : null;

        $auditOfficerStats = $auditStatsBaseQuery
            ? User::query()
                ->whereIn('id', (clone $auditStatsBaseQuery)->pluck('verified_daftar_ulang_by')->filter()->unique())
                ->orderBy('name')
                ->get()
                ->map(function (User $user) use ($auditStatsBaseQuery) {
                    $verifiedCount = (clone $auditStatsBaseQuery)
                        ->where('verified_daftar_ulang_by', $user->id)
                        ->where('status_daftar_ulang', 'verified')
                        ->count();

                    $rejectedCount = (clone $auditStatsBaseQuery)
                        ->where('verified_daftar_ulang_by', $user->id)
                        ->where('status_daftar_ulang', 'rejected')
                        ->count();

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'verified' => $verifiedCount,
                        'rejected' => $rejectedCount,
                        'total' => $verifiedCount + $rejectedCount,
                    ];
                })
                ->filter(fn (array $stat) => $stat['total'] > 0)
                ->values()
            : collect();

        $auditTrend = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($auditStatsBaseQuery) {
                $date = now()->subDays($daysAgo)->toDateString();

                return [
                    'date' => $date,
                    'label' => now()->subDays($daysAgo)->translatedFormat('d M'),
                    'verified' => $auditStatsBaseQuery ? (clone $auditStatsBaseQuery)
                        ->whereDate('verified_daftar_ulang_at', $date)
                        ->where('status_daftar_ulang', 'verified')
                        ->count() : 0,
                    'rejected' => $auditStatsBaseQuery ? (clone $auditStatsBaseQuery)
                        ->whereDate('verified_daftar_ulang_at', $date)
                        ->where('status_daftar_ulang', 'rejected')
                        ->count() : 0,
                ];
            })
            ->map(function (array $day) {
                $day['total'] = $day['verified'] + $day['rejected'];

                return $day;
            });

        $auditOfficers = $activePeriod
            ? PpdbApplication::with('reRegistrationVerifier')
                ->where('period_id', $activePeriod->id)
                ->where('hasil_seleksi', 'passed')
                ->whereNotNull('verified_daftar_ulang_by')
                ->whereNotNull('verified_daftar_ulang_at')
                ->get()
                ->pluck('reRegistrationVerifier')
                ->filter()
                ->unique('id')
                ->sortBy('name')
                ->values()
            : collect();

        $summary = $activePeriod
            ? [
                'eligible' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->count(),
                'submitted' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->where('status_daftar_ulang', 'submitted')->count(),
                'verified' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->where('status_daftar_ulang', 'verified')->count(),
                'pending' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->where('status_daftar_ulang', 'pending')->count(),
                'rejected' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->where('status_daftar_ulang', 'rejected')->count(),
            ]
            : ['eligible' => 0, 'submitted' => 0, 'verified' => 0, 'pending' => 0, 'rejected' => 0];

        return view('livewire.admin.ppdb-re-registration', compact('activePeriod', 'applications', 'selectedApplication', 'summary', 'tracks', 'matchingCount', 'auditHistory', 'auditOfficers', 'auditOfficerStats', 'auditTrend', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin($resolver->resolveInput($this->period));
    }

    protected function getOrderedApplicationsQuery(?PpdbPeriod $activePeriod): Builder
    {
        return $this->getApplicationsQuery($activePeriod)
            ->orderByRaw("case when status_daftar_ulang = 'submitted' then 0 when status_daftar_ulang = 'pending' then 1 when status_daftar_ulang = 'rejected' then 2 when status_daftar_ulang = 'verified' then 3 else 4 end");
    }

    protected function getApplicationsQuery(?PpdbPeriod $activePeriod): Builder
    {
        return PpdbApplication::with(['track', 'period', 'pilihanProgram1', 'programDiterima', 'reRegistrationVerifier'])
            ->when($activePeriod, fn ($query) => $query->where('period_id', $activePeriod->id))
            ->where('hasil_seleksi', 'passed')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nama_lengkap', 'like', "%{$this->search}%")
                        ->orWhere('nomor_pendaftaran', 'like', "%{$this->search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$this->search}%");
                });
            })
            ->when($this->trackFilter, fn ($query) => $query->where('track_id', $this->trackFilter))
            ->when($this->reRegistrationStatusFilter, fn ($query) => $query->where('status_daftar_ulang', $this->reRegistrationStatusFilter));
    }

    protected function getAuditQuery(PpdbPeriod $activePeriod, bool $includeOfficerFilter): Builder
    {
        return PpdbApplication::with(['track', 'programDiterima', 'reRegistrationVerifier'])
            ->where('period_id', $activePeriod->id)
            ->where('hasil_seleksi', 'passed')
            ->whereIn('status_daftar_ulang', ['verified', 'rejected'])
            ->whereNotNull('verified_daftar_ulang_at')
            ->when($includeOfficerFilter && $this->auditOfficerFilter, fn ($query) => $query->where('verified_daftar_ulang_by', $this->auditOfficerFilter))
            ->when($this->auditStatusFilter, fn ($query) => $query->where('status_daftar_ulang', $this->auditStatusFilter))
            ->when($this->auditDateFrom, fn ($query) => $query->whereDate('verified_daftar_ulang_at', '>=', $this->auditDateFrom))
            ->when($this->auditDateTo, fn ($query) => $query->whereDate('verified_daftar_ulang_at', '<=', $this->auditDateTo));
    }

    protected function resetSelectionState(): void
    {
        $this->selectedId = null;
        $this->selectedIds = [];
        $this->selectPage = false;
    }
}