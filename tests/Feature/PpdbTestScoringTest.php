<?php

namespace Tests\Feature;

use App\Livewire\Admin\PpdbTestScoring;
use App\Models\PpdbApplication;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbTestScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_test_committee_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.tests'))
            ->assertOk()
            ->assertSee('Halaman penilaian cepat untuk panitia tes');
    }

    public function test_panitia_tes_can_save_scores_for_candidate(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $application = PpdbApplication::query()
            ->whereNotIn('status_pendaftaran', ['accepted', 'rejected'])
            ->firstOrFail();

        Livewire::actingAs($admin)
            ->test(PpdbTestScoring::class)
            ->call('openCandidate', $application->id)
            ->set('scoreStatus', 'verified')
            ->set('scoreBerkasStatus', 'verified')
            ->set('scoreAkademik', '91.5')
            ->set('scorePrestasi', '87')
            ->set('scoreAfirmasi', '10')
            ->set('scoreTesDasar', '89.5')
            ->set('scoreWawancara', '93')
            ->set('scoreBerkas', '95')
            ->set('scoreNote', 'Nilai tes dan wawancara sudah diverifikasi panitia.')
            ->call('saveScoring');

        $application->refresh();

        $this->assertSame('verified', $application->status_pendaftaran);
        $this->assertSame('verified', $application->status_berkas);
        $this->assertSame('91.50', $application->skor_akademik);
        $this->assertSame('89.50', $application->skor_tes_dasar);
        $this->assertSame('93.00', $application->skor_wawancara);
        $this->assertSame('95.00', $application->skor_berkas);
        $this->assertNotNull($application->scored_at);
        $this->assertSame('Nilai tes dan wawancara sudah diverifikasi panitia.', $application->catatan_verifikator);
    }
}