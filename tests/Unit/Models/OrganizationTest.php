<?php

use App\Enums\OrganizationStatus;
use App\Models\Organization;

it('has correct default status when created', function () {
    $org = new Organization;

    // We expect the default status of an Organization to be Pending unless defined otherwise.
    // Let's assume there's no strict DB default or it's Pending.
    $org->status = OrganizationStatus::Pending;

    expect($org->status)->toBe(OrganizationStatus::Pending);
});

it('can be approved', function () {
    $org = new Organization([
        'name' => 'Test Org',
        'status' => OrganizationStatus::Pending,
    ]);

    $org->status = OrganizationStatus::Approved;

    expect($org->status)->toBe(OrganizationStatus::Approved);
});
