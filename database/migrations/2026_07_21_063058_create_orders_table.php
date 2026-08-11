<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique()->comment('Contoh: TM-20260721-0001');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('customer_address_id')->nullable()->constrained('customer_addresses')->onDelete('set null');
            
            // Opsi Pengiriman
            $table->string('metode_pengiriman')->comment('ambil_sendiri, ekspedisi, armada_gudang');
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            
            // Informasi Pembayaran
            $table->string('metode_pembayaran')->default('transfer_bank');
            $table->decimal('subtotal_produk', 12, 2);
            $table->decimal('grand_total', 12, 2);
            
            // Status Pesanan
            $table->string('status_pembayaran')->default('menunggu_pembayaran')->comment('menunggu_pembayaran, dibayar, dibatalkan');
            $table->string('status_pesanan')->default('pending')->comment('pending, diproses, dikirim, selesai, dibatalkan');
            
            $table->text('catatan_pembeli')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};