<?php

namespace App\Models;

use App\Enums\CurrentShelterType;
use App\Enums\DisabilityType;
use App\Enums\InjurySeverity;
use App\Enums\ShelterCondition;
use App\Enums\SurveyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BeneficiaryOrganization extends Pivot
{
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
    ];
}
