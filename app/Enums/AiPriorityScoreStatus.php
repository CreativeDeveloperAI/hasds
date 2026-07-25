<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AiPriorityScoreStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    /**
     * التسمية العربية لحالة الدورة الإغاثية
     */
    public function getLabel(): ?string
    {
        return __('enums.AiPriorityScoreStatus.'.$this->value);
    }

    /**
     * الألوان الدلالية لحالة الحزمة
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Completed => 'success',
            self::Pending => 'gray',
            self::Processing => 'warning',
            self::Failed => 'danger',
        };
    }
}
