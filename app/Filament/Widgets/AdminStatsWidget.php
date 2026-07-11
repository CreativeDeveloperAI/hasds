<?php

namespace App\Filament\Widgets;

use App\Enums\DistributionStatus;
use App\Enums\OrganizationStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use App\Models\Beneficiary;
use App\Models\Organization;
use Filament\Widgets\Widget;

class AdminStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.admin-stats-widget';
    // جعل الـ Widget ممتداً بكامل العرض ليعطي فخامة المتابعة الرقمية
    protected int | string | array $columnSpan = 'full';

    /**
     * حساب وتحضير التحليلات المركزية السيادية للأدمن على مستوى الدولة
     */
    protected function getViewData(): array
    {

        // 1. إحصائيات السجل الوطني الموحد
        $totalBeneficiaries = Beneficiary::count();
        $martyredCount = Beneficiary::where('vital_status', 'martyred')->count();
        $missingCount = Beneficiary::where('vital_status', 'missing')->count();
        $aliveCount = Beneficiary::where('vital_status', 'alive')->count();

        // 2. إحصائيات حوكمة الجمعيات الشريكة
        $totalOrgs = Organization::count();
        $approvedOrgs = Organization::where('status', OrganizationStatus::Approved)->count();
        $pendingOrgs = Organization::where('status', OrganizationStatus::Pending)->count();
        $suspendedOrgs = Organization::where('status', OrganizationStatus::Suspended)->count();

        // 3. إحصائيات الصرف والتوزيع الشامل والتدقيق المتقاطع
        $totalDistributions = AssistanceDistribution::count();
        $deliveredCount = AssistanceDistribution::where('distribution_status', DistributionStatus::Delivered)->count();
        $pendingDistributions = AssistanceDistribution::where('distribution_status', DistributionStatus::Pending)->count();

        // حساب نسبة تغطية الصرف الإجمالية على مستوى كافة الحزم
        $totalPackagesQty = AssistancePackage::sum('total_quantity');
        $totalDistributedQty = AssistancePackage::sum('distributed_quantity');
        $nationalCompletionRate = $totalPackagesQty > 0 ? round(($totalDistributedQty / $totalPackagesQty) * 100) : 0;

        // 4. تحليل نوعية المساعدات النشطة في الميدان حالياً
        $foodPackagesCount = AssistancePackage::where('package_type', \App\Enums\AssistancePackageType::Food)->count();
        $cashPackagesCount = AssistancePackage::where('package_type', \App\Enums\AssistancePackageType::Cash)->count();
        $medicalPackagesCount = AssistancePackage::where('package_type', \App\Enums\AssistancePackageType::Medical)->count();
        $clothingPackagesCount = AssistancePackage::where('package_type', \App\Enums\AssistancePackageType::Clothing)->count();

        return [
            'totalBeneficiaries' => $totalBeneficiaries,
            'martyredCount' => $martyredCount,
            'missingCount' => $missingCount,
            'aliveCount' => $aliveCount,

            'totalOrgs' => $totalOrgs,
            'approvedOrgs' => $approvedOrgs,
            'pendingOrgs' => $pendingOrgs,
            'suspendedOrgs' => $suspendedOrgs,

            'totalDistributions' => $totalDistributions,
            'deliveredCount' => $deliveredCount,
            'pendingDistributions' => $pendingDistributions,
            'nationalCompletionRate' => $nationalCompletionRate,

            'foodPackagesCount' => $foodPackagesCount,
            'cashPackagesCount' => $cashPackagesCount,
            'medicalPackagesCount' => $medicalPackagesCount,
            'clothingPackagesCount' => $clothingPackagesCount,
        ];
    }
}
