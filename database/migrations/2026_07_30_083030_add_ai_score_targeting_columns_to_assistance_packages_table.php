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
        Schema::table('assistance_packages', function (Blueprint $table) {
            $table->integer('target_min_score_ai')->nullable()->after('target_max_score');
            $table->integer('target_max_score_ai')->nullable()->after('target_min_score_ai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_packages', function (Blueprint $table) {
            $table->dropColumn(['target_min_score_ai', 'target_max_score_ai']);
        });
    }
};
