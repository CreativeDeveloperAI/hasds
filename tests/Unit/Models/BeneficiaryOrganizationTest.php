<?php

use App\Enums\CurrentShelterType;
use App\Enums\ShelterCondition;
use App\Models\BeneficiaryOrganization;
use Illuminate\Database\QueryException;

it('casts pivot fields to their expected types', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create([
            'has_disability' => true,
            'monthly_income' => 1234.56,
            'custom_fields' => ['blood_type' => 'O+'],
        ])
    );

    expect($pivot->has_disability)->toBeTrue()
        ->and((float) $pivot->monthly_income)->toBe(1234.56)
        ->and($pivot->custom_fields)->toBe(['blood_type' => 'O+']);
});

it('maps tent, shelter center and host family to the displacement housing label', function (CurrentShelterType $type) {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create(['current_shelter_type' => $type])
    );

    expect($pivot->toAiHousingLabel())->toBe('مخيم ايواء/خيمة');
})->with([
    CurrentShelterType::Tent,
    CurrentShelterType::ShelterCenter,
    CurrentShelterType::HostFamily,
]);

it('maps a rented apartment to the rent housing label', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create(['current_shelter_type' => CurrentShelterType::RentApartment])
    );

    expect($pivot->toAiHousingLabel())->toBe('إيجار');
});

it('maps a bad-condition home to fully destroyed', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create([
            'current_shelter_type' => CurrentShelterType::Home,
            'shelter_condition' => ShelterCondition::Bad,
        ])
    );

    expect($pivot->toAiHousingLabel())->toBe('مدمر بالكامل');
});

it('maps an acceptable or good-condition home to partially destroyed', function (ShelterCondition $condition) {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create([
            'current_shelter_type' => CurrentShelterType::Home,
            'shelter_condition' => $condition,
        ])
    );

    expect($pivot->toAiHousingLabel())->toBe('مدمر جزئياً');
})->with([
    ShelterCondition::Acceptable,
    ShelterCondition::Good,
]);

it('falls back to partially destroyed when shelter type or condition is unknown', function () {
    $pivotUnknownType = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create(['current_shelter_type' => null])
    );
    $pivotUnknownCondition = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create([
            'current_shelter_type' => CurrentShelterType::Home,
            'shelter_condition' => null,
        ])
    );

    expect($pivotUnknownType->toAiHousingLabel())->toBe('مدمر جزئياً')
        ->and($pivotUnknownCondition->toAiHousingLabel())->toBe('مدمر جزئياً');
});

it('enforces a unique beneficiary/organization pairing', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create()
    );

    expect(fn () => BeneficiaryOrganization::withoutEvents(fn () => BeneficiaryOrganization::factory()->create([
        'beneficiary_id' => $pivot->beneficiary_id,
        'organization_id' => $pivot->organization_id,
    ])))->toThrow(QueryException::class);
});
