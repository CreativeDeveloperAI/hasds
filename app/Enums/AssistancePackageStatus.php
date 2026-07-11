<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AssistancePackageStatus: string implements HasLabel, HasColor
{
    case Active = 'active';
    case Completed = 'completed';
    case Paused = 'paused';

    /**
     * التسمية العربية لحالة الدورة الإغاثية
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'نشط وجاهز للصرف الميداني',
            self::Completed => 'تم التوزيع بنجاح وإغلاق الدورة',
            self::Paused => 'موقوف مؤقتاً لأسباب تشغيلية',
        };
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
