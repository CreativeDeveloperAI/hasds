<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasLabel
{
    case Male = 'male';
    case Female = 'female';

    /**
     * إرجاع النص العربي المعتمد لعرضه في شاشات النظام والفرز
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => 'ذكر',
            self::Female => 'أنثى',
        };
    }
}
