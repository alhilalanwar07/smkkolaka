<?php

namespace Tests\Feature;

use App\Livewire\Admin\Ppdb;
use App\Livewire\Frontend\PpdbDaftarUlang;
use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbAnnouncementAndReRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_official_announcement(): void
    {
        $this->seed(DatabaseSeeder::class);

        $period = PpdbPeriod::where('is_active', true)->firstOrFail();
        $period->update([
            'status_pengumuman' => 'draft',
            'hasil_diumumkan_at' => null,
        ]);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(Ppdb::class)
            ->set('period', (string) $period->id)
            ->call('publishAnnouncement');

        $period->refresh();

        $this->assertSame('published', $period->status_pengumuman);
        $this->assertNotNull($period->hasil_diumumkan_at);
    }

    public function test_passed_student_can_submit_re_registration_after_official_announcement(): void
    {
        $this->seed(DatabaseSeeder::class);

        $application = PpdbApplication::where('hasil_seleksi', 'passed')
            ->where('status_daftar_ulang', 'pending')
            ->firstOrFail();

        Livewire::test(PpdbDaftarUlang::class)
            ->set('nomor_pendaftaran', $application->nomor_pendaftaran)
            ->set('tanggal_lahir', $application->tanggal_lahir->format('Y-m-d'))
            ->call('search')
            ->set('catatan_daftar_ulang', 'Siap hadir sesuai jadwal daftar ulang resmi.')
            ->call('submitReRegistration');

        $application->refresh();

        $this->assertSame('submitted', $application->status_daftar_ulang);
        $this->assertNotNull($application->daftar_ulang_at);
        $this->assertSame('Siap hadir sesuai jadwal daftar ulang resmi.', $application->catatan_daftar_ulang);
    }
}