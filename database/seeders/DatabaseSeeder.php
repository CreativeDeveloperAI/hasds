<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * الترتيب هنا مهم جداً بسبب المفاتيح الأجنبية (FK-safe order):
     * المؤسسات ← المستخدمون التابعون لها ← سياسات السكور ← المستفيدون ومسوحاتهم الميدانية
     * ← الحقول الديناميكية ← حزم المساعدة ← عمليات التوزيع الفعلية.
     */
    public function run(): void
    {
        // المستخدم الافتراضي: يبقى بلا مؤسسة (organization_id = null) ليكون حساب دخول
        // مسؤول النظام السيادي (لوحة admin).
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            OrganizationSeeder::class,
            ScoringPolicySeeder::class,
            BeneficiarySeeder::class,
            CustomFieldDefinitionSeeder::class,
            AssistancePackageSeeder::class,
            AssistanceDistributionSeeder::class,
            // حسابات ثابتة معروفة مسبقاً (Admin/Organization/Beneficiary) لمراجعة العرض التجريبي بسهولة
            DemoAccountSeeder::class,
        ]);
    }
}
