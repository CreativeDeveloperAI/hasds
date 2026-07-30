<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * تهيئة المؤسسات الشريكة (~8) وربط 1-2 موظف/باحث ميداني بكل مؤسسة.
     */
    public function run(): void
    {
        Organization::factory()
            ->count(8)
            ->create()
            ->each(function (Organization $organization) {
                User::factory()
                    ->count(fake()->numberBetween(1, 2))
                    ->forOrganization($organization)
                    ->create();
            });
    }
}
