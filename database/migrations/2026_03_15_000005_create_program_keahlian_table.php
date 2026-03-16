<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_keahlian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurusan')->unique();
            $table->string('nama_jurusan');
            $table->string('slug')->unique();
            $table->text('deskripsi_lengkap')->nullable();
            $table->text('fasilitas_unggulan')->nullable();
            $table->text('prospek_karir')->nullable();
            $table->string('gambar_cover')->nullable();
            $table->boolean('status_tampil')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_keahlian');
    }
};
