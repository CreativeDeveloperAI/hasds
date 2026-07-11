<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beneficiary extends Authenticatable implements FilamentUser ,HasName
{
    public function getFilamentName(): string
    {
        return $this->full_name;
    }
    public function getAuthIdentifierName(): string
    {
        return 'national_id';
    }

    protected $guarded = [];
    protected $casts = [
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'gender' => Gender::class,
        'marital_status' => MaritalStatus::class,
        'vital_status' => VitalStatus::class, // إضافة الـ Enum للوضع الحيوي السيادي

    ];

    /**
     * التحقق من صلاحية المستفيد لدخول لوحة التحكم الخاصة به فقط
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'beneficiary';
    }


    /**
     * علاقة المستفيد مع المؤسسات التي قامت بمسحه وتقييمه ميدانياً (متعدد لمتعدد مع حقول Pivot شاملة)
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'beneficiary_organization')
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
     * علاقة التابعين لرب الأسرة الحالي (علاقات عائلية موحدة)
     */
    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_relationships', 'parent_beneficiary_id', 'relative_beneficiary_id')
            ->withPivot('relation_type')
            ->withTimestamps();
    }

    /**
     * علاقة أرباب الأسر المرتبط بهم هذا التابع (العلاقة العكسية)
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_relationships', 'relative_beneficiary_id', 'parent_beneficiary_id')
            ->withPivot('relation_type')
            ->withTimestamps();
    }

    /**
     * سجل توزيع المساعدات التي استلمها المستفيد منعاً للتكرار
     */
    public function distributions(): HasMany
    {
        return $this->hasMany(AssistanceDistribution::class);
    }
}
