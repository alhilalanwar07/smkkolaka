<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('ppdb_periods')->cascadeOnDelete();
            $table->foreignId('track_id')->constrained('ppdb_tracks')->cascadeOnDelete();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable()->index();
            $table->string('nik')->nullable()->index();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama')->nullable();
            $table->text('alamat_lengkap');
            $table->string('nomor_hp');
            $table->string('email')->nullable();
            $table->string('asal_sekolah');
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('nomor_hp_orang_tua')->nullable();
            $table->foreignId('pilihan_program_1_id')->constrained('program_keahlian')->cascadeOnDelete();
            $table->foreignId('pilihan_program_2_id')->nullable()->constrained('program_keahlian')->nullOnDelete();
            $table->decimal('nilai_rata_rata', 5, 2)->nullable();
            $table->text('catatan_pendaftar')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->enum('status_pendaftaran', ['draft', 'submitted', 'under_review', 'needs_revision', 'verified', 'accepted', 'rejected'])->default('submitted');
            $table->enum('status_berkas', ['pending', 'incomplete', 'complete', 'revision', 'verified'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ppdb_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('ppdb_applications')->cascadeOnDelete();
            $table->string('jenis_dokumen');
            $table->string('file_path');
            $table->enum('status_verifikasi', ['pending', 'approved', 'revision', 'rejected'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_documents');
        Schema::dropIfExists('ppdb_applications');
    }
};
