<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('kode_unik')->default(0)->after('biaya_pengiriman');
            $table->timestamp('expired_at')->nullable()->after('status_pembayaran');
        });
    }
    
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kode_unik', 'expired_at']);
        });
    }
};