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
    Schema::create('produk_varians', function (Blueprint $table) {
        $table->id();
        // Menghubungkan varian ke tabel utama 'produks'
        $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
        
        $table->string('ukuran'); // Contoh: 1.7m, 1.9m
        $table->decimal('harga', 12, 2); // Harga khusus ukuran ini
        $table->decimal('harga_coret', 12, 2)->nullable(); // Harga coret khusus ukuran ini
        $table->string('gambar')->nullable(); // Foto khusus ukuran ini
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_varians');
    }
};
