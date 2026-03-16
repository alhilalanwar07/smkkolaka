<?php

namespace Tests\Feature;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbSelectionStageTwoTest extends TestCase
{
    use RefreshDatabase;

    public function test_stage_two_selection_data_is_generated_by_seeder(): void
    {
        $this->seed(DatabaseSeeder::class);

        $rahmat = PpdbApplication::where('nama_lengkap', 'Rahmat Saputra')->firstOrFail();
        $period = PpdbPeriod::firstOrFail();

        $this->assertSame(PpdbApplication::count(), PpdbApplication::whereNotNull('skor_seleksi')->count());
        $this->assertGreaterThan(0, PpdbApplication::where('hasil_seleksi', 'passed')->count());
        $this->assertGreaterThan(0, PpdbApplication::where('hasil_seleksi', 'pending')->count() + PpdbApplication::where('hasil_seleksi', 'failed')->count());
        $this->assertTrue(PpdbApplication::where('hasil_seleksi', 'passed')->whereNotNull('program_diterima_id')->exists());
        $this->assertTrue(PpdbQuota::where('kuota_terisi', '>', 0)->exists());
        $this->assertSame('published', $period->status_pengumuman);
        $this->assertEqualsWithDelta(92.40, (float) $rahmat->skor_seleksi, 0.01);
        $this->assertSame('pending', PpdbApplication::where('hasil_seleksi', 'passed')->where('status_daftar_ulang', 'pending')->firstOrFail()->status_daftar_ulang);
        $this->assertTrue(PpdbApplication::where('status_daftar_ulang', 'verified')->exists());
    }
}