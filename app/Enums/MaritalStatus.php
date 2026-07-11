<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MaritalStatus: string implements HasLabel
{
    case Married = 'married';
    case Single = 'single';
    case Widowed = 'widowed';
    case Divorced = 'divorced';

    /**
     * إرجاع النص العربي المعتمد للحالة الاجتماعية
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Married => 'متزوج/ة',
            self::Single => 'أعزب/عزباء',
            self::Widowed => 'أرمل/ة',
            self::Divorced => 'مطلق/ة',
        };
    }
}
