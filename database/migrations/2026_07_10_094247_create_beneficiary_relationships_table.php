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
        Schema::create('beneficiary_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_beneficiary_id')->constrained('beneficiaries')->cascadeOnDelete();
            $table->foreignId('relative_beneficiary_id')->constrained('beneficiaries')->cascadeOnDelete();
            $table->string('relation_type');
            $table->unique(['parent_beneficiary_id', 'relative_beneficiary_id'], 'parent_relative_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_relationships');
    }
};
