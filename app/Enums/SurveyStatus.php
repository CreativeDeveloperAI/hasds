<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SurveyStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Archived = 'archived';
    case Conflict = 'conflict';

    /**
     * إرجاع النص العربي لتصنيفات حالة المسح الميداني
     */
    public function getLabel(): ?string
    {
        return __('enums.SurveyStatus.'.$this->value);
    }

    /**
     * ألوان الحالات لتمييز التقييمات المتعارضة أو القديمة في اللوحات شاشات الفرز اللحظي
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Archived => 'slate',
            self::Conflict => 'danger',
        };
    }
}
