<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menambahkan kolom opsi (boleh dikosongkan/nullable)
            $table->string('warna')->nullable()->after('spesifikasi'); // Contoh: Orange, Silver, Biru
            $table->string('ukuran')->nullable()->after('warna');       // Contoh: 1.7m, 1.9m, 2.2m
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['warna', 'ukuran']);
        });
    }
};