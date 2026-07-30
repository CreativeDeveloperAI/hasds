<?php

namespace Database\Factories;

use App\Enums\AiPriorityScoreStatus;
use App\Enums\CurrentShelterType;
use App\Enums\DisabilityType;
use App\Enums\InjurySeverity;
use App\Enums\ShelterCondition;
use App\Enums\SurveyStatus;
use App\Models\Beneficiary;
use App\Models\BeneficiaryOrganization;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BeneficiaryOrganization>
 */
class BeneficiaryOrganizationFactory extends Factory
{
    protected $model = BeneficiaryOrganization::class;

    protected array $displacementLocations = [
        'مخيم النصيرات', 'مخيم البريج', 'مدرسة الأونروا - الرمال', 'مركز إيواء خان يونس',
        'مخيم المواصي', 'مخيم دير البلح', 'منطقة المواصي - رفح', 'مدرسة إيواء - جباليا',
        'مخيم خيام النصيرات', 'خيام بيت لاهيا',
    ];

    protected array $incomeSources = [
        'عمل غير منتظم', 'مساعدات إغاثية', 'راتب حكومي متوقف', 'تجارة صغيرة',
        'عمل موسمي', 'دون مصدر دخل', 'حوالات من الخارج', 'عمل حرفي',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isDisplaced = fake()->boolean(60);
        $hasDisability = fake()->boolean(18);
        $hasRecentInjury = fake()->boolean(10);
        $hasChronicDisease = fake()->boolean(20);

        $shelterType = $isDisplaced
            ? fake()->randomElement([
                CurrentShelterType::Tent,
                CurrentShelterType::Tent,
                CurrentShelterType::ShelterCenter,
                CurrentShelterType::HostFamily,
                CurrentShelterType::RentApartment,
            ])
            : fake()->randomElement([CurrentShelterType::Home, CurrentShelterType::RentApartment]);

        $familyMembersCount = fake()->numberBetween(1, 10);

        return [
            'beneficiary_id' => Beneficiary::factory(),
            'organization_id' => Organization::factory(),
            'phone_number' => sprintf('+970 5%d-%03d-%04d', fake()->numberBetween(0, 9), fake()->numberBetween(0, 999), fake()->numberBetween(0, 9999)),
            'family_members_count' => $familyMembersCount,
            'children_under_5_count' => fake()->numberBetween(0, min(3, $familyMembersCount)),
            'elderly_count' => fake()->numberBetween(0, min(2, $familyMembersCount)),
            'pregnant_or_lactating_count' => fake()->boolean(15) ? fake()->numberBetween(1, 2) : 0,
            'has_chronic_disease' => $hasChronicDisease,
            'has_disability' => $hasDisability,
            'disability_type' => $hasDisability ? fake()->randomElement(DisabilityType::cases()) : null,
            'has_recent_injury' => $hasRecentInjury,
            'injury_severity' => $hasRecentInjury ? fake()->randomElement(InjurySeverity::cases()) : null,
            'is_displaced' => $isDisplaced,
            'current_shelter_type' => $shelterType,
            'shelter_condition' => fake()->randomElement(ShelterCondition::cases()),
            'current_displacement_location' => $isDisplaced ? fake()->randomElement($this->displacementLocations) : null,
            'monthly_income' => fake()->randomFloat(2, 0, 3000),
            'income_source' => fake()->randomElement($this->incomeSources),
            'has_alternative_assistance' => fake()->boolean(20),
            'priority_score' => 0,
            'survey_status' => fake()->randomElement([
                SurveyStatus::Active,
                SurveyStatus::Active,
                SurveyStatus::Active,
                SurveyStatus::Active,
                SurveyStatus::Archived,
            ]),
            'surveyed_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'custom_fields' => null,
            'ai_priority_score' => 0,
            'ai_priority_score_status' => AiPriorityScoreStatus::Pending,
        ];
    }

    /**
     * حالة صريحة: أسرة نازحة تسكن خيمة
     */
    public function displacedInTent(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_displaced' => true,
            'current_shelter_type' => CurrentShelterType::Tent,
            'current_displacement_location' => fake()->randomElement($this->displacementLocations),
        ]);
    }

    /**
     * حالة صريحة: أسرة تعاني من إعاقة أحد أفرادها
     */
    public function withDisability(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_disability' => true,
            'disability_type' => fake()->randomElement(DisabilityType::cases()),
        ]);
    }
}
