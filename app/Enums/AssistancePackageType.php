<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AssistancePackageType: string implements HasLabel
{
    case Food = 'food';
    case Cash = 'cash';
    case Medical = 'medical';
    case Clothing = 'clothing';

    /**
     * النص العربي الموضح لنوع المساعدة
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Food => 'سلة غذائية / معلبات / خضار',
            self::Cash => 'قسيمة نقدية (شيكل)',
            self::Medical => 'مستلزمات طبية وأدوية',
            self::Clothing => 'كسوة شتاء / ملابس وأغطية',
        };
    }
}
