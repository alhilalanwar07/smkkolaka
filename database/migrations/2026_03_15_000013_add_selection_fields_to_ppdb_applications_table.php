<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->decimal('skor_seleksi', 6, 2)->nullable()->after('nilai_rata_rata');
            $table->unsignedInteger('ranking_jalur')->nullable()->after('skor_seleksi');
            $table->unsignedInteger('ranking_program')->nullable()->after('ranking_jalur');
            $table->enum('hasil_seleksi', ['pending', 'passed', 'reserve', 'failed'])->default('pending')->after('status_berkas');
            $table->foreignId('program_diterima_id')->nullable()->after('hasil_seleksi')->constrained('program_keahlian')->nullOnDelete();
            $table->text('selection_notes')->nullable()->after('program_diterima_id');
            $table->timestamp('scored_at')->nullable()->after('selection_notes');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('program_diterima_id');
            $table->dropColumn([
                'skor_seleksi',
                'ranking_jalur',
                'ranking_program',
                'hasil_seleksi',
                'selection_notes',
                'scored_at',
            ]);
        });
    }
};