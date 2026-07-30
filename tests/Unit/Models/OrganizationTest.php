<?php

use App\Enums\OrganizationStatus;
use App\Models\Beneficiary;
use App\Models\BeneficiaryOrganization;
use App\Models\CustomFieldDefinition;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\QueryException;

it('casts status to the OrganizationStatus enum and enable_cross_checking to boolean', function () {
    $org = Organization::factory()->create([
        'status' => OrganizationStatus::Approved,
        'enable_cross_checking' => 1,
    ]);

    expect($org->status)->toBe(OrganizationStatus::Approved)
        ->and($org->enable_cross_checking)->toBeTrue();
});

it('has many users', function () {
    $org = Organization::factory()->create();
    User::factory()->count(2)->forOrganization($org)->create();

    expect($org->users)->toHaveCount(2);
});

it('has many custom field definitions', function () {
    $org = Organization::factory()->create();
    CustomFieldDefinition::factory()->count(3)->create(['organization_id' => $org->id]);

    expect($org->customFieldDefinitions)->toHaveCount(3);
});

it('can attach beneficiaries with survey pivot data', function () {
    $org = Organization::factory()->create();
    $beneficiary = Beneficiary::factory()->create();

    BeneficiaryOrganization::withoutEvents(fn () => $org->beneficiaries()->attach($beneficiary->id, [
        'family_members_count' => 5,
        'is_displaced' => true,
    ]));

    $attached = $org->beneficiaries()->first();

    expect($attached->id)->toBe($beneficiary->id)
        ->and($attached->pivot->family_members_count)->toBe(5)
        ->and($attached->pivot->is_displaced)->toBeTrue();
});

it('enforces a unique email', function () {
    $existing = Organization::factory()->create();

    expect(fn () => Organization::factory()->create(['email' => $existing->email]))
        ->toThrow(QueryException::class);
});
