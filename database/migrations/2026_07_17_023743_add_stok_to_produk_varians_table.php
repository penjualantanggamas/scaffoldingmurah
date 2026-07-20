<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk_varians', function (Blueprint $table) {
            // Menambahkan kolom stok di tabel varian tepat setelah kolom harga_coret
            $table->integer('stok')->default(0)->after('harga_coret');
        });
    }

    public function down(): void
    {
        Schema::table('produk_varians', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};