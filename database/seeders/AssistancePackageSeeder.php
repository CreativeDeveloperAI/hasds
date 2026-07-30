<?php

namespace Database\Seeders;

use App\Models\AssistancePackage;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class AssistancePackageSeeder extends Seeder
{
    /**
     * تهيئة 2-4 حزم مساعدة لكل مؤسسة (أنواع وحالات متنوعة)، مع منح بعض المؤسسات
     * حزمة إضافية صريحة بمعيار "لم يستفد سابقاً" (target_prev_assistance_filter = not_received)
     * لتفعيل سيناريو التدقيق المتقاطع بين المؤسسات على بيانات واقعية.
     */
    public function run(): void
    {
        $organizations = Organization::all();

        $organizations->each(function (Organization $organization) {
            AssistancePackage::factory()
                ->count(fake()->numberBetween(2, 4))
                ->create(['organization_id' => $organization->id]);
        });

        // المؤسسات "الثانوية" التي سجّلت مستفيدين مزدوجي الانتساب هي أفضل مرشح لإظهار قيمة
        // ميزة "لم يستفد سابقاً" (اكتشاف من استلم مساعدة من مؤسسة أخرى مشاركة بالتدقيق المتقاطع)
        $secondaryOrgIds = collect(BeneficiarySeeder::$dualOrganizationCohort)
            ->pluck('secondary')
            ->unique()
            ->values();

        // لو لأي سبب كانت القائمة فارغة (لم يُشغَّل BeneficiarySeeder) نكتفي بمؤسسات عشوائية
        $targetOrgIds = $secondaryOrgIds->isNotEmpty()
            ? $secondaryOrgIds
            : $organizations->random(min(3, $organizations->count()))->pluck('id');

        Organization::whereIn('id', $targetOrgIds)->get()->each(function (Organization $organization) {
            AssistancePackage::factory()
                ->withPreviousAssistanceCheck('not_received', null, 30)
                ->create([
                    'organization_id' => $organization->id,
                    'title' => 'مساعدة طارئة - دون استفادة سابقة',
                ]);
        });
    }
}
