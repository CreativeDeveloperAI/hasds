<?php

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use App\Models\Beneficiary;
use App\Models\BeneficiaryOrganization;
use App\Models\Organization;
use Illuminate\Database\QueryException;

it('casts gender, marital_status and vital_status to their enums', function () {
    $beneficiary = Beneficiary::factory()->male()->create();

    expect($beneficiary->gender)->toBe(Gender::Male)
        ->and($beneficiary->marital_status)->toBeInstanceOf(MaritalStatus::class)
        ->and($beneficiary->vital_status)->toBeInstanceOf(VitalStatus::class);
});

it('uses national_id as the auth identifier for the beneficiary guard', function () {
    $beneficiary = Beneficiary::factory()->create();

    expect($beneficiary->getAuthIdentifierName())->toBe('national_id')
        ->and($beneficiary->getFilamentName())->toBe($beneficiary->full_name);
});

it('can belong to multiple organizations with independent pivot survey data', function () {
    $beneficiary = Beneficiary::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    BeneficiaryOrganization::withoutEvents(function () use ($beneficiary, $orgA, $orgB) {
        $beneficiary->organizations()->attach($orgA->id, ['family_members_count' => 3]);
        $beneficiary->organizations()->attach($orgB->id, ['family_members_count' => 7]);
    });

    $refreshed = $beneficiary->organizations()->get();

    expect($refreshed)->toHaveCount(2)
        ->and($refreshed->firstWhere('id', $orgA->id)->pivot->family_members_count)->toBe(3)
        ->and($refreshed->firstWhere('id', $orgB->id)->pivot->family_members_count)->toBe(7);
});

it('tracks parent/relative family relationships in both directions', function () {
    $parent = Beneficiary::factory()->create();
    $child = Beneficiary::factory()->create();

    $parent->relatives()->attach($child->id, ['relation_type' => 'ابن']);

    expect($parent->relatives()->first()->id)->toBe($child->id)
        ->and($parent->relatives()->first()->pivot->relation_type)->toBe('ابن')
        ->and($child->parents()->first()->id)->toBe($parent->id);
});

it('enforces a unique national_id', function () {
    $existing = Beneficiary::factory()->create();

    expect(fn () => Beneficiary::factory()->create(['national_id' => $existing->national_id]))
        ->toThrow(QueryException::class);
});
