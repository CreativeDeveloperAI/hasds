<?php

use App\Enums\AiPriorityScoreStatus;
use App\Enums\AssistancePackageStatus;
use App\Enums\AssistancePackageType;
use App\Enums\CurrentShelterType;
use App\Enums\DisabilityType;
use App\Enums\DistributionStatus;
use App\Enums\Gender;
use App\Enums\InjurySeverity;
use App\Enums\MaritalStatus;
use App\Enums\OrganizationStatus;
use App\Enums\PolicyCategory;
use App\Enums\PolicyKey;
use App\Enums\ShelterCondition;
use App\Enums\SurveyStatus;
use App\Enums\VitalStatus;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

dataset('enum_classes', [
    AiPriorityScoreStatus::class,
    AssistancePackageStatus::class,
    AssistancePackageType::class,
    CurrentShelterType::class,
    DisabilityType::class,
    DistributionStatus::class,
    Gender::class,
    InjurySeverity::class,
    MaritalStatus::class,
    OrganizationStatus::class,
    PolicyCategory::class,
    PolicyKey::class,
    ShelterCondition::class,
    SurveyStatus::class,
    VitalStatus::class,
]);

it('has a non-empty ar and en label, distinct per locale, for every case', function (string $enumClass) {
    expect(is_a($enumClass, HasLabel::class, true))->toBeTrue();

    foreach ($enumClass::cases() as $case) {
        app()->setLocale('ar');
        $arabicLabel = $case->getLabel();

        app()->setLocale('en');
        $englishLabel = $case->getLabel();

        expect($arabicLabel)
            ->not->toBeNull()
            ->not->toBe('')
            ->and($englishLabel)
            ->not->toBeNull()
            ->not->toBe('')
            ->and($arabicLabel)->not->toBe($englishLabel);
    }
})->with('enum_classes');

it('returns a valid color for every case of enums implementing HasColor', function (string $enumClass) {
    if (! is_a($enumClass, HasColor::class, true)) {
        expect(true)->toBeTrue();

        return;
    }

    foreach ($enumClass::cases() as $case) {
        $color = $case->getColor();

        expect($color)->not->toBeNull();

        if (is_string($color)) {
            expect($color)->not->toBe('');
        }
    }
})->with('enum_classes');
