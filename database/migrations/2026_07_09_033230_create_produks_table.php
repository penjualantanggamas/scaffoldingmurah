<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id(); // Membuat kolom 'id' otomatis (Primary Key)
            $table->string('kategori'); // Untuk menyimpan teks kategori: 'frame', 'ringlock', 'tubular', 'kwikstage', 'bekisting'
            $table->string('nama_produk'); // Nama produk scaffolding
            $table->string('slug')->unique(); // URL ramah SEO, misal: 'main-frame-t190'
            $table->string('spesifikasi')->nullable(); // Spesifikasi produk, misal: 'Scaffolding galvanis T170' (boleh kosong)
            $table->integer('harga'); // Harga utama produk (Angka)
            $table->integer('harga_coret')->nullable(); // Harga sebelum diskon (boleh kosong)
            $table->string('gambar')->nullable(); // Menampung nama file foto produk (boleh kosong)
            $table->timestamps(); // Otomatis membuat kolom 'created_at' dan 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};