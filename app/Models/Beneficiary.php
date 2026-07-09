<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beneficiary extends Model
{
    protected $guarded =[];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)
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
}
