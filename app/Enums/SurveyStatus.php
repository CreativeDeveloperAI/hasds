<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum SurveyStatus: string implements HasLabel, HasColor
{
    case Active = 'active';
    case Archived = 'archived';
    case Conflict = 'conflict';

    /**
     * إرجاع النص العربي لتصنيفات حالة المسح الميداني
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'نشط ومعتمد ميدانياً',
            self::Archived => 'مؤرشف (سجل تاريخي قديم)',
            self::Conflict => 'متعارض (يتطلب مراجعة وتدقيق ميداني)',
        };
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
