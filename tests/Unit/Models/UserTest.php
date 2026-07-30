<?php

use App\Models\Organization;
use App\Models\User;
use Filament\Panel;

it('lets an admin user (no organization) access only the admin panel', function () {
    $admin = User::factory()->create(['organization_id' => null, 'is_active' => true]);

    expect($admin->canAccessPanel(mockPanel('admin')))->toBeTrue()
        ->and($admin->canAccessPanel(mockPanel('organization')))->toBeFalse()
        ->and($admin->canAccessPanel(mockPanel('beneficiary')))->toBeFalse();
});

it('lets an active org staff user access only the organization panel', function () {
    $org = Organization::factory()->create();
    $staff = User::factory()->forOrganization($org)->create(['is_active' => true]);

    expect($staff->canAccessPanel(mockPanel('organization')))->toBeTrue()
        ->and($staff->canAccessPanel(mockPanel('admin')))->toBeFalse()
        ->and($staff->canAccessPanel(mockPanel('beneficiary')))->toBeFalse();
});

it('blocks an inactive org staff user from the organization panel', function () {
    $org = Organization::factory()->create();
    $staff = User::factory()->forOrganization($org)->inactive()->create();

    expect($staff->canAccessPanel(mockPanel('organization')))->toBeFalse();
});

it('blocks an inactive admin user from the admin panel', function () {
    $admin = User::factory()->create(['organization_id' => null, 'is_active' => false]);

    expect($admin->canAccessPanel(mockPanel('admin')))->toBeFalse();
});

it('resolves its own organization as the default and only tenant', function () {
    $org = Organization::factory()->create();
    $staff = User::factory()->forOrganization($org)->create();

    expect($staff->getDefaultTenant(mockPanel('organization'))->id)->toBe($org->id)
        ->and($staff->getTenants(mockPanel('organization'))->pluck('id'))->toEqual(collect([$org->id]));
});

it('can only access the tenant it belongs to', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $staff = User::factory()->forOrganization($orgA)->create();

    expect($staff->canAccessTenant($orgA))->toBeTrue()
        ->and($staff->canAccessTenant($orgB))->toBeFalse();
});

function mockPanel(string $id): Panel
{
    return Panel::make()->id($id);
}
