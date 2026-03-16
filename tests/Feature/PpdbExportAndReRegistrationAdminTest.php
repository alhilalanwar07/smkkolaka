<?php

namespace Tests\Feature;

use App\Livewire\Admin\Ppdb;
use App\Livewire\Admin\PpdbAnalytics;
use App\Livewire\Admin\PpdbReRegistration;
use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbExportAndReRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppdb_admin_can_download_export_results(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.ppdb.export'));

        $response->assertOk();
        $response->assertStreamed();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('content-disposition');

        $this->assertStringContainsString('Nomor Pendaftaran', $response->streamedContent());
    }

    public function test_ppdb_summary_page_can_switch_to_another_period(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $archivedPeriod = PpdbPeriod::where('status', 'archived')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(Ppdb::class)
            ->set('period', (string) $archivedPeriod->id)
            ->assertSee($archivedPeriod->tahun_ajaran)
            ->assertSee($archivedPeriod->gelombang_label)
            ->assertSee('Periode Dipilih');
    }

    public function test_super_admin_can_open_ppdb_analytics_page_for_selected_period(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();
        $archivedPeriod = PpdbPeriod::where('status', 'archived')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.analytics', ['periode' => $archivedPeriod->id]))
            ->assertOk()
            ->assertSee('Analisa PPDB')
            ->assertSee('Jangka Pendek')
            ->assertSee('Jangka Menengah')
            ->assertSee('Jangka Panjang')
            ->assertSee($archivedPeriod->tahun_ajaran);
    }

    public function test_ppdb_admin_can_download_filtered_re_registration_export(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $verifiedApplication = PpdbApplication::where('hasil_seleksi', 'passed')
            ->where('status_daftar_ulang', 'verified')
            ->firstOrFail();
        $pendingApplication = PpdbApplication::where('hasil_seleksi', 'passed')
            ->where('status_daftar_ulang', 'pending')
            ->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.ppdb.export', [
            'scope' => 're-registration',
            'registration_status' => 'verified',
        ]));

        $content = $response->streamedContent();

        $response->assertOk();
        $response->assertStreamed();
        $this->assertStringContainsString($verifiedApplication->nomor_pendaftaran, $content);
        $this->assertStringNotContainsString($pendingApplication->nomor_pendaftaran, $content);
        $this->assertStringContainsString('Diproses Oleh', $content);
    }

    public function test_admin_can_open_re_registration_verification_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.re-registration'))
            ->assertOk()
            ->assertSee('Finalisasi peserta yang sudah dinyatakan lulus');
    }

    public function test_panitia_can_verify_re_registration_from_dedicated_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $application = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->where('status_daftar_ulang', 'pending')
            ->firstOrFail();

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->call('openApplication', $application->id)
            ->set('verificationStatus', 'verified')
            ->set('verificationNote', 'Dokumen hadir lengkap dan daftar ulang dinyatakan sah.')
            ->call('saveVerification');

        $application->refresh();

        $this->assertSame('verified', $application->status_daftar_ulang);
        $this->assertNotNull($application->verified_daftar_ulang_at);
        $this->assertSame($admin->id, $application->verified_daftar_ulang_by);
        $this->assertSame('Dokumen hadir lengkap dan daftar ulang dinyatakan sah.', $application->catatan_daftar_ulang);
    }

    public function test_panitia_can_bulk_verify_re_registration_from_dedicated_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $applications = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->limit(2)
            ->get();

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->set('selectedIds', $applications->pluck('id')->map(fn ($id) => (string) $id)->all())
            ->set('bulkVerificationStatus', 'rejected')
            ->set('bulkVerificationNote', 'Perlu hadir ulang dengan dokumen tambahan.')
            ->call('applyBulkVerification');

        foreach ($applications as $application) {
            $application->refresh();

            $this->assertSame('rejected', $application->status_daftar_ulang);
            $this->assertSame('Perlu hadir ulang dengan dokumen tambahan.', $application->catatan_daftar_ulang);
            $this->assertSame($admin->id, $application->verified_daftar_ulang_by);
            $this->assertNotNull($application->verified_daftar_ulang_at);
        }
    }

    public function test_panitia_can_select_all_matching_re_registration_results_across_pages(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $template = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();

        $createdIds = [];

        foreach (range(1, 11) as $index) {
            $replica = $template->replicate();
            $replica->nomor_pendaftaran = 'PPDB-BULK-' . str_pad((string) $index, 3, '0', STR_PAD_LEFT);
            $replica->nisn = '99000000' . str_pad((string) $index, 2, '0', STR_PAD_LEFT);
            $replica->nik = '99000000000000' . str_pad((string) $index, 2, '0', STR_PAD_LEFT);
            $replica->nama_lengkap = 'Massal Pagination ' . $index;
            $replica->asal_sekolah = 'SMP Bulk Selection';
            $replica->status_daftar_ulang = 'submitted';
            $replica->catatan_daftar_ulang = null;
            $replica->verified_daftar_ulang_by = null;
            $replica->verified_daftar_ulang_at = null;
            $replica->daftar_ulang_at = now()->subMinutes($index);
            $replica->save();

            $createdIds[] = $replica->id;
        }

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->set('search', 'Massal Pagination')
            ->call('selectAllMatchingResults')
            ->set('bulkVerificationStatus', 'verified')
            ->set('bulkVerificationNote', 'Diproses lintas pagination.')
            ->call('applyBulkVerification')
            ->assertSet('selectedIds', []);

        $applications = PpdbApplication::query()->whereIn('id', $createdIds)->get();

        $this->assertCount(11, $applications);

        foreach ($applications as $application) {
            $this->assertSame('verified', $application->status_daftar_ulang);
            $this->assertSame('Diproses lintas pagination.', $application->catatan_daftar_ulang);
            $this->assertSame($admin->id, $application->verified_daftar_ulang_by);
            $this->assertNotNull($application->verified_daftar_ulang_at);
        }
    }

    public function test_re_registration_audit_can_be_filtered_by_officer_and_date(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $otherOfficer = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        $matchingAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $matchingAudit->update([
            'status_daftar_ulang' => 'verified',
            'catatan_daftar_ulang' => 'Audit cocok dengan filter.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDay(),
        ]);

        $nonMatchingOfficer = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($matchingAudit->id)
            ->firstOrFail();
        $nonMatchingOfficer->update([
            'status_daftar_ulang' => 'rejected',
            'catatan_daftar_ulang' => 'Audit petugas lain.',
            'verified_daftar_ulang_by' => $otherOfficer->id,
            'verified_daftar_ulang_at' => now()->subDay(),
        ]);

        $nonMatchingDate = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($matchingAudit->id)
            ->whereKeyNot($nonMatchingOfficer->id)
            ->firstOrFail();
        $nonMatchingDate->update([
            'status_daftar_ulang' => 'verified',
            'catatan_daftar_ulang' => 'Audit tanggal lain.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDays(5),
        ]);

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->set('auditOfficerFilter', (string) $admin->id)
            ->set('auditDateFrom', now()->subDays(2)->format('Y-m-d'))
            ->set('auditDateTo', now()->format('Y-m-d'))
            ->assertSee('Audit cocok dengan filter.')
            ->assertDontSee('Audit petugas lain.')
            ->assertDontSee('Audit tanggal lain.');
    }

    public function test_re_registration_audit_can_be_filtered_by_status(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();

        $verifiedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $verifiedAudit->update([
            'status_daftar_ulang' => 'verified',
            'catatan_daftar_ulang' => 'Audit verified saja.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDay(),
        ]);

        $rejectedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($verifiedAudit->id)
            ->firstOrFail();
        $rejectedAudit->update([
            'status_daftar_ulang' => 'rejected',
            'catatan_daftar_ulang' => 'Audit rejected saja.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subHours(12),
        ]);

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->set('auditStatusFilter', 'rejected')
            ->assertSee('Audit rejected saja.')
            ->assertDontSee('Audit verified saja.');
    }

    public function test_re_registration_audit_export_can_be_filtered_by_officer_and_date(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $otherOfficer = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        $matchingAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $matchingAudit->update([
            'status_daftar_ulang' => 'verified',
            'catatan_daftar_ulang' => 'Audit export cocok.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDay(),
        ]);

        $otherAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($matchingAudit->id)
            ->firstOrFail();
        $otherAudit->update([
            'status_daftar_ulang' => 'rejected',
            'catatan_daftar_ulang' => 'Audit export lain.',
            'verified_daftar_ulang_by' => $otherOfficer->id,
            'verified_daftar_ulang_at' => now()->subDays(4),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ppdb.export', [
            'scope' => 're-registration-audit',
            'audit_officer' => $admin->id,
            'audit_date_from' => now()->subDays(2)->format('Y-m-d'),
            'audit_date_to' => now()->format('Y-m-d'),
        ]));

        $content = $response->streamedContent();

        $response->assertOk();
        $response->assertStreamed();
        $this->assertStringContainsString('Catatan Audit', $content);
        $this->assertStringContainsString('Audit export cocok.', $content);
        $this->assertStringNotContainsString('Audit export lain.', $content);
    }

    public function test_re_registration_audit_export_can_be_filtered_by_status(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();

        $verifiedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $verifiedAudit->update([
            'status_daftar_ulang' => 'verified',
            'catatan_daftar_ulang' => 'Audit export verified.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subHours(10),
        ]);

        $rejectedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($verifiedAudit->id)
            ->firstOrFail();
        $rejectedAudit->update([
            'status_daftar_ulang' => 'rejected',
            'catatan_daftar_ulang' => 'Audit export rejected.',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subHours(8),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ppdb.export', [
            'scope' => 're-registration-audit',
            'audit_status' => 'rejected',
        ]));

        $content = $response->streamedContent();

        $response->assertOk();
        $response->assertStreamed();
        $this->assertStringContainsString('Audit export rejected.', $content);
        $this->assertStringNotContainsString('Audit export verified.', $content);
    }

    public function test_re_registration_page_shows_seven_day_trend_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $verifiedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $verifiedAudit->update([
            'status_daftar_ulang' => 'verified',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDay(),
        ]);

        $rejectedAudit = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($verifiedAudit->id)
            ->firstOrFail();
        $rejectedAudit->update([
            'status_daftar_ulang' => 'rejected',
            'verified_daftar_ulang_by' => $admin->id,
            'verified_daftar_ulang_at' => now()->subDays(2),
        ]);

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->assertSee('Tren 7 Hari')
            ->assertSee('Pergerakan audit finalisasi')
            ->assertSee('Verified: 1')
            ->assertSee('Rejected: 1');
    }

    public function test_re_registration_page_shows_officer_statistics(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $otherOfficer = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();
        $application = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();

        $application->update([
            'status_daftar_ulang' => 'rejected',
            'verified_daftar_ulang_by' => $otherOfficer->id,
            'verified_daftar_ulang_at' => now()->subHours(2),
        ]);

        Livewire::actingAs($admin)
            ->test(PpdbReRegistration::class)
            ->assertSee($otherOfficer->name)
            ->assertSee('Rejected 1');
    }

    public function test_ppdb_analytics_page_shows_re_registration_trend_and_sla(): void
    {
        $this->seed(DatabaseSeeder::class);

        $ppdbAdmin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();
        $superAdmin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->update([
                'status_daftar_ulang' => 'pending',
                'daftar_ulang_at' => null,
                'verified_daftar_ulang_by' => null,
                'verified_daftar_ulang_at' => null,
                'catatan_daftar_ulang' => null,
            ]);

        $submittedApplication = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->firstOrFail();
        $submittedApplication->update([
            'status_daftar_ulang' => 'submitted',
            'daftar_ulang_at' => now()->subDay(),
        ]);

        $processedApplication = PpdbApplication::query()
            ->where('hasil_seleksi', 'passed')
            ->whereKeyNot($submittedApplication->id)
            ->firstOrFail();
        $processedApplication->update([
            'status_daftar_ulang' => 'verified',
            'daftar_ulang_at' => now()->subHours(10),
            'verified_daftar_ulang_by' => $superAdmin->id,
            'verified_daftar_ulang_at' => now()->subHours(2),
        ]);

        Livewire::actingAs($ppdbAdmin)
            ->test(Ppdb::class)
            ->assertDontSee('Tren Daftar Ulang')
            ->assertDontSee('SLA Daftar Ulang');

        Livewire::actingAs($superAdmin)
            ->test(PpdbAnalytics::class)
            ->assertSee('Kualitas SLA verifikasi daftar ulang')
            ->assertSee('Masuk: 1')
            ->assertSee('Diproses: 1')
            ->assertSee('8 jam')
            ->assertSee('480 menit')
            ->assertSee('1/1');
    }
}