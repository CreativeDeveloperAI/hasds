<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ShelterCondition: string implements HasColor, HasLabel
{
    case Bad = 'bad';
    case Acceptable = 'acceptable';
    case Good = 'good';

    /**
     * إرجاع النص العربي لحالة ومستوى تضرر المأوى الحالي
     */
    public function getLabel(): ?string
    {
        return __('enums.ShelterCondition.'.$this->value);
    }

    /**
     * تلوين مستويات تضرر المأوى لحساب الأولوية التلقائية
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Bad => 'danger',
            self::Acceptable => 'warning',
            self::Good => 'success',
        };
    }
}
