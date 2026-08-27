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
        Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('nama_armada'); // Contoh: Pickup, Engkel CDE, CDD, Fuso
        $table->decimal('max_berat_kg', 10, 2); // Kapasitas max tonase (Kg)
        $table->decimal('max_volume_m3', 10, 2); // Kapasitas max kubikasi (m3)
        $table->integer('urutan')->default(1); // 1 = terkecil, 4 = terbesar
        $table->boolean('is_aktif')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
