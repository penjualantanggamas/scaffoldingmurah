<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_decorations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'banner_slider', 'category_pills', 'product_promo', 'product_hot', 'single_banner', 'usp_text'
            $table->string('judul')->nullable(); 
            $table->json('content')->nullable(); // Menampung array foto, link, ID produk, dsb.
            $table->integer('urutan')->default(0); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_decorations');
    }
};