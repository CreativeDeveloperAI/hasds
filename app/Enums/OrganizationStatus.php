<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrganizationStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Suspended = 'suspended';

    /**
     * إرجاع النص العربي لتصنيفات ترخيص واعتماد المؤسسات الشريكة
     */
    public function getLabel(): ?string
    {
        return __('enums.OrganizationStatus.'.$this->value);
    }

    /**
     * تلوين الحالات في شاشات الإدارة والفرز اللحظي
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Suspended => 'danger',
        };
    }
}
