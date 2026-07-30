<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringPolicie extends Model
{
    use HasFactory;

    // ربط الموديل بجدول السياسات (تم الحفاظ على اسم الملف ScoringPolicie لمطابقة بيئة العمل لديك)
    protected $table = 'scoring_policies';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'points_weight' => 'integer',
    ];
}
