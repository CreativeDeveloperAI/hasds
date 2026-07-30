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
        // هجرة إعادة بناء الجدول لإضافة عمود id (2026_07_25_102250) أسقطت بالخطأ
        // فهرس unique(beneficiary_id, organization_id) الأصلي لأنها أعادت بناء الجدول
        // من نص CREATE TABLE فقط دون إعادة إنشاء فهارس UNIQUE المنفصلة المرتبطة به.
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->unique(['beneficiary_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->dropUnique(['beneficiary_id', 'organization_id']);
        });
    }
};
