<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VitalStatus: string implements HasColor, HasLabel
{
    case Alive = 'alive';
    case Martyred = 'martyred';
    case Missing = 'missing';

    /**
     * إرجاع النص العربي المعتمد للحالة الحيوية للمواطن
     */
    public function getLabel(): ?string
    {
        return __('enums.VitalStatus.'.$this->value);
    }

    /**
     * الألوان السيادية لحالة المواطن
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Alive => 'success',
            self::Martyred => 'danger',
            self::Missing => 'warning',
        };
    }
}
