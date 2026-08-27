<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            if (!Schema::hasColumn('artikels', 'prefix_url')) {
                $table->string('prefix_url')->default('artikel')->after('judul');
            }
        });
    }

    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn('prefix_url');
        });
    }
};