<?php

use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use App\Models\Organization;

it('excludes beneficiaries who recently received matching aid from a participating organization when filter is not_received', function () {
    $ownOrg = Organization::factory()->create();
    $otherOrg = Organization::factory()->create(['enable_cross_checking' => true]);

    $recipient = Beneficiary::factory()->create();
    $untouched = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($otherOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($otherOrg, 'organization')->create(['package_type' => 'food']),
        'delivered_at' => now()->subDays(5),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->not->toContain($recipient->id)
        ->and($eligibleIds)->toContain($untouched->id);
});

it('includes only beneficiaries who received matching aid when filter is received', function () {
    $ownOrg = Organization::factory()->create();
    $otherOrg = Organization::factory()->create(['enable_cross_checking' => true]);

    $recipient = Beneficiary::factory()->create();
    $untouched = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($otherOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($otherOrg, 'organization')->create(['package_type' => 'food']),
        'delivered_at' => now()->subDays(5),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($recipient->id)
        ->and($eligibleIds)->not->toContain($untouched->id);
});

it('does not filter anything when target_prev_assistance_filter is none', function () {
    $ownOrg = Organization::factory()->create();
    $beneficiary = Beneficiary::factory()->create();

    $package = AssistancePackage::factory()->for($ownOrg, 'organization')->create([
        'target_prev_assistance_filter' => 'none',
    ]);

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($beneficiary->id);
});

it('ignores distributions from organizations that opted out of cross-checking', function () {
    $ownOrg = Organization::factory()->create();
    $nonParticipatingOrg = Organization::factory()->create(['enable_cross_checking' => false]);

    $recipient = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($nonParticipatingOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($nonParticipatingOrg, 'organization')->create(['package_type' => 'food']),
        'delivered_at' => now()->subDays(5),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($recipient->id);
});

it('ignores distributions older than the configured day window', function () {
    $ownOrg = Organization::factory()->create();
    $otherOrg = Organization::factory()->create(['enable_cross_checking' => true]);

    $recipient = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($otherOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($otherOrg, 'organization')->create(['package_type' => 'food']),
        'delivered_at' => now()->subDays(45),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($recipient->id);
});

it('only counts distributions matching the configured previous-assistance type', function () {
    $ownOrg = Organization::factory()->create();
    $otherOrg = Organization::factory()->create(['enable_cross_checking' => true]);

    $recipient = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($otherOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($otherOrg, 'organization')->create(['package_type' => 'clothing']),
        'delivered_at' => now()->subDays(5),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'food', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($recipient->id);
});

it('always counts the packages own organization history regardless of its own enable_cross_checking value', function () {
    $ownOrg = Organization::factory()->create(['enable_cross_checking' => false]);

    $recipient = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($ownOrg, 'organization')->for($recipient, 'beneficiary')->delivered()->create([
        'assistance_package_id' => AssistancePackage::factory()->for($ownOrg, 'organization')->create(['package_type' => 'food']),
        'delivered_at' => now()->subDays(5),
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->not->toContain($recipient->id);
});

it('ignores distributions that were never actually delivered', function () {
    $ownOrg = Organization::factory()->create();
    $otherOrg = Organization::factory()->create(['enable_cross_checking' => true]);

    $recipient = Beneficiary::factory()->create();

    AssistanceDistribution::factory()->for($otherOrg, 'organization')->for($recipient, 'beneficiary')->create([
        'assistance_package_id' => AssistancePackage::factory()->for($otherOrg, 'organization')->create(['package_type' => 'food']),
        'distribution_status' => DistributionStatus::Pending,
        'delivered_at' => null,
    ]);

    $package = AssistancePackage::factory()
        ->for($ownOrg, 'organization')
        ->withPreviousAssistanceCheck('not_received', 'any', 30)
        ->create();

    $eligibleIds = $package->applyPreviousAssistanceFilter(Beneficiary::query())->pluck('id');

    expect($eligibleIds)->toContain($recipient->id);
});
