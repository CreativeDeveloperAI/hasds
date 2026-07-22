<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Pages;

use App\Enums\DistributionStatus;
use App\Filament\Organization\Resources\AssistancePackages\AssistancePackageResource;
use App\Models\AssistanceDistribution;
use App\Models\Beneficiary;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateAssistancePackage extends CreateRecord
{
    protected static string $resource = AssistancePackageResource::class;

    protected function afterCreate(): void
    {
        $package = $this->record;
        $tenantId = Filament::getTenant()?->id;

        // نبدأ الاستعلام من جدول المستفيدين ونربطه فوراً بالجدول الوسيط
        $query = Beneficiary::query()
            ->whereHas('organizations', function ($q) use ($tenantId) {
                $q->where('organization_id', $tenantId);
            });

        // استخدام whereRelation للفلترة داخل الـ Pivot
        if ($package->target_min_score !== null || $package->target_max_score !== null) {
            $min = $package->target_min_score ?? 0;
            $max = $package->target_max_score ?? 100;
            $query->whereRelation('organizations', 'priority_score', '>=', $min)
                ->whereRelation('organizations', 'priority_score', '<=', $max);
        }

        // الفلاتر الأخرى يجب أن تنتقل إلى whereRelation أيضاً
        if ($package->target_is_displaced) {
            $query->whereRelation('organizations', 'is_displaced', true);
            if ($package->target_displacement_location) {
                $query->whereRelation('organizations', 'current_displacement_location', 'like', "%{$package->target_displacement_location}%");
            }
            if ($package->target_shelter_type) {
                $query->whereRelation('organizations', 'current_shelter_type', $package->target_shelter_type);
            }
        }

        if ($package->target_has_disability) {
            $query->whereRelation('organizations', 'has_disability', true);
        }
        if ($package->target_has_recent_injury) {
            $query->whereRelation('organizations', 'has_recent_injury', true);
        }
        if ($package->target_has_chronic_disease) {
            $query->whereRelation('organizations', 'has_chronic_disease', true);
        }

        if ($package->target_has_children_under_5) {
            $query->whereRelation('organizations', 'children_under_5_count', '>', 0);
        }
        if ($package->target_has_elderly) {
            $query->whereRelation('organizations', 'elderly_count', '>', 0);
        }
        if ($package->target_has_pregnant_or_lactating) {
            $query->whereRelation('organizations', 'pregnant_or_lactating_count', '>', 0);
        }

        // ملاحظة: الحقول الموجودة في جدول Beneficiary الأساسي (مثل gender) تبقى كما هي
        if ($package->target_gender) {
            $query->where('gender', $package->target_gender);
        }
        if ($package->target_marital_status) {
            $query->where('marital_status', $package->target_marital_status);
        }
        if ($package->target_vital_status) {
            $query->where('vital_status', $package->target_vital_status);
        }

        // ... باقي منطق التدقيق المتقاطع (يبقى كما هو)
        $beneficiaries = $query->limit($package->total_quantity)->get();

        Log::info('AssistancePackage: Found '.$beneficiaries->count().' beneficiaries. Filters applied: '.json_encode($package->only(['target_min_score', 'target_gender', 'target_is_displaced'])));

        if ($beneficiaries->isEmpty()) {
            Log::warning('AssistancePackage: No beneficiaries matched. Check if the beneficiaries actually have priority_score or organization linked.');

            return;
        }

        foreach ($beneficiaries as $beneficiary) {
            AssistanceDistribution::updateOrCreate(
                [
                    'beneficiary_id' => $beneficiary->id,
                    'assistance_package_id' => $package->id,
                ],
                [
                    'organization_id' => $tenantId,
                    'distribution_status' => DistributionStatus::Pending->value,
                    'notes' => __('messages.ui_5cf0bea3'),
                ]
            );
        }

        Log::info('AssistancePackage: Successfully created/updated distributions for '.$beneficiaries->count().' beneficiaries.');
    }
}
