<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldDefinition extends Model
{
    use HasFactory;

    protected $guarded = [];

    // تحويل حقل الـ options تلقائياً إلى مصفوفة array عند التعامل معه
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
