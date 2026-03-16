<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbApplication;
use App\Support\PpdbPeriodResolver;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpdbExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $resolver = app(PpdbPeriodResolver::class);
        $activePeriod = $resolver->resolveAdmin($request->integer('period_id'));

        abort_unless($activePeriod, 404, 'Periode PPDB tidak ditemukan.');

        $scope = $request->string('scope')->value();
        $isAuditExport = $scope === 're-registration-audit';

        $applications = PpdbApplication::with(['track', 'pilihanProgram1', 'programDiterima', 'reRegistrationVerifier'])
            ->where('period_id', $activePeriod->id)
            ->when($scope === 're-registration', fn ($query) => $query->where('hasil_seleksi', 'passed'))
            ->when($isAuditExport, function ($query) use ($request) {
                $query->where('hasil_seleksi', 'passed')
                    ->whereIn('status_daftar_ulang', ['verified', 'rejected'])
                    ->whereNotNull('verified_daftar_ulang_at')
                    ->when($request->filled('audit_officer'), fn ($auditQuery) => $auditQuery->where('verified_daftar_ulang_by', $request->integer('audit_officer')))
                    ->when($request->filled('audit_status'), fn ($auditQuery) => $auditQuery->where('status_daftar_ulang', $request->string('audit_status')->value()))
                    ->when($request->filled('audit_date_from'), fn ($auditQuery) => $auditQuery->whereDate('verified_daftar_ulang_at', '>=', $request->string('audit_date_from')->value()))
                    ->when($request->filled('audit_date_to'), fn ($auditQuery) => $auditQuery->whereDate('verified_daftar_ulang_at', '<=', $request->string('audit_date_to')->value()));
            })
            ->when($request->filled('track_id'), fn ($query) => $query->where('track_id', $request->integer('track_id')))
            ->when($request->filled('registration_status'), fn ($query) => $query->where('status_daftar_ulang', $request->string('registration_status')->value()))
            ->when($request->filled('selection_result'), fn ($query) => $query->where('hasil_seleksi', $request->string('selection_result')->value()))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->value();

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$search}%");
                });
            })
            ->orderBy($isAuditExport ? 'verified_daftar_ulang_by' : 'track_id')
            ->orderByDesc($isAuditExport ? 'verified_daftar_ulang_at' : 'skor_seleksi')
            ->orderBy('nama_lengkap')
            ->get();

        $filenamePrefix = match ($scope) {
            're-registration' => 'hasil-daftar-ulang-ppdb',
            're-registration-audit' => 'audit-daftar-ulang-ppdb',
            default => 'hasil-ppdb',
        };

        $filename = $filenamePrefix . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($applications, $activePeriod, $isAuditExport): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $isAuditExport ? [
                'Periode',
                'Tahun Ajaran',
                'Nomor Pendaftaran',
                'Nama Lengkap',
                'Jalur',
                'Program Diterima',
                'Status Daftar Ulang',
                'Diproses Oleh',
                'Tanggal Diproses',
                'Catatan Audit',
            ] : [
                'Periode',
                'Tahun Ajaran',
                'Nomor Pendaftaran',
                'Nama Lengkap',
                'Asal Sekolah',
                'Jalur',
                'Pilihan 1',
                'Program Diterima',
                'Status Pendaftaran',
                'Status Berkas',
                'Hasil Seleksi',
                'Status Daftar Ulang',
                'Skor Seleksi',
                'Ranking Jalur',
                'Ranking Program',
                'Tanggal Submit',
                'Tanggal Daftar Ulang',
                'Diproses Oleh',
                'Tanggal Diproses',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, $isAuditExport ? [
                    $activePeriod->nama_periode,
                    $activePeriod->tahun_ajaran,
                    $application->nomor_pendaftaran,
                    $application->nama_lengkap,
                    $application->track?->nama_jalur,
                    $application->programDiterima?->nama_jurusan,
                    $application->status_daftar_ulang,
                    $application->reRegistrationVerifier?->name,
                    $application->verified_daftar_ulang_at?->format('Y-m-d H:i:s'),
                    $application->catatan_daftar_ulang,
                ] : [
                    $activePeriod->nama_periode,
                    $activePeriod->tahun_ajaran,
                    $application->nomor_pendaftaran,
                    $application->nama_lengkap,
                    $application->asal_sekolah,
                    $application->track?->nama_jalur,
                    $application->pilihanProgram1?->nama_jurusan,
                    $application->programDiterima?->nama_jurusan,
                    $application->status_pendaftaran,
                    $application->status_berkas,
                    $application->hasil_seleksi,
                    $application->status_daftar_ulang,
                    $application->skor_seleksi,
                    $application->ranking_jalur,
                    $application->ranking_program,
                    $application->submitted_at?->format('Y-m-d H:i:s'),
                    $application->daftar_ulang_at?->format('Y-m-d H:i:s'),
                    $application->reRegistrationVerifier?->name,
                    $application->verified_daftar_ulang_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}