<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('label_alamat')->comment('Contoh: Proyek Ruko Surabaya, Gudang Gresik');
            $table->text('detail_jalan')->comment('Nama jalan, nomor rumah, nomor gedung');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kecamatan');
            $table->string('kode_pos', 10);
            $table->boolean('is_utama')->default(false)->comment('Penentu alamat pengiriman default');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};