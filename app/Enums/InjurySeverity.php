<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InjurySeverity: string implements HasColor, HasLabel
{
    case Critical = 'critical';
    case Moderate = 'moderate';
    case Light = 'light';

    /**
     * إرجاع النص العربي لوصف خطورة جرحى ومصابي الحرب
     */
    public function getLabel(): ?string
    {
        return __('enums.InjurySeverity.'.$this->value);
    }

    /**
     * تلوين الأولوية الطبية
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Critical => 'danger',
            self::Moderate => 'warning',
            self::Light => 'info',
        };
    }
}
