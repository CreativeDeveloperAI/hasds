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
        Schema::create('scoring_policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_key')->unique(); // المفتاح المرجعي في الكود (مثل: weight_is_displaced, weight_female_breadwinner)
            $table->string('policy_name'); // الاسم العربي الواضح للمدراء (مثل: وزن حالة النزوح، وزن المعيل امرأة)
            $table->string('category'); // التصنيف لترتيب العرض (health, shelter, social, financial)
            $table->integer('points_weight')->default(0); // عدد النقاط الممنوحة عند تحقق هذا الشرط
            $table->boolean('is_active')->default(true); // تفعيل أو تعطيل هذه السياسة في الحساب
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_policies');
    }
};
