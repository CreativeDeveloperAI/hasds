<?php

namespace Database\Factories;

use App\Enums\PolicyCategory;
use App\Models\ScoringPolicie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScoringPolicie>
 */
class ScoringPolicieFactory extends Factory
{
    protected $model = ScoringPolicie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy_key' => fake()->unique()->slug(2, '_'),
            'policy_name' => 'سياسة تسجيل نقاط - '.fake()->words(2, true),
            'category' => fake()->randomElement(PolicyCategory::cases())->value,
            'points_weight' => fake()->numberBetween(1, 20),
            'is_active' => fake()->boolean(90),
            'description' => fake()->boolean(60) ? fake()->sentence(10) : null,
        ];
    }
}
