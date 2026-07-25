<?php

namespace App\Observers;

use App\Jobs\CalculateBeneficiaryPriorityScore;
use App\Models\BeneficiaryOrganization;

class BeneficiaryOrganizationObserver
{
    public function created(BeneficiaryOrganization $beneficiaryOrganization): void
    {
        CalculateBeneficiaryPriorityScore::dispatch($beneficiaryOrganization)
            ->afterCommit();
    }

    public function updated(BeneficiaryOrganization $beneficiaryOrganization): void
    {
        // بس إذا الحقول المؤثرة على السكور تغيرت فعلاً
        // (لتفادي إعادة الحساب كل مرة بيتحدث فيها أي حقل تاني، ولتفادي loop لأن الجوب نفسه بيعمل update)
        if ($beneficiaryOrganization->wasChanged(['has_disability', 'monthly_income', 'family_members_count'])) {
            CalculateBeneficiaryPriorityScore::dispatch($beneficiaryOrganization)
                ->afterCommit();
        }
    }
}
