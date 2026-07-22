<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AssistancePackageStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Completed = 'completed';
    case Paused = 'paused';

    /**
     * التسمية العربية لحالة الدورة الإغاثية
     */
    public function getLabel(): ?string
    {
        return __('enums.AssistancePackageStatus.'.$this->value);
    }

    /**
     * الألوان الدلالية لحالة الحزمة
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Completed => 'gray',
            self::Paused => 'warning',
        };
    }
}
