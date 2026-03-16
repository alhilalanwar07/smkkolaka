<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->enum('status_pengumuman', ['draft', 'published'])->default('draft')->after('status');
            $table->timestamp('hasil_diumumkan_at')->nullable()->after('status_pengumuman');
            $table->text('catatan_pengumuman')->nullable()->after('hasil_diumumkan_at');
        });

        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->decimal('skor_akademik', 5, 2)->nullable()->after('nilai_rata_rata');
            $table->decimal('skor_prestasi', 5, 2)->nullable()->after('skor_akademik');
            $table->decimal('skor_afirmasi', 5, 2)->nullable()->after('skor_prestasi');
            $table->decimal('skor_tes_dasar', 5, 2)->nullable()->after('skor_afirmasi');
            $table->decimal('skor_wawancara', 5, 2)->nullable()->after('skor_tes_dasar');
            $table->decimal('skor_berkas', 5, 2)->nullable()->after('skor_wawancara');
            $table->enum('status_daftar_ulang', ['not_available', 'pending', 'submitted', 'verified', 'rejected'])->default('not_available')->after('selection_notes');
            $table->timestamp('daftar_ulang_at')->nullable()->after('status_daftar_ulang');
            $table->text('catatan_daftar_ulang')->nullable()->after('daftar_ulang_at');
            $table->foreignId('verified_daftar_ulang_by')->nullable()->after('catatan_daftar_ulang')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_daftar_ulang_at')->nullable()->after('verified_daftar_ulang_by');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_daftar_ulang_by');
            $table->dropColumn([
                'skor_akademik',
                'skor_prestasi',
                'skor_afirmasi',
                'skor_tes_dasar',
                'skor_wawancara',
                'skor_berkas',
                'status_daftar_ulang',
                'daftar_ulang_at',
                'catatan_daftar_ulang',
                'verified_daftar_ulang_at',
            ]);
        });

        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->dropColumn([
                'status_pengumuman',
                'hasil_diumumkan_at',
                'catatan_pengumuman',
            ]);
        });
    }
};