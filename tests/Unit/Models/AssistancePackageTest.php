<?php

use App\Enums\AssistancePackageStatus;
use App\Models\AssistancePackage;

it('can check if package is completed', function () {
    $package = new AssistancePackage([
        'name' => 'Food Basket',
        'status' => AssistancePackageStatus::Completed,
    ]);

    expect($package->status)->toBe(AssistancePackageStatus::Completed);
});

it('can calculate remaining quantity', function () {
    $package = new AssistancePackage([
        'total_quantity' => 100,
        'distributed_quantity' => 25,
    ]);

    $remaining = $package->total_quantity - $package->distributed_quantity;

    expect($remaining)->toBe(75);
});
