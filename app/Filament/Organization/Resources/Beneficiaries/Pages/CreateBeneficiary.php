<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use App\Models\Beneficiary;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBeneficiary extends CreateRecord
{
    protected static string $resource = BeneficiaryResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $tenantId = Filament::getTenant()?->id;

        // 1. التحقق أولاً أو إنشاء السجل السيادي للمستفيد
        $beneficiary = Beneficiary::firstOrCreate(
            ['national_id' => $data['national_id']],
            [
                'full_name' => $data['full_name'],
                'date_of_birth' => $data['date_of_birth'] ?? null
            ]
        );

        // 2. تجميع البيانات المخصصة للجدول الوسيط (Pivot Table)
        $pivotData = [
            'phone_number' => $data['pivot_phone_number'],
            'family_members_count' => $data['pivot_family_members_count'] ?? 1,
            'monthly_income' => $data['pivot_monthly_income'] ?? 0,
            'is_displaced' => $data['pivot_is_displaced'] ?? false,
            'current_shelter_type' => $data['pivot_current_shelter_type'] ?? null,
            // حفظ الحقول الديناميكية كـ JSON داخل الجدول الوسيط
            'custom_fields' => json_encode($data['pivot_custom_fields'] ?? []),
            'surveyed_at' => now(),
        ];

        // 3. ربط المستفيد بالمؤسسة الحالية داخل الجدول الوسيط
        $beneficiary->organizations()->syncWithoutDetaching([$tenantId => $pivotData]);

        return $beneficiary;
    }
}
