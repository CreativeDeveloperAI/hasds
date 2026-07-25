<?php

use App\Enums\AiPriorityScoreStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->decimal('ai_priority_score', 5, 2)
                ->default(0.00)
                ->after('priority_score');
            $table->string('ai_priority_score_status')->default(AiPriorityScoreStatus::Pending);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->dropColumn('ai_priority_score');
            $table->dropColumn('ai_priority_score_status');
        });
    }
};
