<?php

namespace App\Models;

use App\Enums\CurrentShelterType;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use App\Enums\AssistancePackageType;
use App\Enums\AssistancePackageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssistancePackage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',

        // أوزان ونطاقات الاستحقاق
        'target_min_score' => 'integer',
        'target_max_score' => 'integer',

        // مؤشرات نعم / لا البولينية
        'target_is_displaced' => 'boolean',
        'target_has_disability' => 'boolean',
        'target_has_recent_injury' => 'boolean',
        'target_has_chronic_disease' => 'boolean',
        'target_has_children_under_5' => 'boolean',
        'target_has_elderly' => 'boolean',
        'target_has_pregnant_or_lactating' => 'boolean',

        // ربط الـ Enums بشكل سيادي لمنع تعارض الحالات
        'target_gender' => Gender::class,
        'target_shelter_type' => CurrentShelterType::class,
        'target_marital_status' => MaritalStatus::class,
        'target_vital_status' => VitalStatus::class,

        // ربط نوع المساعدة وحالة الدورة الإغاثية بالـ Enums الرسمية
        'package_type' => AssistancePackageType::class,
        'status' => AssistancePackageStatus::class,

        // إعدادات الاستفادة السابقة والتدقيق المزدوج
        'target_prev_assistance_days' => 'integer',
    ];

    /**
     * المؤسسة المالكة والمشرفة على توزيع هذه الحزمة الإغاثية
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * سجل عمليات الصرف والتوزيع الفعلي المستلمة من هذه الحزمة
     */
    public function distributions(): HasMany
    {
        return $this->hasMany(AssistanceDistribution::class);
    }
}
