<?php

namespace App\Livewire\Admin;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbTrack;
use App\Support\PpdbPeriodResolver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Panitia Tes PPDB')]
class PpdbTestScoring extends Component
{
    use WithPagination;

    #[Url(as: 'periode')]
    public string $period = '';

    public string $search = '';
    public string $trackFilter = '';
    public string $scoreStatusFilter = '';
    public ?int $selectedId = null;
    public string $scoreStatus = 'verified';
    public string $scoreBerkasStatus = 'verified';
    public string $scoreNote = '';
    public string $scoreAkademik = '';
    public string $scorePrestasi = '';
    public string $scoreAfirmasi = '';
    public string $scoreTesDasar = '';
    public string $scoreWawancara = '';
    public string $scoreBerkas = '';

    public function mount(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        if ($selectedPeriod && $this->period === '') {
            $this->period = (string) $selectedPeriod->id;
        }
    }

    public function updatingPeriod(): void
    {
        $this->selectedId = null;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->selectedId = null;
        $this->resetPage();
    }

    public function updatingTrackFilter(): void
    {
        $this->selectedId = null;
        $this->resetPage();
    }

    public function updatingScoreStatusFilter(): void
    {
        $this->selectedId = null;
        $this->resetPage();
    }

    public function openCandidate(int $id): void
    {
        $application = PpdbApplication::findOrFail($id);

        $this->selectedId = $application->id;
        $this->scoreStatus = in_array($application->status_pendaftaran, ['under_review', 'needs_revision', 'verified'], true)
            ? $application->status_pendaftaran
            : 'verified';
        $this->scoreBerkasStatus = in_array($application->status_berkas, ['pending', 'incomplete', 'complete', 'revision', 'verified'], true)
            ? $application->status_berkas
            : 'verified';
        $this->scoreNote = $application->catatan_verifikator ?? '';
        $this->scoreAkademik = (string) ($application->skor_akademik ?? $application->nilai_rata_rata ?? '');
        $this->scorePrestasi = (string) ($application->skor_prestasi ?? '');
        $this->scoreAfirmasi = (string) ($application->skor_afirmasi ?? '');
        $this->scoreTesDasar = (string) ($application->skor_tes_dasar ?? '');
        $this->scoreWawancara = (string) ($application->skor_wawancara ?? '');
        $this->scoreBerkas = (string) ($application->skor_berkas ?? '');
    }

    public function saveScoring(): void
    {
        $this->validate([
            'selectedId' => 'required|integer',
            'scoreStatus' => 'required|in:under_review,needs_revision,verified',
            'scoreBerkasStatus' => 'required|in:pending,incomplete,complete,revision,verified',
            'scoreNote' => 'nullable|string',
            'scoreAkademik' => 'nullable|numeric|min:0|max:100',
            'scorePrestasi' => 'nullable|numeric|min:0|max:100',
            'scoreAfirmasi' => 'nullable|numeric|min:0|max:100',
            'scoreTesDasar' => 'nullable|numeric|min:0|max:100',
            'scoreWawancara' => 'nullable|numeric|min:0|max:100',
            'scoreBerkas' => 'nullable|numeric|min:0|max:100',
        ]);

        $application = PpdbApplication::findOrFail($this->selectedId);

        $application->update([
            'status_pendaftaran' => $this->scoreStatus,
            'status_berkas' => $this->scoreBerkasStatus,
            'catatan_verifikator' => $this->scoreNote,
            'skor_akademik' => $this->scoreAkademik !== '' ? $this->scoreAkademik : null,
            'skor_prestasi' => $this->scorePrestasi !== '' ? $this->scorePrestasi : null,
            'skor_afirmasi' => $this->scoreAfirmasi !== '' ? $this->scoreAfirmasi : null,
            'skor_tes_dasar' => $this->scoreTesDasar !== '' ? $this->scoreTesDasar : null,
            'skor_wawancara' => $this->scoreWawancara !== '' ? $this->scoreWawancara : null,
            'skor_berkas' => $this->scoreBerkas !== '' ? $this->scoreBerkas : null,
            'verified_by' => auth()->id(),
            'verified_at' => $this->scoreStatus === 'verified' ? now() : $application->verified_at,
            'scored_at' => now(),
            'selection_notes' => $application->selection_notes ?: 'Nilai panitia tes telah diinput dan siap diproses pada seleksi tahap 2.',
        ]);

        $this->openCandidate($application->id);

        $this->dispatch('toast', type: 'success', message: 'Nilai peserta berhasil disimpan.');
    }

    public function render()
    {
        $activePeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $activePeriod?->id;

        $tracks = $activePeriod
            ? PpdbTrack::where('period_id', $activePeriod->id)->visible()->get()
            : collect();

        $candidatesQuery = PpdbApplication::with(['track', 'pilihanProgram1', 'documents'])
            ->when($activePeriod, fn ($query) => $query->where('period_id', $activePeriod->id))
            ->whereNotIn('status_pendaftaran', ['accepted', 'rejected'])
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nama_lengkap', 'like', "%{$this->search}%")
                        ->orWhere('nomor_pendaftaran', 'like', "%{$this->search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$this->search}%");
                });
            })
            ->when($this->trackFilter, fn ($query) => $query->where('track_id', $this->trackFilter))
            ->when($this->scoreStatusFilter === 'scored', fn ($query) => $query->whereNotNull('scored_at'))
            ->when($this->scoreStatusFilter === 'unscored', fn ($query) => $query->whereNull('scored_at'))
            ->orderByRaw('scored_at is null desc')
            ->orderBy('submitted_at');

        $candidates = (clone $candidatesQuery)->paginate(8);

        if (! $this->selectedId && $candidates->count() > 0) {
            $this->openCandidate($candidates->first()->id);
        }

        $selectedApplication = $this->selectedId
            ? PpdbApplication::with(['track', 'period', 'pilihanProgram1', 'pilihanProgram2', 'documents'])->find($this->selectedId)
            : null;

        $summary = $activePeriod
            ? [
                'total' => PpdbApplication::where('period_id', $activePeriod->id)->whereNotIn('status_pendaftaran', ['accepted', 'rejected'])->count(),
                'scored' => PpdbApplication::where('period_id', $activePeriod->id)->whereNotNull('scored_at')->count(),
                'ready' => PpdbApplication::where('period_id', $activePeriod->id)->where('status_pendaftaran', 'verified')->whereNotNull('scored_at')->count(),
                'needs_revision' => PpdbApplication::where('period_id', $activePeriod->id)->where('status_pendaftaran', 'needs_revision')->count(),
            ]
            : ['total' => 0, 'scored' => 0, 'ready' => 0, 'needs_revision' => 0];

        return view('livewire.admin.ppdb-test-scoring', compact('activePeriod', 'tracks', 'candidates', 'selectedApplication', 'summary', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin($resolver->resolveInput($this->period));
    }
}
