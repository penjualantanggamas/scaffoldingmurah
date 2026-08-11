<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained('produks')->onDelete('set null');
            $table->unsignedBigInteger('varian_id')->nullable();
            
            // Menyimpan snapshot data saat dibeli (jika produk diubah/dihapus admin di kemudian hari, invoice lama tetap akurat)
            $table->string('nama_produk');
            $table->string('ukuran_varian')->nullable();
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            
            $table->boolean('is_preorder')->default(false);
            $table->integer('waktu_preorder')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};