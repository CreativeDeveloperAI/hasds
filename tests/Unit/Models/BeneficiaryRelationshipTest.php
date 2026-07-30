<?php

use App\Models\Beneficiary;
use App\Models\BeneficiaryRelationship;
use Illuminate\Database\QueryException;

it('resolves the parent and relative beneficiary relations', function () {
    $parent = Beneficiary::factory()->create();
    $relative = Beneficiary::factory()->create();

    $relationship = BeneficiaryRelationship::factory()->create([
        'parent_beneficiary_id' => $parent->id,
        'relative_beneficiary_id' => $relative->id,
        'relation_type' => 'ابنة',
    ]);

    expect($relationship->parentBeneficiary->id)->toBe($parent->id)
        ->and($relationship->relativeBeneficiary->id)->toBe($relative->id)
        ->and($relationship->relation_type)->toBe('ابنة');
});

it('enforces a unique parent/relative pairing', function () {
    $parent = Beneficiary::factory()->create();
    $relative = Beneficiary::factory()->create();

    BeneficiaryRelationship::factory()->create([
        'parent_beneficiary_id' => $parent->id,
        'relative_beneficiary_id' => $relative->id,
    ]);

    expect(fn () => BeneficiaryRelationship::factory()->create([
        'parent_beneficiary_id' => $parent->id,
        'relative_beneficiary_id' => $relative->id,
    ]))->toThrow(QueryException::class);
});
