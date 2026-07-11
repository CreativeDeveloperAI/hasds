<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use App\Models\ScoringPolicie;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditBeneficiary extends EditRecord
{
    protected static string $resource = BeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $tenantId = Filament::getTenant()?->id;

        // جلب بيانات الـ Pivot الخاصة بهذه المؤسسة فقط
        $pivot = $this->record->organizations()->where('organization_id', $tenantId)->first()?->pivot;

        if ($pivot) {
            foreach ($pivot->toArray() as $key => $value) {
                // استثناء الحقول التي ليست Pivot
                if (!in_array($key, ['beneficiary_id', 'organization_id', 'created_at', 'updated_at'])) {
                    $data["pivot_{$key}"] = $value;
                }
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $tenantId = Filament::getTenant()?->id;

        // 1. إعادة حساب نقاط الأولوية (Priority Score)
        $activePolicies = ScoringPolicie::where('is_active', true)->get()->keyBy('policy_key');
        $score = 0;

        $addPoints = function (string $key) use ($activePolicies, &$score) {
            if ($activePolicies->has($key)) {
                $score += $activePolicies->get($key)->points_weight;
            }
        };

        // فحص مؤشرات النزوح
        if (filter_var($data['pivot_is_displaced'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $addPoints('is_displaced');
            $shelterType = $data['pivot_current_shelter_type'] ?? null;
            if ($shelterType === 'tent') $addPoints('shelter_tent');
            elseif ($shelterType === 'shelter_center') $addPoints('shelter_center');
        }

        // فحص مؤشرات الصحة
        if (filter_var($data['pivot_has_disability'] ?? false, FILTER_VALIDATE_BOOLEAN)) $addPoints('has_disability');
        if (filter_var($data['pivot_has_chronic_disease'] ?? false, FILTER_VALIDATE_BOOLEAN)) $addPoints('has_chronic_disease');
        if (filter_var($data['pivot_has_recent_injury'] ?? false, FILTER_VALIDATE_BOOLEAN)) $addPoints('has_recent_injury');

        // فحص البيانات السيادية
        if (($data['vital_status'] ?? '') === 'martyred') $addPoints('vital_status_martyred');
        elseif (($data['vital_status'] ?? '') === 'missing') $addPoints('vital_status_missing');
        if (($data['gender'] ?? '') === 'female') $addPoints('gender_female');

        // فحص المؤشرات الديموغرافية والمالية
        if (intval($data['pivot_family_members_count'] ?? 0) >= 5) $addPoints('family_large');
        if (floatval($data['pivot_monthly_income'] ?? 0) == 0) $addPoints('no_income');
// داخل دالة handleRecordUpdate بعد جلب $data وقبل حفظ التعديلات
        if (isset($data['date_of_birth']) && $record->date_of_birth?->format('Y-m-d') !== $data['date_of_birth']) {
            $data['password'] = Hash::make($data['date_of_birth']);
        }
        $finalScore = min(100, max(0, $score));

        // 2. فصل بيانات الـ Pivot وتحديثها
        $pivotData = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'pivot_')) {
                $pivotKey = str_replace('pivot_', '', $key);
                $pivotData[$pivotKey] = $value;
            }
        }

        $pivotData['priority_score'] = $finalScore; // تحديث النتيجة المحسوبة
        $pivotData['surveyed_at'] = now();

        $record->organizations()->updateExistingPivot($tenantId, $pivotData);

        // 3. حفظ البيانات الأساسية (الجدول الرئيسي)
        $record->update(array_filter($data, fn($key) => !str_starts_with($key, 'pivot_'), ARRAY_FILTER_USE_KEY));

        return $record;
    }
}
