<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum DistributionStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    /**
     * النص العربي لحالة تسليم المساعدة للمواطن
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار / لم يستلم',
            self::Delivered => 'تم التسليم فعلياً للأسرة',
            self::Cancelled => 'تم الإلغاء / الاستبدال الميداني',
        };
    }

    /**
     * ألوان البطاقات الدلالية لحالة التسليم
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }
}
