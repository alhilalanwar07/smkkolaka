<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tefa_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('tefa_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_keahlian_id')->constrained('program_keahlian')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('tefa_kategori')->cascadeOnDelete();
            $table->string('nama_produk_jasa');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_estimasi', 15, 2)->nullable();
            $table->string('gambar_utama')->nullable();
            $table->enum('status_ketersediaan', ['tersedia', 'pre-order', 'arsip'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tefa_produk');
        Schema::dropIfExists('tefa_kategori');
    }
};
