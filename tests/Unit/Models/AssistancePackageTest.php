<?php

use App\Enums\AssistancePackageStatus;
use App\Enums\AssistancePackageType;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Organization;

it('casts status and package_type to their enums', function () {
    $package = AssistancePackage::factory()->create([
        'title' => 'Food Basket',
        'status' => AssistancePackageStatus::Completed,
        'package_type' => AssistancePackageType::Food,
    ]);

    expect($package->title)->toBe('Food Basket')
        ->and($package->status)->toBe(AssistancePackageStatus::Completed)
        ->and($package->package_type)->toBe(AssistancePackageType::Food);
});

it('can calculate remaining quantity', function () {
    $package = AssistancePackage::factory()->create([
        'total_quantity' => 100,
        'distributed_quantity' => 25,
    ]);

    $remaining = $package->total_quantity - $package->distributed_quantity;

    expect($remaining)->toBe(75);
});

it('belongs to an organization', function () {
    $org = Organization::factory()->create();
    $package = AssistancePackage::factory()->for($org, 'organization')->create();

    expect($package->organization->id)->toBe($org->id);
});

it('has many distributions', function () {
    $package = AssistancePackage::factory()->create();
    AssistanceDistribution::factory()->count(3)->for($package, 'assistancePackage')->create();

    expect($package->distributions)->toHaveCount(3);
});
