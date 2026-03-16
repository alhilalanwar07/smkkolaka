<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_album', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul_album');
            $table->string('slug')->unique();
            $table->text('deskripsi_singkat')->nullable();
            $table->date('tanggal_kegiatan')->nullable();
            $table->timestamps();
        });

        Schema::create('galeri_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('galeri_album')->cascadeOnDelete();
            $table->enum('tipe_file', ['foto', 'video_url'])->default('foto');
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_item');
        Schema::dropIfExists('galeri_album');
    }
};
