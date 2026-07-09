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
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            // وسائل الاتصال والمعايير الميدانية المتغيرة (تخص كل مؤسسة)
            $table->string('phone_number')->nullable(); // رقم جوال التواصل الحالي بالميدان
            $table->integer('family_members_count')->default(1); // عدد أفراد الأسرة
            $table->decimal('monthly_income', 10, 2)->default(0.00); // الدخل الشهري
            $table->boolean('is_displaced')->default(false); // حالة النزوح
            $table->string('current_shelter_type')->nullable(); // خيمة، مركز إيواء، الخ

            // حسابات أولوية الذكاء الاصطناعي لهذه المؤسسة بالذات
            $table->decimal('priority_score', 5, 2)->default(0.00);
            $table->string('survey_status')->default('active'); // active, archived, conflict

            $table->timestamp('surveyed_at')->nullable(); // تاريخ المسح للمقارنة الزمنية الذكية
            $table->json('custom_fields')->nullable(); // يحمل البيانات الديناميكية بصيغة مفتاح وقيمة (Key-Value)
            // منع المؤسسة من تكرار عمل أكثر من سجل تقييم نشط لنفس المستفيد
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
