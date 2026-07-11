<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use App\Models\Beneficiary;
use App\Models\ScoringPolicie;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateBeneficiary extends CreateRecord
{
    protected static string $resource = BeneficiaryResource::class;

    /**
     * معالجة عملية الإنشاء وحساب نقاط الأولوية الإنسانية المتقاطعة ديناميكياً
     */
    protected function handleRecordCreation(array $data): Model
    {
        $tenantId = Filament::getTenant()?->id;

        // 1. جلب كافة سياسات وأوزان التنقيط المفعلة من قاعدة البيانات
        $activePolicies = ScoringPolicie::where('is_active', true)->get()->keyBy('policy_key');

        $score = 0;

        // دالة مساعدة لإضافة نقاط الوزن للمؤشرات المتحققة بأمان ومنع الأخطاء
        $addPoints = function (string $key) use ($activePolicies, &$score) {
            if ($activePolicies->has($key)) {
                $score += $activePolicies->get($key)->points_weight;
            }
        };

        // --- أ: معالجة وفحص مؤشرات النزوح والمأوى الميداني ---
        $isDisplaced = filter_var($data['pivot_is_displaced'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if ($isDisplaced) {
            $addPoints('is_displaced'); // إضافة نقاط النزوح

            // جلب قيمة نوع المأوى بشكل نصي آمن سواء كانت Enum أو String
            $shelterType = $data['pivot_current_shelter_type'] ?? null;
            if ($shelterType instanceof \BackedEnum) {
                $shelterType = $shelterType->value;
            }

            if ($shelterType === 'tent') {
                $addPoints('shelter_tent'); // نقاط الإقامة بخيمة
            } elseif ($shelterType === 'shelter_center') {
                $addPoints('shelter_center'); // نقاط الإقامة بمركز إيواء
            }
        }

        // --- ب: معالجة وفحص المؤشرات الصحية والطبية الخاصة بالأسرة ---
        if (filter_var($data['pivot_has_disability'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $addPoints('has_disability');
        }
        if (filter_var($data['pivot_has_chronic_disease'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $addPoints('has_chronic_disease');
        }
        if (filter_var($data['pivot_has_recent_injury'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $addPoints('has_recent_injury');
        }

        // --- ج: معالجة وفحص البيانات السيادية للمواطن ---
        $vitalStatus = $data['vital_status'] ?? 'alive';
        if ($vitalStatus instanceof \BackedEnum) {
            $vitalStatus = $vitalStatus->value;
        }
        if ($vitalStatus === 'martyred') {
            $addPoints('vital_status_martyred');
        } elseif ($vitalStatus === 'missing') {
            $addPoints('vital_status_missing');
        }

        $gender = $data['gender'] ?? 'male';
        if ($gender instanceof \BackedEnum) {
            $gender = $gender->value;
        }
        if ($gender === 'female') {
            $addPoints('gender_female'); // مؤشّر احتمالية إعالة امرأة للأسرة
        }

        // --- د: معالجة وفحص المؤشرات الديموغرافية والمالية ---
        $familyCount = intval($data['pivot_family_members_count'] ?? 1);
        if ($familyCount >= 5) {
            $addPoints('family_large'); // عائلة كبيرة العدد
        }

        $income = floatval($data['pivot_monthly_income'] ?? 0);
        if ($income == 0) {
            $addPoints('no_income'); // بلا دخل مادي
        }

        // حصر النتيجة الإجمالية للنقاط لتكون بين 0 و 100 كحد أقصى معتمد
        $finalScore = min(100, max(0, $score));

        // 2. تسجيل أو جلب السجل السيادي للمستفيد وتشفير تاريخ ميلاده تلقائياً ليكون كلمة مروره للوزارة
        $beneficiary = Beneficiary::firstOrCreate(
            ['national_id' => $data['national_id']],
            [
                'full_name' => $data['full_name'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'marital_status' => $data['marital_status'] ?? 'married',
                'vital_status' => $data['vital_status'] ?? 'alive',
                'personal_phone' => $data['personal_phone'] ?? null,
                'password' => Hash::make($data['date_of_birth'] ?? '1990-01-01'),
            ]
        );

        // 3. تخزين البيانات والـ Score المحسوب بدقة في الجدول الوسيط مع المؤسسة الشريكة
        $pivotData = [
            'phone_number' => $data['pivot_phone_number'],
            'family_members_count' => $familyCount,
            'children_under_5_count' => intval($data['pivot_children_under_5_count'] ?? 0),
            'elderly_count' => intval($data['pivot_elderly_count'] ?? 0),
            'pregnant_or_lactating_count' => intval($data['pivot_pregnant_or_lactating_count'] ?? 0),
            'has_chronic_disease' => filter_var($data['pivot_has_chronic_disease'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'has_disability' => filter_var($data['pivot_has_disability'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'disability_type' => $data['pivot_disability_type'] ?? null,
            'has_recent_injury' => filter_var($data['pivot_has_recent_injury'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'injury_severity' => $data['pivot_injury_severity'] ?? null,
            'is_displaced' => $isDisplaced,
            'current_shelter_type' => $data['pivot_current_shelter_type'] ?? null,
            'shelter_condition' => $data['pivot_shelter_condition'] ?? null,
            'current_displacement_location' => $data['pivot_current_displacement_location'] ?? null,
            'monthly_income' => $income,
            'income_source' => $data['pivot_income_source'] ?? null,
            'has_alternative_assistance' => filter_var($data['pivot_has_alternative_assistance'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'priority_score' => $finalScore, // حفظ المؤشر الذكي المعياري المحسوب بنزاهة
            'custom_fields' => json_encode($data['pivot_custom_fields'] ?? []),
            'surveyed_at' => now(),
        ];

        // المزامنة الآمنة للبيانات الوسيطة (Pivot) دون حذف الارتباطات مع الجمعيات الأخرى لعدم فقدان التاريخ المتقاطع
        $beneficiary->organizations()->syncWithoutDetaching([$tenantId => $pivotData]);

        return $beneficiary;
    }
}
