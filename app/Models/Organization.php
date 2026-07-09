<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $guarded = [];

    /**
     * علاقة المؤسسة مع الموظفين والباحثين التابعين لها
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // داخل كلاس Organization.php أضف العلاقة العكسية للمستفيدين:
    public function beneficiaries(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class)
            ->withPivot([
                'phone_number',
                'family_members_count',
                'monthly_income',
                'is_displaced',
                'current_shelter_type',
                'priority_score',
                'survey_status',
                'surveyed_at'
            ])
            ->withTimestamps();
    }

    public function customFieldDefinitions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CustomFieldDefinition::class);
    }
}
