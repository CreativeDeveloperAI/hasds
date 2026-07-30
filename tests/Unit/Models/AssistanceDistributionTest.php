<?php

use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use App\Models\Organization;
use Illuminate\Database\QueryException;

it('casts distribution_status to its enum and cash_amount to decimal', function () {
    $distribution = AssistanceDistribution::factory()->create([
        'distribution_status' => DistributionStatus::Delivered,
        'cash_amount' => 250.5,
    ]);

    expect($distribution->distribution_status)->toBe(DistributionStatus::Delivered)
        ->and((float) $distribution->cash_amount)->toBe(250.5);
});

it('resolves beneficiary, organization and assistancePackage relations', function () {
    $beneficiary = Beneficiary::factory()->create();
    $org = Organization::factory()->create();
    $package = AssistancePackage::factory()->for($org, 'organization')->create();

    $distribution = AssistanceDistribution::factory()->create([
        'beneficiary_id' => $beneficiary->id,
        'organization_id' => $org->id,
        'assistance_package_id' => $package->id,
    ]);

    expect($distribution->beneficiary->id)->toBe($beneficiary->id)
        ->and($distribution->organization->id)->toBe($org->id)
        ->and($distribution->assistancePackage->id)->toBe($package->id);
});

it('enforces a unique beneficiary per package', function () {
    $beneficiary = Beneficiary::factory()->create();
    $package = AssistancePackage::factory()->create();

    AssistanceDistribution::factory()->create([
        'beneficiary_id' => $beneficiary->id,
        'assistance_package_id' => $package->id,
    ]);

    expect(fn () => AssistanceDistribution::factory()->create([
        'beneficiary_id' => $beneficiary->id,
        'assistance_package_id' => $package->id,
    ]))->toThrow(QueryException::class);
});
