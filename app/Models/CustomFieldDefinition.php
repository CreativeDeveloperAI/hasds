<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldDefinition extends Model
{
    protected $guarded = [];

    // تحويل حقل الـ options تلقائياً إلى مصفوفة array عند التعامل معه
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];
}
