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
        Schema::create('assistance_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete(); // المستفيد المستلم (رب الأسرة)
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete(); // الجمعية الموزعة
            $table->foreignId('assistance_package_id')->constrained()->cascadeOnDelete(); // الحزمة المستلمة

            $table->string('distribution_status')->default('delivered'); // pending (مجدول), delivered (تم الاستلام), cancelled (ملغي)
            $table->timestamp('delivered_at')->nullable(); // التاريخ والساعة بالدقة لضبط العمليات الميدانية

            $table->decimal('cash_amount', 10, 2)->nullable(); // في حال كانت المساعدة نقدية (يسجل المبلغ المستلم بالشيكل/الدولار)
            $table->string('notes')->nullable(); // ملاحظات المسلم الميداني (مثل: تم التسليم بموجب توكيل رسمي)

            // منع تسجيل نفس المساعدة للمستفيد من نفس الدورة لضمان النزاهة
            $table->unique(['beneficiary_id', 'assistance_package_id'], 'b_package_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_distributions');
    }
};
