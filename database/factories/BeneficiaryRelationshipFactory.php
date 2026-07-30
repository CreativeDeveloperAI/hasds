<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use App\Models\BeneficiaryRelationship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BeneficiaryRelationship>
 */
class BeneficiaryRelationshipFactory extends Factory
{
    /**
     * أنواع صلات القرابة الأسرية الشائعة
     */
    protected array $relationTypes = [
        'زوج', 'زوجة', 'ابن', 'ابنة', 'أب', 'أم', 'أخ', 'أخت',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_beneficiary_id' => Beneficiary::factory(),
            'relative_beneficiary_id' => Beneficiary::factory(),
            'relation_type' => fake()->randomElement($this->relationTypes),
        ];
    }
}
