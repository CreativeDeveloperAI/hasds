<?php

namespace App\Models;

use App\Enums\AssistancePackageStatus;
use App\Enums\AssistancePackageType;
use App\Enums\CurrentShelterType;
use App\Enums\DistributionStatus;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssistancePackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',

        // أوزان ونطاقات الاستحقاق
        'target_min_score' => 'integer',
        'target_max_score' => 'integer',
        'target_min_score_ai' => 'integer',
        'target_max_score_ai' => 'integer',

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

    /**
     * تطبيق معيار "الاستفادة السابقة" على استعلام المستفيدين: يشترط أو يستثني من استلم مساعدة
     * مشابهة خلال آخر target_prev_assistance_days يوماً من أي مؤسسة مشاركة في التدقيق المتقاطع
     * (enable_cross_checking) أو من هذه المؤسسة نفسها.
     */
    public function applyPreviousAssistanceFilter(Builder $beneficiaryQuery): Builder
    {
        if (! in_array($this->target_prev_assistance_filter, ['received', 'not_received'], true)) {
            return $beneficiaryQuery;
        }

        $cutoff = now()->subDays($this->target_prev_assistance_days ?? 30);

        $participatingOrganizationIds = Organization::query()
            ->where('enable_cross_checking', true)
            ->orWhere('id', $this->organization_id)
            ->pluck('id');

        $matchingBeneficiaryIds = AssistanceDistribution::query()
            ->whereIn('organization_id', $participatingOrganizationIds)
            ->where('distribution_status', DistributionStatus::Delivered->value)
            ->where(function (Builder $query) use ($cutoff) {
                $query->where('delivered_at', '>=', $cutoff)
                    ->orWhere(function (Builder $query) use ($cutoff) {
                        $query->whereNull('delivered_at')->where('created_at', '>=', $cutoff);
                    });
            })
            ->when(
                $this->target_prev_assistance_type && $this->target_prev_assistance_type !== 'any',
                fn (Builder $query) => $query->whereHas(
                    'assistancePackage',
                    fn (Builder $q) => $q->where('package_type', $this->target_prev_assistance_type)
                )
            )
            ->pluck('beneficiary_id');

        return match ($this->target_prev_assistance_filter) {
            'received' => $beneficiaryQuery->whereIn('id', $matchingBeneficiaryIds),
            'not_received' => $beneficiaryQuery->whereNotIn('id', $matchingBeneficiaryIds),
        };
    }
}
