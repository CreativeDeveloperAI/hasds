<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرة لإضافة حقول الاستهداف الذكي والمفصل لجدول المساعدات
     */
    public function up(): void
    {
        Schema::table('assistance_packages', function (Blueprint $table) {
            // أ: معايير الاستهداف بالنقاط والموقع
            $table->integer('target_min_score')->default(0)->after('total_quantity');
            $table->integer('target_max_score')->default(100)->after('target_min_score');
            $table->boolean('target_is_displaced')->default(false)->after('target_max_score');
            $table->string('target_displacement_location')->nullable()->after('target_is_displaced');
            $table->string('target_shelter_type')->nullable()->after('target_displacement_location');

            // ب: معايير الاستهداف الصحي والطبي
            $table->boolean('target_has_disability')->default(false)->after('target_shelter_type');
            $table->boolean('target_has_recent_injury')->default(false)->after('target_has_disability');
            $table->boolean('target_has_chronic_disease')->default(false)->after('target_has_recent_injury');

            // ج: معايير الاستهداف السيادي والديموغرافي التفصيلي الجديد
            $table->string('target_gender')->nullable()->after('target_has_chronic_disease');
            $table->string('target_marital_status')->nullable()->after('target_gender');
            $table->string('target_vital_status')->nullable()->after('target_marital_status');

            $table->boolean('target_has_children_under_5')->default(false)->after('target_vital_status');
            $table->boolean('target_has_elderly')->default(false)->after('target_has_children_under_5');
            $table->boolean('target_has_pregnant_or_lactating')->default(false)->after('target_has_elderly');

            $table->string('target_prev_assistance_filter')->default('none')->after('target_has_pregnant_or_lactating'); // none, received, not_received
            $table->string('target_prev_assistance_type')->nullable()->after('target_prev_assistance_filter'); // food, cash, medical, clothing, any
            $table->integer('target_prev_assistance_days')->default(30)->after('target_prev_assistance_type');
        });
    }

    /**
     * التراجع عن الهجرة وحذف كافة الحقول المضافة
     */
    public function down(): void
    {
        Schema::table('assistance_packages', function (Blueprint $table) {
            $table->dropColumn([
                'target_min_score',
                'target_max_score',
                'target_is_displaced',
                'target_displacement_location',
                'target_shelter_type',
                'target_has_disability',
                'target_has_recent_injury',
                'target_has_chronic_disease',
                'target_gender',
                'target_marital_status',
                'target_vital_status',
                'target_has_children_under_5',
                'target_has_elderly',
                'target_has_pregnant_or_lactating',
                'target_prev_assistance_filter',
                'target_prev_assistance_type',
                'target_prev_assistance_days',
            ]);
        });
    }
};
