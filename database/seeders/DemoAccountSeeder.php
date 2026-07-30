<?php

namespace Database\Seeders;

use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use App\Models\BeneficiaryOrganization;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountSeeder extends Seeder
{
    /**
     * ثلاثة حسابات ثابتة (Admin / Organization Staff / Beneficiary) بكلمات سر معروفة مسبقاً،
     * تُستخدم فقط لمراجعة المشروع على بيئة العرض التجريبي، بمعزل عن البيانات العشوائية.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hasds.test'],
            [
                'name' => 'Demo Platform Admin',
                'password' => Hash::make('password'),
                'organization_id' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $organization = Organization::updateOrCreate(
            ['email' => 'org@hasds.test'],
            [
                'name' => 'مؤسسة العرض التجريبي',
                'license_number' => 'DEMO-0001',
                'phone' => '+970 59-000-0001',
                'hq_address' => 'غزة، فلسطين',
                'primary_contact_person' => 'Demo Organization Staff',
                'enable_cross_checking' => true,
                'status' => 'approved',
            ]
        );

        $orgStaff = User::updateOrCreate(
            ['email' => 'org@hasds.test'],
            [
                'name' => 'Demo Organization Staff',
                'password' => Hash::make('password'),
                'organization_id' => $organization->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $beneficiaryDob = '1990-01-01';

        $beneficiary = Beneficiary::updateOrCreate(
            ['national_id' => '900000001'],
            [
                'full_name' => 'مستفيد العرض التجريبي',
                'gender' => 'male',
                'date_of_birth' => $beneficiaryDob,
                'password' => Hash::make($beneficiaryDob),
                'personal_phone' => '+970 59-000-0002',
                'marital_status' => 'married',
                'vital_status' => 'alive',
            ]
        );

        if (! $organization->beneficiaries()->where('beneficiary_id', $beneficiary->id)->exists()) {
            $pivotData = BeneficiaryOrganization::factory()->definition();
            unset($pivotData['beneficiary_id'], $pivotData['organization_id']);

            $organization->beneficiaries()->attach($beneficiary->id, [
                ...$pivotData,
                'family_members_count' => 5,
                'is_displaced' => true,
                'priority_score' => 72.5,
            ]);
        }

        $package = AssistancePackage::firstOrCreate(
            ['organization_id' => $organization->id, 'title' => 'سلة العرض التجريبي'],
            [
                'package_type' => 'food',
                'total_quantity' => 10,
                'distributed_quantity' => 0,
                'status' => 'active',
                'description' => 'حزمة مساعدة توضيحية لمراجعة النظام على بيئة العرض.',
            ]
        );

        AssistanceDistribution::firstOrCreate(
            ['beneficiary_id' => $beneficiary->id, 'assistance_package_id' => $package->id],
            [
                'organization_id' => $organization->id,
                'distribution_status' => 'delivered',
                'delivered_at' => now()->subDays(3),
                'notes' => 'توزيع توضيحي لمراجعة بوابة المستفيد.',
            ]
        );

        $this->command?->info('Demo accounts ready:');
        $this->command?->info('  Admin:        admin@hasds.test / password');
        $this->command?->info('  Organization: org@hasds.test / password');
        $this->command?->info("  Beneficiary:  national_id=900000001 / dob(password)={$beneficiaryDob}");
    }
}
