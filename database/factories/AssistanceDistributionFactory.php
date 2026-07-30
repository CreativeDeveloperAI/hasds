<?php

namespace Database\Factories;

use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssistanceDistribution>
 */
class AssistanceDistributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            DistributionStatus::Delivered,
            DistributionStatus::Delivered,
            DistributionStatus::Delivered,
            DistributionStatus::Delivered,
            DistributionStatus::Pending,
            DistributionStatus::Cancelled,
        ]);

        return [
            'beneficiary_id' => Beneficiary::factory(),
            'organization_id' => Organization::factory(),
            'assistance_package_id' => AssistancePackage::factory(),
            'distribution_status' => $status,
            'delivered_at' => $status === DistributionStatus::Delivered
                ? fake()->dateTimeBetween('-6 months', 'now')
                : null,
            'cash_amount' => null,
            'notes' => fake()->boolean(20) ? 'تم التسليم بموجب توكيل رسمي من رب الأسرة' : null,
        ];
    }

    /**
     * حالة تسليم فعلي ناجح للمساعدة
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'distribution_status' => DistributionStatus::Delivered,
            'delivered_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * مساعدة نقدية مع تحديد المبلغ المستلم (تستخدم مع حزم من نوع Cash)
     */
    public function cash(?float $amount = null): static
    {
        return $this->state(fn (array $attributes) => [
            'cash_amount' => $amount ?? fake()->randomFloat(2, 100, 1500),
        ]);
    }
}
