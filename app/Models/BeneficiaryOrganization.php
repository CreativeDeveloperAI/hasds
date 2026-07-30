<?php

namespace App\Models;

use App\Enums\AiPriorityScoreStatus;
use App\Enums\CurrentShelterType;
use App\Enums\DisabilityType;
use App\Enums\InjurySeverity;
use App\Enums\ShelterCondition;
use App\Enums\SurveyStatus;
use App\Observers\BeneficiaryOrganizationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy(BeneficiaryOrganizationObserver::class)]
class BeneficiaryOrganization extends Model
{
    use AsPivot, HasFactory; // ⬅️ هاد اللي بيرجع قدرات الـ pivot (fromRawAttributes وغيرها)

    // تحديد اسم جدول الربط الميداني المتقاطع
    protected $table = 'beneficiary_organization';

    protected $guarded = [];

    // تحويل تلقائي للحقول الميدانية المعقدة لتسهيل العمليات الحسابية
    protected $casts = [
        'is_displaced' => 'boolean',
        'has_chronic_disease' => 'boolean',
        'has_disability' => 'boolean',
        'has_recent_injury' => 'boolean',
        'disability_type' => DisabilityType::class, // الـ Enum الجديد لنوع الإعاقة
        'injury_severity' => InjurySeverity::class, // الـ Enum الجديد لخطورة الإصابة
        'current_shelter_type' => CurrentShelterType::class, // الـ Enum الجديد لنوع المأوى
        'shelter_condition' => ShelterCondition::class,
        'survey_status' => SurveyStatus::class, // الـ Enum الجديد لحالة المسح الميداني لمنع التعارض
        'has_alternative_assistance' => 'boolean',
        'custom_fields' => 'array',
        'surveyed_at' => 'datetime',
        'priority_score' => 'decimal:2',
        'monthly_income' => 'decimal:2',
        'ai_priority_score' => 'decimal:2',
        'ai_priority_score_status' => AiPriorityScoreStatus::class,
    ];

    public function toAiHousingLabel(): string
    {
        return match ($this->current_shelter_type) {
            CurrentShelterType::Tent,
            CurrentShelterType::ShelterCenter,
            CurrentShelterType::HostFamily => 'مخيم ايواء/خيمة',

            CurrentShelterType::RentApartment => 'إيجار',

            CurrentShelterType::Home => match ($this->shelter_condition) {
                ShelterCondition::Bad => 'مدمر بالكامل',
                ShelterCondition::Acceptable, ShelterCondition::Good => 'مدمر جزئياً',
                null => 'مدمر جزئياً', // حالة غير محددة، نفترض الأسوأ نسبيًا كـ fallback آمن
            },

            null => 'مدمر جزئياً', // نوع المأوى غير محدد أصلاً، fallback آمن
        };
    }
}
