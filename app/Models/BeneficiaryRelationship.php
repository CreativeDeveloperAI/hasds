<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeneficiaryRelationship extends Model
{
    protected $table = 'beneficiary_relationships';

    protected $guarded = [];

    /**
     * رب الأسرة الأساسي في العلاقة
     */
    public function parentBeneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'parent_beneficiary_id');
    }

    /**
     * الفرد التابع لرب الأسرة
     */
    public function relativeBeneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'relative_beneficiary_id');
    }
}
