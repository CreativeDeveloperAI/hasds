<?php

namespace App\Filament\Beneficiary\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class BeneficiaryStatusWidget extends Widget
{
    protected string $view = 'filament.beneficiary.widgets.beneficiary-status-widget';
    protected int | string | array $columnSpan = 'full';
    public function getViewData(): array
    {
        // جلب بيانات المستفيد الحالي المسجل عبر الـ Guard الخاص به
        $beneficiary = Auth::guard('beneficiary_guard')->user();
        // جلب السجل الميداني المرتبط بالمستفيد (آخر تقييم تم له في الميدان)
        $latestAssessment = $beneficiary?->organizations()->orderBy('surveyed_at', 'desc')->first();
        $beneficiary->load(['organizations', 'distributions.assistancePackage']);
        $pivot = $latestAssessment ? $latestAssessment->pivot : null;

        return [
            'beneficiary' => $beneficiary,
            'pivot' => $pivot,
            'organization_name' => $latestAssessment?->name ?? __('messages.ui_a8f3c2d1'),
        ];
    }
}
