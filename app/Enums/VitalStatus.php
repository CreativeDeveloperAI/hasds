<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum VitalStatus: string implements HasLabel, HasColor
{
    case Alive = 'alive';
    case Martyred = 'martyred';
    case Missing = 'missing';

    /**
     * إرجاع النص العربي المعتمد للحالة الحيوية للمواطن
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Alive => 'على قيد الحياة',
            self::Martyred => 'شهيد (رحمه الله)',
            self::Missing => 'مفقود (لم يُعثر عليه)',
        };
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
