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
        return __('enums.AssistancePackageType.'.$this->value);
    }
}
