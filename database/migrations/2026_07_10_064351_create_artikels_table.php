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
    Schema::create('artikels', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('slug')->unique();
        $table->string('kategori'); // Contoh: Edukasi K3, Info Produk, Proyek
        $table->text('ringkasan');  // Untuk preview di halaman list
        $table->longText('konten'); // Isi artikel lengkap
        $table->string('gambar')->nullable();
        $table->integer('views')->default(0); // Menghitung total pembaca
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
