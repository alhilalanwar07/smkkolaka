<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_periods', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->string('tahun_ajaran');
            $table->date('tanggal_mulai_pendaftaran');
            $table->date('tanggal_selesai_pendaftaran');
            $table->date('tanggal_pengumuman')->nullable();
            $table->date('tanggal_mulai_daftar_ulang')->nullable();
            $table->date('tanggal_selesai_daftar_ulang')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'archived'])->default('draft');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('ppdb_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('ppdb_periods')->cascadeOnDelete();
            $table->string('nama_jalur');
            $table->string('slug');
            $table->text('deskripsi')->nullable();
            $table->boolean('status_tampil')->default(true);
            $table->boolean('requires_verification')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique(['period_id', 'slug']);
        });

        Schema::create('ppdb_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('ppdb_periods')->cascadeOnDelete();
            $table->foreignId('track_id')->nullable()->constrained('ppdb_tracks')->nullOnDelete();
            $table->foreignId('program_keahlian_id')->constrained('program_keahlian')->cascadeOnDelete();
            $table->unsignedInteger('kuota')->default(0);
            $table->unsignedInteger('kuota_terisi')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->unique(['period_id', 'track_id', 'program_keahlian_id'], 'ppdb_quotas_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_quotas');
        Schema::dropIfExists('ppdb_tracks');
        Schema::dropIfExists('ppdb_periods');
    }
};
