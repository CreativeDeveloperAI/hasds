<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use App\Models\Beneficiary;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateBeneficiary extends CreateRecord
{
    protected static string $resource = BeneficiaryResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $tenantId = Filament::getTenant()?->id;

        // 1. حساب نقاط الأولوية المعيارية بناءً على المدخلات الميدانية
        $score = 0;

        $familyCount = $data['pivot_family_members_count'] ?? 1;
        if ($familyCount >= 8) $score += 40;
        elseif ($familyCount >= 5) $score += 25;
        elseif ($familyCount >= 3) $score += 15;
        else $score += 5;

        if ($data['pivot_is_displaced'] ?? false) {
            $score += 20;
            if (str_contains($data['pivot_current_shelter_type'] ?? '', 'خيمة')) {
                $score += 15;
            } else {
                $score += 5;
            }
        }

        $income = $data['pivot_monthly_income'] ?? 0;
        if ($income == 0) $score += 25;
        elseif ($income < 400) $score += 15;
        elseif ($income < 800) $score += 5;

        $finalScore = min(100, max(0, $score));

        // 2. إنشاء السجل السيادي وتشفير تاريخ الميلاد تلقائياً ليكون كلمة المرور
        $beneficiary = Beneficiary::firstOrCreate(
            ['national_id' => $data['national_id']],
            [
                'full_name' => $data['full_name'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'password' => Hash::make($data['date_of_birth'] ?? '1990-01-01'), // منع الـ NULL Constraint
            ]
        );

        // 3. تخزين البيانات والـ Score المحسوب في الجدول الوسيط
        $pivotData = [
            'phone_number' => $data['pivot_phone_number'],
            'family_members_count' => $familyCount,
            'monthly_income' => $income,
            'is_displaced' => $data['pivot_is_displaced'] ?? false,
            'current_shelter_type' => $data['pivot_current_shelter_type'] ?? null,
            'priority_score' => $finalScore,
            'custom_fields' => json_encode($data['pivot_custom_fields'] ?? []),
            'surveyed_at' => now(),
        ];

        $beneficiary->organizations()->syncWithoutDetaching([$tenantId => $pivotData]);

        return $beneficiary;
    }}
