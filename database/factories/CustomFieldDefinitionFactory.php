<?php

namespace Database\Factories;

use App\Models\CustomFieldDefinition;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomFieldDefinition>
 */
class CustomFieldDefinitionFactory extends Factory
{
    /**
     * أزواج (تسمية عربية => مفتاح برمجي => نوع الحقل [=> خيارات]) شائعة الاستخدام ميدانياً
     * (public static ليتم إعادة استخدامها من الـ Seeder لضمان عدم تكرار field_key داخل نفس المؤسسة)
     */
    public static array $fieldPairs = [
        ['label' => 'فصيلة الدم', 'key' => 'blood_type', 'type' => 'select', 'options' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']],
        ['label' => 'احتياج الشتاء', 'key' => 'winter_need', 'type' => 'boolean', 'options' => null],
        ['label' => 'رقم بطاقة التموين', 'key' => 'ration_card_number', 'type' => 'text', 'options' => null],
        ['label' => 'عدد الغرف بالمسكن', 'key' => 'rooms_count', 'type' => 'number', 'options' => null],
        ['label' => 'نوع الاحتياج الطبي', 'key' => 'medical_need_type', 'type' => 'select', 'options' => ['أدوية مزمنة', 'أدوية إسعافية', 'أجهزة طبية', 'متابعة نفسية']],
        ['label' => 'حالة الوثائق الثبوتية', 'key' => 'documentation_status', 'type' => 'select', 'options' => ['مكتملة', 'ناقصة', 'مفقودة']],
        ['label' => 'ملاحظات الباحث الميداني', 'key' => 'field_researcher_notes', 'type' => 'text', 'options' => null],
        ['label' => 'عدد الأطفال الملتحقين بالمدرسة', 'key' => 'schooled_children_count', 'type' => 'number', 'options' => null],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pair = fake()->randomElement(self::$fieldPairs);

        return [
            'organization_id' => Organization::factory(),
            'field_label' => $pair['label'],
            'field_key' => $pair['key'],
            'field_type' => $pair['type'],
            'options' => $pair['options'],
            'is_required' => fake()->boolean(30),
        ];
    }
}
