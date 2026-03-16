<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul_pengumuman');
            $table->string('slug')->unique();
            $table->longText('isi_pengumuman');
            $table->string('file_lampiran_path')->nullable();
            $table->date('tanggal_mulai_tampil')->nullable();
            $table->date('tanggal_akhir_tampil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
