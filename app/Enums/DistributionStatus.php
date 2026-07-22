<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DistributionStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    /**
     * النص العربي لحالة تسليم المساعدة للمواطن
     */
    public function getLabel(): ?string
    {
        return __('enums.DistributionStatus.'.$this->value);
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
