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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            // البيانات الأساسية والتوثيق
            $table->string('name'); // اسم المؤسسة (مثال: جمعية الهلال الأحمر)
            $table->string('license_number')->nullable()->unique(); // رقم الترخيص الرسمي لمنع التزوير
            $table->string('email')->unique(); // البريد الإلكتروني الرسمي للمؤسسة
            $table->string('phone'); // رقم التواصل

            // النطاق الجغرافي والعملياتي للمؤسسة
            $table->string('hq_address'); // عنوان المقر الرئيسي الحالي في غزة
            $table->string('primary_contact_person'); // اسم الشخص المسؤول (مدير الجمعية أو المنسق)

            // إعدادات الخصوصية والثقة (الحل الذكي لتبادل البيانات المجهلة)
            // هذا الحقل يسمح للمؤسسة باختيار ما إذا كانت تريد تشغيل التدقيق المتقاطع لمنع التكرار
            $table->boolean('enable_cross_checking')->default(true);

            // حالة الحساب الإدارية في نظامك (أنت كـ Super Admin)
            // 'pending': مسجلة جديد وتحتاج تفعيل، 'approved': نشطة، 'suspended': موقوفة مؤقتاً
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
