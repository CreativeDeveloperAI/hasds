<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PolicyKey: string implements HasLabel
{
    // مؤشرات النزوح والمأوى
    case IsDisplaced = 'is_displaced';
    case ShelterTent = 'shelter_tent';
    case ShelterCenter = 'shelter_center';

    // مؤشرات صحية وطبية (Pivot)
    case HasDisability = 'has_disability';
    case HasChronicDisease = 'has_chronic_disease';
    case HasRecentInjury = 'has_recent_injury';

    // مؤشرات سيادية (Sovereign Beneficiary)
    case VitalStatusMartyred = 'vital_status_martyred';
    case VitalStatusMissing = 'vital_status_missing';
    case GenderFemale = 'gender_female';

    // مؤشرات مركبة ديموغرافية واقتصادية
    case FamilyLarge = 'family_large';
    case NoIncome = 'no_income';

    /**
     * إرجاع التسمية التفصيلية لكل حقل برمجي في قاعدة البيانات لتسهيل ربطه بالنقاط
     */
    public function getLabel(): ?string
    {
        return __('enums.PolicyKey.'.$this->value);
    }
}
