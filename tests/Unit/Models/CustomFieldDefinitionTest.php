<?php

use App\Models\CustomFieldDefinition;
use App\Models\Organization;
use Illuminate\Database\QueryException;

it('casts options to an array and is_required to boolean', function () {
    $field = CustomFieldDefinition::factory()->create([
        'field_type' => 'select',
        'options' => ['A+', 'A-'],
        'is_required' => 1,
    ]);

    expect($field->options)->toBe(['A+', 'A-'])
        ->and($field->is_required)->toBeTrue();
});

it('enforces a unique field_key per organization', function () {
    $org = Organization::factory()->create();

    CustomFieldDefinition::factory()->create([
        'organization_id' => $org->id,
        'field_key' => 'blood_type',
    ]);

    expect(fn () => CustomFieldDefinition::factory()->create([
        'organization_id' => $org->id,
        'field_key' => 'blood_type',
    ]))->toThrow(QueryException::class);
});

it('allows the same field_key across different organizations', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    CustomFieldDefinition::factory()->create(['organization_id' => $orgA->id, 'field_key' => 'blood_type']);
    $second = CustomFieldDefinition::factory()->create(['organization_id' => $orgB->id, 'field_key' => 'blood_type']);

    expect($second->exists)->toBeTrue();
});
