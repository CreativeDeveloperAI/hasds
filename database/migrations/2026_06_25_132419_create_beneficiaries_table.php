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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('national_id')->unique();
            $table->string('full_name');
            $table->string('gender'); // male (ذكر) ، female (أنثى)
            $table->date('date_of_birth')->nullable();
            $table->string('password');

            // رقم الهاتف الثابت الخاص بالمواطن لتسجيل الدخول وإدارة بياناته بمعزل عن الجمعيات
            $table->string('personal_phone')->nullable();

            // الحالة الاجتماعية والتركيبة السيادية
            $table->string('marital_status')->default('married'); // married, single, widowed, divorced
            $table->string('vital_status')->default('alive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
