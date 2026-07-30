<?php

namespace Database\Seeders;

use App\Enums\PolicyCategory;
use App\Enums\PolicyKey;
use App\Models\ScoringPolicie;
use Illuminate\Database\Seeder;

class ScoringPolicySeeder extends Seeder
{
    /**
     * تهيئة سياسات احتساب النقاط بشكل حتمي (سياسة واحدة لكل قيمة من PolicyKey) وليس عشوائياً،
     * لأن أوزان النظام السيادي لترتيب الأولوية يجب أن تكون معروفة ومضبوطة سلفاً.
     *
     * @var array<string, array{name: string, category: PolicyCategory, weight: int, description: string}>
     */
    protected array $definitions = [
        'is_displaced' => [
            'name' => 'وزن حالة النزوح',
            'category' => PolicyCategory::Shelter,
            'weight' => 15,
            'description' => 'نقاط إضافية للأسر النازحة عن مكان سكنها الأصلي.',
        ],
        'shelter_tent' => [
            'name' => 'وزن السكن في خيمة',
            'category' => PolicyCategory::Shelter,
            'weight' => 12,
            'description' => 'نقاط إضافية للأسر التي تسكن حالياً في خيمة.',
        ],
        'shelter_center' => [
            'name' => 'وزن السكن في مركز إيواء',
            'category' => PolicyCategory::Shelter,
            'weight' => 8,
            'description' => 'نقاط إضافية للأسر التي تسكن حالياً في مراكز الإيواء الجماعية.',
        ],
        'has_disability' => [
            'name' => 'وزن وجود إعاقة',
            'category' => PolicyCategory::Health,
            'weight' => 15,
            'description' => 'نقاط إضافية للأسر التي بها فرد أو أكثر من ذوي الإعاقة.',
        ],
        'has_chronic_disease' => [
            'name' => 'وزن الأمراض المزمنة',
            'category' => PolicyCategory::Health,
            'weight' => 10,
            'description' => 'نقاط إضافية للأسر التي بها فرد أو أكثر مصاب بمرض مزمن.',
        ],
        'has_recent_injury' => [
            'name' => 'وزن الإصابة الحديثة',
            'category' => PolicyCategory::Health,
            'weight' => 20,
            'description' => 'نقاط إضافية للأسر التي بها مصاب حديث (إصابة حرب).',
        ],
        'vital_status_martyred' => [
            'name' => 'وزن حالة الاستشهاد',
            'category' => PolicyCategory::Social,
            'weight' => 25,
            'description' => 'نقاط إضافية عالية للأسر التي فقدت رب الأسرة أو أحد أفرادها (شهيد).',
        ],
        'vital_status_missing' => [
            'name' => 'وزن حالة الفقدان',
            'category' => PolicyCategory::Social,
            'weight' => 20,
            'description' => 'نقاط إضافية للأسر التي لديها فرد مفقود.',
        ],
        'gender_female' => [
            'name' => 'وزن المعيل امرأة',
            'category' => PolicyCategory::Social,
            'weight' => 8,
            'description' => 'نقاط إضافية للأسر التي تعيلها امرأة.',
        ],
        'family_large' => [
            'name' => 'وزن الأسرة كبيرة العدد',
            'category' => PolicyCategory::Financial,
            'weight' => 10,
            'description' => 'نقاط إضافية للأسر ذات عدد الأفراد الكبير.',
        ],
        'no_income' => [
            'name' => 'وزن انعدام الدخل',
            'category' => PolicyCategory::Financial,
            'weight' => 18,
            'description' => 'نقاط إضافية للأسر التي لا يوجد لديها أي مصدر دخل ثابت.',
        ],
    ];

    /**
     * تهيئة سجل واحد بالضبط لكل قيمة من PolicyKey (11 سياسة إجمالاً).
     */
    public function run(): void
    {
        foreach (PolicyKey::cases() as $policyKey) {
            $definition = $this->definitions[$policyKey->value];

            ScoringPolicie::query()->updateOrCreate(
                ['policy_key' => $policyKey->value],
                [
                    'policy_name' => $definition['name'],
                    'category' => $definition['category']->value,
                    'points_weight' => $definition['weight'],
                    'is_active' => true,
                    'description' => $definition['description'],
                ]
            );
        }
    }
}
