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
        return __('enums.MaritalStatus.'.$this->value);
    }
}
