<?php

use App\Jobs\CalculateBeneficiaryPriorityScore;
use App\Models\BeneficiaryOrganization;
use Illuminate\Support\Facades\Bus;

it('always dispatches the scoring job when a pivot record is created', function () {
    Bus::fake();

    BeneficiaryOrganization::factory()->create();

    Bus::assertDispatched(CalculateBeneficiaryPriorityScore::class);
});

it('dispatches the scoring job when a watched field changes', function (string $field, mixed $value) {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create([
            'has_disability' => false,
            'monthly_income' => 100,
            'family_members_count' => 2,
            'current_shelter_type' => 'home',
            'shelter_condition' => 'good',
        ])
    );

    Bus::fake();

    $pivot->update([$field => $value]);

    Bus::assertDispatched(CalculateBeneficiaryPriorityScore::class);
})->with([
    'has_disability' => ['has_disability', true],
    'monthly_income' => ['monthly_income', 999.99],
    'family_members_count' => ['family_members_count', 9],
    'current_shelter_type' => ['current_shelter_type', 'tent'],
    'shelter_condition' => ['shelter_condition', 'bad'],
]);

it('does not dispatch the scoring job when an unrelated field changes', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create()
    );

    Bus::fake();

    $pivot->update(['income_source' => 'حوالات من الخارج']);

    Bus::assertNotDispatched(CalculateBeneficiaryPriorityScore::class);
});
