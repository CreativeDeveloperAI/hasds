<?php

namespace Database\Factories;

use App\Enums\AssistancePackageStatus;
use App\Enums\AssistancePackageType;
use App\Models\AssistancePackage;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssistancePackage>
 */
class AssistancePackageFactory extends Factory
{
    /**
     * عناوين واقعية لحزم المساعدة حسب نوعها
     */
    protected array $titlesByType = [
        'food' => [
            'سلة غذائية - الدورة الأولى', 'سلة غذائية - الدورة الثانية', 'طرد غذائي طارئ',
            'سلة غذائية شهرية', 'مساعدة غذائية طارئة - رمضان',
        ],
        'cash' => [
            'مساعدة نقدية طارئة', 'دعم نقدي شهري', 'منحة نقدية للأسر الأشد فقراً',
            'مساعدة نقدية - الإيجار',
        ],
        'medical' => [
            'دعم طبي - أدوية مزمنة', 'حزمة صحية للأطفال', 'مساعدة طبية طارئة',
            'دعم أجهزة طبية مساعدة',
        ],
        'clothing' => [
            'كسوة شتوية للأطفال', 'حزمة ملابس شتوية', 'كسوة عيد للأسر النازحة',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(AssistancePackageType::cases());
        $title = fake()->randomElement($this->titlesByType[$type->value]);
        $totalQuantity = fake()->numberBetween(50, 1000);
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(30, 120).' days');

        return [
            'organization_id' => Organization::factory(),
            'title' => $title,
            'package_type' => $type,
            'total_quantity' => $totalQuantity,
            'distributed_quantity' => 0,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement([
                AssistancePackageStatus::Active,
                AssistancePackageStatus::Active,
                AssistancePackageStatus::Active,
                AssistancePackageStatus::Completed,
                AssistancePackageStatus::Paused,
            ]),
            'description' => fake()->boolean(70) ? fake()->sentence(12) : null,

            // معايير الاستهداف الافتراضية (مفتوحة لكل المستفيدين ضمن نطاق السكور الكامل)
            'target_min_score' => 0,
            'target_max_score' => 100,
            'target_min_score_ai' => null,
            'target_max_score_ai' => null,
            'target_is_displaced' => false,
            'target_displacement_location' => null,
            'target_shelter_type' => null,
            'target_has_disability' => false,
            'target_has_recent_injury' => false,
            'target_has_chronic_disease' => false,
            'target_gender' => null,
            'target_marital_status' => null,
            'target_vital_status' => null,
            'target_has_children_under_5' => false,
            'target_has_elderly' => false,
            'target_has_pregnant_or_lactating' => false,
            'target_prev_assistance_filter' => 'none',
            'target_prev_assistance_type' => null,
            'target_prev_assistance_days' => 30,
        ];
    }

    /**
     * حزمة مكتملة التوزيع بالكامل
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AssistancePackageStatus::Completed,
            'distributed_quantity' => $attributes['total_quantity'] ?? 100,
        ]);
    }

    /**
     * حزمة نشطة قيد التوزيع حالياً
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AssistancePackageStatus::Active,
        ]);
    }

    /**
     * تفعيل معيار "الاستفادة السابقة" لاستبعاد/تضمين من استلم مساعدة مشابهة سابقاً
     * (لاختبار ميزة التدقيق المتقاطع بين المؤسسات - منع ازدواجية المساعدات)
     */
    public function withPreviousAssistanceCheck(string $filter = 'not_received', ?string $type = null, int $days = 30): static
    {
        return $this->state(fn (array $attributes) => [
            'target_prev_assistance_filter' => $filter,
            'target_prev_assistance_type' => $type,
            'target_prev_assistance_days' => $days,
        ]);
    }
}
