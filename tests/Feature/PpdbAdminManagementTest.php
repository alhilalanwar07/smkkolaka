<?php

namespace Tests\Feature;

use App\Livewire\Admin\PpdbSettings;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_period_schedule_from_ppdb_dashboard(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(PpdbSettings::class)
            ->set('periodForm.nama_periode', 'PPDB Final 2026/2027')
            ->set('periodForm.tanggal_mulai_pendaftaran', '2026-03-10')
            ->set('periodForm.tanggal_selesai_pendaftaran', '2026-05-31')
            ->set('periodForm.tanggal_pengumuman', '2026-06-07')
            ->set('periodForm.tanggal_mulai_daftar_ulang', '2026-06-08')
            ->set('periodForm.tanggal_selesai_daftar_ulang', '2026-06-14')
            ->call('savePeriodSettings');

        $period = PpdbPeriod::where('is_active', true)->firstOrFail();

        $this->assertSame('PPDB Final 2026/2027', $period->nama_periode);
        $this->assertSame('2026-03-10', $period->tanggal_mulai_pendaftaran?->format('Y-m-d'));
        $this->assertSame('2026-06-14', $period->tanggal_selesai_daftar_ulang?->format('Y-m-d'));
    }

    public function test_admin_can_update_quota_from_ppdb_dashboard(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();
        $activePeriod = PpdbPeriod::where('is_active', true)->firstOrFail();
        $quota = PpdbQuota::where('period_id', $activePeriod->id)->firstOrFail();

        Livewire::actingAs($admin)
            ->test(PpdbSettings::class)
            ->set('period', (string) $activePeriod->id)
            ->set("quotaSettings.{$quota->id}.kuota", 99)
            ->set("quotaSettings.{$quota->id}.status_aktif", false)
            ->call('saveQuotaSettings');

        $quota->refresh();

        $this->assertSame(99, $quota->kuota);
        $this->assertFalse($quota->status_aktif);
    }

    public function test_admin_can_create_new_period_from_existing_template(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();
        $activePeriod = PpdbPeriod::where('is_active', true)->firstOrFail();

        Livewire::actingAs($admin)
            ->test(PpdbSettings::class)
            ->set('period', (string) $activePeriod->id)
            ->set('newPeriodForm.nama_periode', 'PPDB Gelombang 1 2027/2028')
            ->set('newPeriodForm.tahun_ajaran', '2027/2028')
            ->set('newPeriodForm.tahun_mulai', 2027)
            ->set('newPeriodForm.tahun_selesai', 2028)
            ->set('newPeriodForm.gelombang_ke', 1)
            ->set('newPeriodForm.gelombang_label', 'Gelombang 1')
            ->set('newPeriodForm.tanggal_mulai_pendaftaran', '2027-03-01')
            ->set('newPeriodForm.tanggal_selesai_pendaftaran', '2027-05-31')
            ->set('newPeriodForm.tanggal_pengumuman', '2027-06-07')
            ->set('newPeriodForm.tanggal_mulai_daftar_ulang', '2027-06-08')
            ->set('newPeriodForm.tanggal_selesai_daftar_ulang', '2027-06-14')
            ->set('newPeriodForm.clone_template', true)
            ->call('createPeriod');

        $newPeriod = PpdbPeriod::where('nama_periode', 'PPDB Gelombang 1 2027/2028')->latest('id')->firstOrFail();

        $this->assertSame(2027, $newPeriod->tahun_mulai);
        $this->assertSame('Gelombang 1', $newPeriod->gelombang_label);
        $this->assertGreaterThan(0, $newPeriod->tracks()->count());
        $this->assertGreaterThan(0, $newPeriod->quotas()->count());
    }
}