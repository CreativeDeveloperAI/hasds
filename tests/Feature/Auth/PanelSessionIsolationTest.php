<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('keeps admin and organization panel logins independent in the same session', function () {
    $admin = User::factory()->create();
    $organization = Organization::factory()->create();
    $orgUser = User::factory()->forOrganization($organization)->create();

    Auth::guard('web')->login($admin);
    Auth::guard('organization_guard')->login($orgUser);

    expect(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($admin->id)
        ->and(Auth::guard('organization_guard')->check())->toBeTrue()
        ->and(Auth::guard('organization_guard')->id())->toBe($orgUser->id);
});

it('logging into the organization guard does not log out the web guard', function () {
    $admin = User::factory()->create();
    $organization = Organization::factory()->create();
    $orgUser = User::factory()->forOrganization($organization)->create();

    Auth::guard('web')->login($admin);
    expect(Auth::guard('web')->check())->toBeTrue();

    Auth::guard('organization_guard')->login($orgUser);

    expect(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($admin->id);
});
