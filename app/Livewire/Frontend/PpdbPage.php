<?php

namespace App\Livewire\Frontend;

use App\Models\PpdbApplication;
use App\Models\ProgramKeahlian;
use App\Support\PpdbPeriodResolver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('PPDB Online - SMK Negeri 1 Kolaka')]
class PpdbPage extends Component
{
    #[Url(as: 'periode')]
    public string $selectedPeriod = '';

    public function mount(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        if ($selectedPeriod && $this->selectedPeriod === '') {
            $this->selectedPeriod = (string) $selectedPeriod->id;
        }
    }

    public function render()
    {
        $period = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->publicOptions();
        $selectedPeriodId = $period?->id;

        $applicationsCount = $period ? PpdbApplication::where('period_id', $period->id)->count() : 0;
        $acceptedCount = $period ? PpdbApplication::where('period_id', $period->id)->where('hasil_seleksi', 'passed')->count() : 0;
        $programsCount = ProgramKeahlian::tampil()->count();

        return view('livewire.frontend.ppdb-overview', compact('period', 'applicationsCount', 'acceptedCount', 'programsCount', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod()
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolvePublic(
            $resolver->resolveInput($this->selectedPeriod),
            [
                'tracks' => fn ($query) => $query->visible(),
                'quotas.programKeahlian',
            ]
        );
    }
}
