<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum InjurySeverity: string implements HasLabel, HasColor
{
    case Critical = 'critical';
    case Moderate = 'moderate';
    case Light = 'light';

    /**
     * إرجاع النص العربي لوصف خطورة جرحى ومصابي الحرب
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Critical => 'إصابة حرجة جداً (أولوية قصوى)',
            self::Moderate => 'إصابة متوسطة (أولوية ثانوية)',
            self::Light => 'إصابة طفيفة (مستقرة)',
        };
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
