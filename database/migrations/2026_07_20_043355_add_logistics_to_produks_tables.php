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
        // 1. Menambah kolom logistik ke tabel Produk Utama
        Schema::table('produks', function (Blueprint $table) {
            $table->integer('berat')->nullable()->comment('Berat asli dalam gram');
            $table->integer('panjang')->nullable()->comment('Panjang dalam cm');
            $table->integer('lebar')->nullable()->comment('Lebar dalam cm');
            $table->integer('tinggi')->nullable()->comment('Tinggi dalam cm');
            $table->integer('maks_pembelian')->nullable()->comment('Batas beli per pesanan');
            
            // Fitur Pre-Order
            $table->boolean('is_preorder')->default(false);
            $table->integer('waktu_preorder')->nullable()->comment('Durasi PO dalam hari');
        });

        // 2. Menambah kolom logistik khusus ke tabel Varian (Beda ukuran, beda berat/dimensi)
        Schema::table('produk_varians', function (Blueprint $table) {
            $table->integer('berat')->nullable()->comment('Berat varian dalam gram');
            $table->integer('panjang')->nullable()->comment('Panjang varian dalam cm');
            $table->integer('lebar')->nullable()->comment('Lebar varian dalam cm');
            $table->integer('tinggi')->nullable()->comment('Tinggi varian dalam cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['berat', 'panjang', 'lebar', 'tinggi', 'maks_pembelian', 'is_preorder', 'waktu_preorder']);
        });

        Schema::table('produk_varians', function (Blueprint $table) {
            $table->dropColumn(['berat', 'panjang', 'lebar', 'tinggi']);
        });
    }
};