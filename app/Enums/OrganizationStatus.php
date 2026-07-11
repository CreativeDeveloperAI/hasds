<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum OrganizationStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Suspended = 'suspended';

    /**
     * إرجاع النص العربي لتصنيفات ترخيص واعتماد المؤسسات الشريكة
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار والتدقيق الميداني',
            self::Approved => 'مفعّل ومصادق عليه رسمياً',
            self::Suspended => 'موقف مؤقتاً (مخالف للسياسات)',
        };
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
