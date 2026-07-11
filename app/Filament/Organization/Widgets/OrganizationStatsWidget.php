<?php

namespace App\Filament\Organization\Widgets;

use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class OrganizationStatsWidget extends Widget
{
    protected string $view = 'filament.organization.widgets.organization-stats-widget';

    // جعل الـ Widget يمتد على كامل العرض ليعطي مساحة تصميمية مريحة
    protected int | string | array $columnSpan = 'full';

    /**
     * حساب وتحضير البيانات الحية للجمعية الشريكة النشطة حالياً (Tenant)
     */
    protected function getViewData(): array
    {
        $tenantId = Filament::getTenant()?->id;

        if (!$tenantId) {
            return [
                'totalBeneficiaries' => 0,
                'criticalCount' => 0,
                'mediumCount' => 0,
                'lowCount' => 0,
                'activePackagesCount' => 0,
                'deliveredCount' => 0,
                'pendingCount' => 0,
                'completionRate' => 0,
            ];
        }

        // 1. حساب إجمالي المستفيدين المسجلين لدى هذه المؤسسة تحديداً
        $totalBeneficiaries = Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
            $q->where('organization_id', $tenantId);
        })->count();

        // 2. توزيع الأسر حسب مستويات الأولوية الإنسانية (مستنداً على الـ Pivot Score المحسوب)
        $criticalCount = Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
            $q->where('organization_id', $tenantId)->where('priority_score', '>=', 75);
        })->count();

        $mediumCount = Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
            $q->where('organization_id', $tenantId)->whereBetween('priority_score', [50, 74.99]);
        })->count();

        $lowCount = Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
            $q->where('organization_id', $tenantId)->where('priority_score', '<', 50);
        })->count();

        // 3. مؤشرات حزم المساعدات النشطة وعمليات التسليم الميداني
        $activePackagesCount = AssistancePackage::where('organization_id', $tenantId)
            ->where('status', \App\Enums\AssistancePackageStatus::Active)
            ->count();

        $deliveredCount = AssistanceDistribution::where('organization_id', $tenantId)
            ->where('distribution_status', DistributionStatus::Delivered)
            ->count();

        $pendingCount = AssistanceDistribution::where('organization_id', $tenantId)
            ->where('distribution_status', DistributionStatus::Pending)
            ->count();

        // 4. احتساب نسبة إنجاز وتوزيع الحصص الميدانية
        $totalDistributions = $deliveredCount + $pendingCount;
        $completionRate = $totalDistributions > 0 ? round(($deliveredCount / $totalDistributions) * 100) : 0;

        return [
            'totalBeneficiaries' => $totalBeneficiaries,
            'criticalCount' => $criticalCount,
            'mediumCount' => $mediumCount,
            'lowCount' => $lowCount,
            'activePackagesCount' => $activePackagesCount,
            'deliveredCount' => $deliveredCount,
            'pendingCount' => $pendingCount,
            'completionRate' => $completionRate,
        ];
    }
}
