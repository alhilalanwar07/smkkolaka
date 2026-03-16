<?php

namespace App\Livewire\Admin;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Models\PpdbTrack;
use App\Support\PpdbPeriodResolver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Data Pendaftar PPDB')]
class PpdbApplicants extends Component
{
    use WithPagination;

    #[Url(as: 'periode')]
    public string $period = '';

    public string $search = '';
    public string $statusFilter = '';
    public string $trackFilter = '';
    public string $selectionFilter = '';
    public bool $showReviewModal = false;
    public ?int $selectedId = null;
    public string $reviewStatus = 'under_review';
    public string $reviewBerkasStatus = 'pending';
    public string $reviewNote = '';
    public string $reviewSkorAkademik = '';
    public string $reviewSkorPrestasi = '';
    public string $reviewSkorAfirmasi = '';
    public string $reviewSkorTesDasar = '';
    public string $reviewSkorWawancara = '';
    public string $reviewSkorBerkas = '';

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
        $this->selectedId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTrackFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSelectionFilter(): void
    {
        $this->resetPage();
    }

    public function openReview(int $id): void
    {
        $application = PpdbApplication::findOrFail($id);

        $this->selectedId = $application->id;
        $this->reviewStatus = $application->status_pendaftaran;
        $this->reviewBerkasStatus = $application->status_berkas;
        $this->reviewNote = $application->catatan_verifikator ?? '';
        $this->reviewSkorAkademik = (string) ($application->skor_akademik ?? '');
        $this->reviewSkorPrestasi = (string) ($application->skor_prestasi ?? '');
        $this->reviewSkorAfirmasi = (string) ($application->skor_afirmasi ?? '');
        $this->reviewSkorTesDasar = (string) ($application->skor_tes_dasar ?? '');
        $this->reviewSkorWawancara = (string) ($application->skor_wawancara ?? '');
        $this->reviewSkorBerkas = (string) ($application->skor_berkas ?? '');
        $this->showReviewModal = true;
    }

    public function saveReview(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:submitted,under_review,needs_revision,verified,accepted,rejected',
            'reviewBerkasStatus' => 'required|in:pending,incomplete,complete,revision,verified',
            'reviewNote' => 'nullable|string',
            'reviewSkorAkademik' => 'nullable|numeric|min:0|max:100',
            'reviewSkorPrestasi' => 'nullable|numeric|min:0|max:100',
            'reviewSkorAfirmasi' => 'nullable|numeric|min:0|max:100',
            'reviewSkorTesDasar' => 'nullable|numeric|min:0|max:100',
            'reviewSkorWawancara' => 'nullable|numeric|min:0|max:100',
            'reviewSkorBerkas' => 'nullable|numeric|min:0|max:100',
        ]);

        $application = PpdbApplication::findOrFail($this->selectedId);
        $selectionUpdates = [];

        if ($this->reviewStatus === 'accepted') {
            $selectionUpdates = [
                'hasil_seleksi' => 'passed',
                'program_diterima_id' => $application->program_diterima_id ?? $application->pilihan_program_1_id,
                'selection_notes' => $application->selection_notes ?: 'Ditetapkan lulus oleh panitia PPDB.',
            ];
        } elseif ($this->reviewStatus === 'rejected') {
            $selectionUpdates = [
                'hasil_seleksi' => 'failed',
                'program_diterima_id' => null,
                'selection_notes' => 'Ditetapkan tidak lulus oleh panitia PPDB.',
            ];
        } elseif ($this->reviewStatus === 'verified' && $application->hasil_seleksi === 'failed') {
            $selectionUpdates = [
                'hasil_seleksi' => 'pending',
                'selection_notes' => 'Berkas valid dan menunggu proses seleksi ulang.',
            ];
        }

        $application->update(array_merge([
            'status_pendaftaran' => $this->reviewStatus,
            'status_berkas' => $this->reviewBerkasStatus,
            'catatan_verifikator' => $this->reviewNote,
            'skor_akademik' => $this->reviewSkorAkademik !== '' ? $this->reviewSkorAkademik : null,
            'skor_prestasi' => $this->reviewSkorPrestasi !== '' ? $this->reviewSkorPrestasi : null,
            'skor_afirmasi' => $this->reviewSkorAfirmasi !== '' ? $this->reviewSkorAfirmasi : null,
            'skor_tes_dasar' => $this->reviewSkorTesDasar !== '' ? $this->reviewSkorTesDasar : null,
            'skor_wawancara' => $this->reviewSkorWawancara !== '' ? $this->reviewSkorWawancara : null,
            'skor_berkas' => $this->reviewSkorBerkas !== '' ? $this->reviewSkorBerkas : null,
            'verified_by' => auth()->id(),
            'verified_at' => in_array($this->reviewStatus, ['verified', 'accepted', 'rejected'], true) ? now() : $application->verified_at,
        ], $selectionUpdates));

        $this->showReviewModal = false;
        $this->dispatch('toast', type: 'success', message: 'Review PPDB berhasil disimpan.');
    }

    public function render()
    {
        $activePeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $activePeriod?->id;

        $tracks = $activePeriod
            ? PpdbTrack::where('period_id', $activePeriod->id)->visible()->get()
            : collect();

        $applicationsQuery = PpdbApplication::with(['track', 'pilihanProgram1', 'programDiterima', 'documents'])
            ->when($activePeriod, fn ($query) => $query->where('period_id', $activePeriod->id))
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nama_lengkap', 'like', "%{$this->search}%")
                        ->orWhere('nomor_pendaftaran', 'like', "%{$this->search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn ($query) => $query->where('status_pendaftaran', $this->statusFilter))
            ->when($this->trackFilter, fn ($query) => $query->where('track_id', $this->trackFilter))
            ->when($this->selectionFilter, fn ($query) => $query->where('hasil_seleksi', $this->selectionFilter));

        $applications = (clone $applicationsQuery)
            ->latest()
            ->paginate(10);

        $summary = $activePeriod
            ? [
                'pendaftar' => PpdbApplication::where('period_id', $activePeriod->id)->count(),
                'review' => PpdbApplication::where('period_id', $activePeriod->id)->whereIn('status_pendaftaran', ['submitted', 'under_review', 'needs_revision'])->count(),
                'passed' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'passed')->count(),
                'reserve' => PpdbApplication::where('period_id', $activePeriod->id)->where('hasil_seleksi', 'reserve')->count(),
                're_registered' => PpdbApplication::where('period_id', $activePeriod->id)->whereIn('status_daftar_ulang', ['submitted', 'verified'])->count(),
            ]
            : ['pendaftar' => 0, 'review' => 0, 'passed' => 0, 'reserve' => 0, 're_registered' => 0];

        $quotaOverview = $activePeriod
            ? PpdbQuota::with(['track', 'programKeahlian'])
                ->where('period_id', $activePeriod->id)
                ->where('status_aktif', true)
                ->orderBy('track_id')
                ->get()
            : collect();

        $selectedApplication = $this->selectedId
            ? PpdbApplication::with(['track', 'period', 'pilihanProgram1', 'pilihanProgram2', 'programDiterima', 'documents', 'verifier', 'reRegistrationVerifier'])->find($this->selectedId)
            : null;

        return view('livewire.admin.ppdb-applicants', compact('activePeriod', 'tracks', 'applications', 'selectedApplication', 'summary', 'quotaOverview', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin($resolver->resolveInput($this->period));
    }
}
