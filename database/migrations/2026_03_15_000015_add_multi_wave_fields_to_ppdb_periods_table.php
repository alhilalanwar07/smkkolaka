<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun_mulai')->nullable()->after('tahun_ajaran');
            $table->unsignedSmallInteger('tahun_selesai')->nullable()->after('tahun_mulai');
            $table->unsignedTinyInteger('gelombang_ke')->default(1)->after('tahun_selesai');
            $table->string('gelombang_label')->nullable()->after('gelombang_ke');
        });

        DB::table('ppdb_periods')->orderBy('id')->get()->each(function (object $period): void {
            preg_match('/(\d{4})\s*\/\s*(\d{4})/', (string) $period->tahun_ajaran, $yearMatches);
            preg_match('/gelombang\s+([0-9]+)/i', (string) $period->nama_periode, $waveMatches);

            $tahunMulai = isset($yearMatches[1]) ? (int) $yearMatches[1] : null;
            $tahunSelesai = isset($yearMatches[2]) ? (int) $yearMatches[2] : null;
            $gelombangKe = isset($waveMatches[1]) ? (int) $waveMatches[1] : 1;
            $gelombangLabel = 'Gelombang ' . $gelombangKe;

            DB::table('ppdb_periods')
                ->where('id', $period->id)
                ->update([
                    'tahun_mulai' => $tahunMulai,
                    'tahun_selesai' => $tahunSelesai,
                    'gelombang_ke' => $gelombangKe,
                    'gelombang_label' => $gelombangLabel,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->dropColumn([
                'tahun_mulai',
                'tahun_selesai',
                'gelombang_ke',
                'gelombang_label',
            ]);
        });
    }
};
