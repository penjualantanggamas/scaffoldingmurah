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
    Schema::table('produks', function (Blueprint $table) {
        // Tipe boolean, bawaannya bernilai false (0)
        $table->boolean('is_terlaris')->default(false)->after('harga_coret');
    });
}

public function down(): void
{
    Schema::table('produks', function (Blueprint $table) {
        $table->dropColumn('is_terlaris');
    });
}
};
