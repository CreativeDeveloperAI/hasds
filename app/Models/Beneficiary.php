<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beneficiary extends Authenticatable implements FilamentUser ,HasName
{
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    // 2. إخبار Filament باستخدام حقل full_name لعرضه في واجهة لوحة التحكم والـ Avatar
    public function getFilamentName(): string
    {
        return $this->full_name;
    }
    // تأمين عمل حقل الهوية كاسم مستخدم للـ Auth
    public function getAuthIdentifierName()
    {
        return 'national_id';
    }

    protected $guarded = [];

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

    protected $casts = [
        'password' => 'hashed',
    ];
}
