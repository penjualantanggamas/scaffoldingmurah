<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi')->default('Jawa Timur');
            $table->string('kota'); // Contoh: KOTA SURABAYA, KABUPATEN GRESIK, KABUPATEN SIDOARJO
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};