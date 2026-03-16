<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_berita')->cascadeOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten_html');
            $table->string('gambar_thumbnail')->nullable();
            $table->enum('status_publikasi', ['draft', 'published', 'archived'])->default('draft');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
        Schema::dropIfExists('kategori_berita');
    }
};
