<?php

namespace App\Models;

use App\Enums\AssistancePackageStatus;
use App\Enums\DistributionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistanceDistribution extends Model
{
    protected $table = 'assistance_distributions';

    protected $guarded = [];

    protected $casts = [
        'delivered_at' => 'datetime',
        'cash_amount' => 'decimal:2',
        'distribution_status' => DistributionStatus::class,

    ];

    /**
     * رب الأسرة المستلم للمساعدة
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * الجمعية التي قامت بتسليم المساعدة وتوثيقها ميدانياً
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * تفاصيل منحة أو حزمة المساعدة المستلمة
     */
    public function assistancePackage(): BelongsTo
    {
        return $this->belongsTo(AssistancePackage::class);
    }
}
