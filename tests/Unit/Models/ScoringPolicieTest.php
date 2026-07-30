<?php

use App\Models\ScoringPolicie;
use Illuminate\Database\QueryException;

it('casts is_active to boolean and points_weight to integer', function () {
    $policy = ScoringPolicie::factory()->create([
        'is_active' => 1,
        'points_weight' => '15',
    ]);

    expect($policy->is_active)->toBeTrue()
        ->and($policy->points_weight)->toBe(15);
});

it('enforces a unique policy_key', function () {
    $existing = ScoringPolicie::factory()->create();

    expect(fn () => ScoringPolicie::factory()->create(['policy_key' => $existing->policy_key]))
        ->toThrow(QueryException::class);
});
