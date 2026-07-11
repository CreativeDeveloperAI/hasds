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
        Schema::create('assistance_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete(); // الجمعية التي تقدم المساعدة
            $table->string('title'); // اسم المنحة/المساعدة (مثال: طرد غذائي طارئ - الدورة الأولى)
            $table->string('package_type'); // food (غذائي), cash (نقدي), medical (صحي), clothing (كسوة)
            $table->integer('total_quantity'); // الكمية الإجمالية المتوفرة للتوزيع
            $table->integer('distributed_quantity')->default(0); // الكمية التي تم توزيعها فعلياً
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_packages');
    }
};
