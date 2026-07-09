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
        Schema::create('custom_field_definitions', function (Blueprint $table) {
            $table->id();
            // ربط الحقل بالمؤسسة (كل مؤسسة لها حقولها، أو نتركه nullable لحقول النظام العامة)
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            $table->string('field_label'); // الاسم الظاهر للمستخدم (مثل: نوع الإعاقة)
            $table->string('field_key');   // الاسم البرمجي لتخزينه في الـ JSON (مثل: disability_type)

            // نوع الحقل لتوليد الواجهة المناسبة: text, number, select, boolean
            $table->string('field_type')->default('text');

            // خيارات الحقل إذا كان من نوع select (تُخزن كـ JSON مصفوفة من الخيارات)
            $table->json('options')->nullable();

            $table->boolean('is_required')->default(false); // هل الحقل إجباري أثناء التعبئة؟
            $table->timestamps();

            // منع تكرار نفس المفتاح البرمجي داخل نفس المؤسسة
            $table->unique(['organization_id', 'field_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_definitions');
    }
};
