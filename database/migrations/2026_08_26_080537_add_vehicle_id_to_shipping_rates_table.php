<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_rates', 'vehicle_id')) {
                $table->foreignId('vehicle_id')->nullable()->after('kota')->constrained('vehicles')->onDelete('cascade');
            }
            if (!Schema::hasColumn('shipping_rates', 'biaya_per_km')) {
                $table->decimal('biaya_per_km', 12, 2)->nullable()->after('biaya_pengiriman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn(['vehicle_id', 'biaya_per_km']);
        });
    }
};