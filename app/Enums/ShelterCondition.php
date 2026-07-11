<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum ShelterCondition: string implements HasLabel, HasColor
{
    case Bad = 'bad';
    case Acceptable = 'acceptable';
    case Good = 'good';

    /**
     * إرجاع النص العربي لحالة ومستوى تضرر المأوى الحالي
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Bad => 'مهترئ / غير صالح للاستخدام الإنساني',
            self::Acceptable => 'مقبول / يحتاج لصيانات خفيفة',
            self::Good => 'سليم / بحالة جيدة جداً',
        };
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
