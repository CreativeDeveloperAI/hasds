<?php

namespace Database\Factories;

use App\Enums\OrganizationStatus;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * أنواع المؤسسات الأهلية والخيرية الشائعة في السياق الفلسطيني
     */
    protected array $orgTypes = [
        'جمعية', 'مؤسسة', 'مركز', 'جمعية خيرية', 'منظمة',
    ];

    /**
     * أسماء وصفية لمهام ورسالة المؤسسة
     */
    protected array $missions = [
        'الإغاثة الخيرية', 'التنمية الإنسانية', 'الأمل', 'العطاء الإنساني',
        'الرحمة للإغاثة والتنمية', 'الصمود', 'البركة الخيرية', 'الخير للإغاثة',
        'التكافل الاجتماعي', 'النور للإغاثة الإنسانية', 'الوفاء الخيرية',
        'الرعاية الإنسانية', 'الإخاء الخيري', 'كرامة للإغاثة',
    ];

    /**
     * مناطق قطاع غزة الشهيرة لبناء عناوين واقعية
     */
    protected array $gazaAreas = [
        'مدينة غزة', 'خان يونس', 'دير البلح', 'رفح', 'جباليا', 'بيت لاهيا', 'النصيرات', 'بيت حانون',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement($this->orgTypes);
        $mission = fake()->randomElement($this->missions);
        $area = fake()->randomElement($this->gazaAreas);

        $name = fake()->boolean(50)
            ? "{$type} {$mission}"
            : "{$type} {$mission} - {$area}";

        return [
            'name' => $name,
            'license_number' => fake()->unique()->numerify('LIC-#######'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => sprintf('+970 5%d-%03d-%04d', fake()->numberBetween(0, 9), fake()->numberBetween(0, 999), fake()->numberBetween(0, 9999)),
            'hq_address' => "{$area} - ".fake()->streetName(),
            'primary_contact_person' => fake()->name(),
            'enable_cross_checking' => fake()->boolean(85),
            'status' => fake()->randomElement([
                OrganizationStatus::Approved,
                OrganizationStatus::Approved,
                OrganizationStatus::Approved,
                OrganizationStatus::Approved,
                OrganizationStatus::Pending,
                OrganizationStatus::Suspended,
            ]),
        ];
    }

    /**
     * حالة اعتماد صريحة للمؤسسة (approved)
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrganizationStatus::Approved,
        ]);
    }

    /**
     * حالة تعليق صريحة للمؤسسة (suspended)
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrganizationStatus::Suspended,
        ]);
    }

    /**
     * تعطيل التدقيق المتقاطع لمنع التكرار (لسيناريوهات اختبار خصوصية البيانات)
     */
    public function disabledCrossChecking(): static
    {
        return $this->state(fn (array $attributes) => [
            'enable_cross_checking' => false,
        ]);
    }
}
