<?php

namespace App\Models;

use App\Enums\OrganizationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enable_cross_checking' => 'boolean',
        'status' => OrganizationStatus::class,
    ];

    /**
     * الباحثون والموظفون المرتبطون بهذه المؤسسة الشريكة
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * المستفيدون المسجلون والذين تم مسحهم بواسطة هذه المؤسسة
     */
    public function beneficiaries(): BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_organization')
            ->using(BeneficiaryOrganization::class)
            ->withPivot([
                'phone_number',
                'family_members_count',
                'children_under_5_count',
                'elderly_count',
                'pregnant_or_lactating_count',
                'has_chronic_disease',
                'has_disability',
                'disability_type',
                'has_recent_injury',
                'injury_severity',
                'is_displaced',
                'current_shelter_type',
                'shelter_condition',
                'current_displacement_location',
                'monthly_income',
                'income_source',
                'has_alternative_assistance',
                'priority_score',
                'survey_status',
                'surveyed_at',
                'custom_fields'
            ])
            ->withTimestamps();
    }

    /**
     * تعاريف الحقول الديناميكية المحوكمة التي صممتها المؤسسة لنفسها
     */
    public function customFieldDefinitions(): HasMany
    {
        return $this->hasMany(CustomFieldDefinition::class);
    }

    /**
     * حزم ومشاريع المساعدات الإغاثية التابعة لهذه المؤسسة
     */
    public function assistancePackages(): HasMany
    {
        return $this->hasMany(AssistancePackage::class);
    }
}
