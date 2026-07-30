<?php

namespace Database\Seeders;

use App\Enums\AssistancePackageType;
use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\AssistancePackage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssistanceDistributionSeeder extends Seeder
{
    public function run(): void
    {
        $packages = AssistancePackage::all();

        foreach ($packages as $package) {
            $orgBeneficiaryIds = DB::table('beneficiary_organization')
                ->where('organization_id', $package->organization_id)
                ->pluck('beneficiary_id');

            if ($orgBeneficiaryIds->isEmpty()) {
                continue;
            }

            $recipientsCount = max(1, (int) floor($orgBeneficiaryIds->count() * fake()->randomFloat(2, 0.2, 0.6)));
            $recipients = $orgBeneficiaryIds->shuffle()->take($recipientsCount);

            foreach ($recipients as $beneficiaryId) {
                $this->createDistribution($beneficiaryId, $package);
            }
        }

        // سيناريو تجريبي واقعي: المستفيدون مزدوجو الانتساب استلموا مساعدة فعلية من مؤسستهم "الأساسية"
        // (التي تدعم التدقيق المتقاطع)، بحيث تستطيع مؤسستهم "الثانوية" اكتشاف ذلك لاحقاً عند تفعيل
        // معيار "لم يستفد سابقاً" على حزمها الخاصة (AssistancePackage::applyPreviousAssistanceFilter).
        foreach (BeneficiarySeeder::$dualOrganizationCohort as $entry) {
            $primaryPackage = AssistancePackage::where('organization_id', $entry['primary'])
                ->inRandomOrder()
                ->first();

            if (! $primaryPackage) {
                continue;
            }

            $this->createDistribution(
                $entry['beneficiary'],
                $primaryPackage,
                forceDelivered: true,
                deliveredAt: now()->subDays(fake()->numberBetween(1, 20))
            );
        }
    }

    /**
     * إنشاء عملية توزيع واحدة لمستفيد ضمن حزمة معينة، مع احترام قيد الفرادة
     * (beneficiary_id, assistance_package_id) المسمى b_package_unique.
     */
    protected function createDistribution(
        int $beneficiaryId,
        AssistancePackage $package,
        bool $forceDelivered = false,
        ?Carbon $deliveredAt = null,
    ): void {
        $alreadyExists = AssistanceDistribution::query()
            ->where('beneficiary_id', $beneficiaryId)
            ->where('assistance_package_id', $package->id)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $status = $forceDelivered
            ? DistributionStatus::Delivered
            : fake()->randomElement([
                DistributionStatus::Delivered,
                DistributionStatus::Delivered,
                DistributionStatus::Delivered,
                DistributionStatus::Delivered,
                DistributionStatus::Pending,
                DistributionStatus::Cancelled,
            ]);

        $deliveredAtValue = null;

        if ($status === DistributionStatus::Delivered) {
            $deliveredAtValue = $deliveredAt ?? $this->randomDeliveryMomentWithin($package);
        }

        $cashAmount = ($package->package_type === AssistancePackageType::Cash && $status === DistributionStatus::Delivered)
            ? fake()->randomFloat(2, 100, 1500)
            : null;

        AssistanceDistribution::factory()->create([
            'beneficiary_id' => $beneficiaryId,
            'organization_id' => $package->organization_id,
            'assistance_package_id' => $package->id,
            'distribution_status' => $status,
            'delivered_at' => $deliveredAtValue,
            'cash_amount' => $cashAmount,
            'notes' => fake()->boolean(20) ? 'تم التسليم بموجب توكيل رسمي من رب الأسرة' : null,
        ]);

        if ($status === DistributionStatus::Delivered) {
            $package->increment('distributed_quantity');
        }
    }

    /**
     * اختيار لحظة تسليم واقعية ضمن نافذة بداية/نهاية دورة الحزمة (لا تتجاوز اللحظة الحالية أبداً).
     */
    protected function randomDeliveryMomentWithin(AssistancePackage $package): Carbon
    {
        $windowStart = $package->start_date ? Carbon::parse($package->start_date) : now()->subMonths(6);
        $windowEnd = $package->end_date ? Carbon::parse($package->end_date) : now();

        if ($windowEnd->greaterThan(now())) {
            $windowEnd = now();
        }

        if ($windowStart->greaterThan($windowEnd)) {
            $windowStart = $windowEnd->copy()->subDays(5);
        }

        return Carbon::instance(fake()->dateTimeBetween($windowStart, $windowEnd));
    }
}
