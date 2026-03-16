<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('npsn')->unique();
            $table->string('nama_sekolah');
            $table->text('alamat_lengkap');
            $table->string('koordinat_peta')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('email_resmi')->nullable();
            $table->json('tautan_sosmed')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->text('teks_sambutan_kepsek')->nullable();
            $table->string('foto_kepsek')->nullable();
            $table->text('visi_teks')->nullable();
            $table->text('misi_teks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_sekolah');
    }
};
