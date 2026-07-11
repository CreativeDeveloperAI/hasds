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
        return match ($this) {
            self::IsDisplaced => 'المواطن نازح في الميدان (is_displaced)',
            self::ShelterTent => 'نوع المأوى الحالي: خيمة (current_shelter_type = tent)',
            self::ShelterCenter => 'نوع المأوى الحالي: مركز إيواء (current_shelter_type = shelter_center)',

            self::HasDisability => 'المواطن من ذوي الاحتياجات الخاصة (has_disability)',
            self::HasChronicDisease => 'يعاني من أمراض مزمنة (has_chronic_disease)',
            self::HasRecentInjury => 'لديه إصابة حرب حديثة (has_recent_injury)',

            self::VitalStatusMartyred => 'حالة المواطن السيادية: شهيد (vital_status = martyred)',
            self::VitalStatusMissing => 'حالة المواطن السيادية: مفقود (vital_status = missing)',
            self::GenderFemale => 'المواطن أنثى / احتمالية معيل أسرة (gender = female)',

            self::FamilyLarge => 'عائلة كبيرة العدد (أكثر من 5 أفراد)',
            self::NoIncome => 'بلا دخل مادي شهري نهائياً (monthly_income = 0)',
        };
    }
}
