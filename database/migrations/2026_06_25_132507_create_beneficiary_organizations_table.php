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
        Schema::create('beneficiary_organization', function (Blueprint $table) {
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('phone_number')->nullable(); // رقم التواصل الميداني الحالي

            // 📊 ديموغرافيا النزوح الحالي للأسرة
            $table->integer('family_members_count')->default(1);
            $table->integer('children_under_5_count')->default(0);
            $table->integer('elderly_count')->default(0);
            $table->integer('pregnant_or_lactating_count')->default(0);

            // 🩺 الوضع الصحي الميداني (تم نقله هنا بناءً على طلبك وهو الأصح زمنيًا)
            $table->boolean('has_chronic_disease')->default(false); // أمراض مزمنة
            $table->boolean('has_disability')->default(false); // وجود إعاقة
            $table->string('disability_type')->nullable(); // حركية، بصرية، سمعية...
            $table->boolean('has_recent_injury')->default(false); // مصاب حرب (حديثاً)
            $table->string('injury_severity')->nullable(); // critical (حرجة), moderate (متوسطة)

            // ⛺ حالة المأوى والنزوح
            $table->boolean('is_displaced')->default(false);
            $table->string('current_shelter_type')->nullable(); // tent, shelter_center, rent, host
            $table->string('shelter_condition')->nullable(); // bad, acceptable
            $table->string('current_displacement_location')->nullable();

            // 💰 الوضع المادي والاقتصادي
            $table->decimal('monthly_income', 10, 2)->default(0.00);
            $table->string('income_source')->nullable();
            $table->boolean('has_alternative_assistance')->default(false);

            // 📊 الحوكمة والسكور
            $table->decimal('priority_score', 5, 2)->default(0.00);
            $table->string('survey_status')->default('active');
            $table->timestamp('surveyed_at')->nullable();
            $table->json('custom_fields')->nullable();

            $table->unique(['beneficiary_id', 'organization_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_organizations');
    }
};
