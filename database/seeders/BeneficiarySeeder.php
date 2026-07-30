<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\BeneficiaryOrganization;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class BeneficiarySeeder extends Seeder
{
    /**
     * لقطة عن مجموعة المستفيدين "مزدوجي الانتساب" (مسجلين لدى مؤسستين) التي بناها هذا الـ Seeder،
     * تُستخدم لاحقاً من AssistancePackageSeeder / AssistanceDistributionSeeder لبناء سيناريو تجريبي
     * واقعي لميزة التدقيق المتقاطع بين المؤسسات (منع ازدواجية المساعدات).
     *
     * @var array<int, array{beneficiary: int, primary: int, secondary: int}>
     */
    public static array $dualOrganizationCohort = [];

    /**
     * عدد المستفيدين الإجمالي المراد إنشاؤه
     */
    protected int $totalBeneficiaries = 250;

    /**
     * عدد المستفيدين الذين سيتم تسجيلهم لدى مؤسسة ثانية عمداً (سيناريو الازدواجية)
     */
    protected int $dualOrganizationCount = 30;

    public function run(): void
    {
        $organizations = Organization::all();

        // ضمان وجود مؤسستين على الأقل مفعّل لديهما التدقيق المتقاطع، حتى لو لم تنتجهما العشوائية
        if ($organizations->where('enable_cross_checking', true)->count() < 2) {
            $organizations->take(2)->each(fn (Organization $organization) => $organization->update(['enable_cross_checking' => true]));
            $organizations = Organization::all();
        }

        $beneficiaries = Beneficiary::factory()->count($this->totalBeneficiaries)->create();

        // 1) كل مستفيد يُسجَّل لدى مؤسسة واحدة عشوائية بمسح ميداني مستقل
        $beneficiaries->each(function (Beneficiary $beneficiary) use ($organizations) {
            $organization = $organizations->random();
            $organization->beneficiaries()->attach($beneficiary->id, $this->pivotData());
        });

        // 2) مجموعة مزدوجة الانتساب: نختار مستفيدين مسجلين أصلاً لدى مؤسسة تدعم التدقيق المتقاطع
        //    (enable_cross_checking = true) ونسجلهم أيضاً لدى مؤسسة ثانية مختلفة، ببيانات مسح مستقلة.
        $eligible = Beneficiary::query()
            ->whereHas('organizations', fn ($query) => $query->where('enable_cross_checking', true))
            ->inRandomOrder()
            ->limit($this->dualOrganizationCount)
            ->get();

        foreach ($eligible as $beneficiary) {
            $primaryOrganization = $beneficiary->organizations()->first();

            if (! $primaryOrganization) {
                continue;
            }

            $secondaryCandidates = $organizations->where('id', '!=', $primaryOrganization->id);

            if ($secondaryCandidates->isEmpty()) {
                continue;
            }

            $secondaryOrganization = $secondaryCandidates->random();

            $secondaryOrganization->beneficiaries()->attach($beneficiary->id, $this->pivotData());

            static::$dualOrganizationCohort[] = [
                'beneficiary' => $beneficiary->id,
                'primary' => $primaryOrganization->id,
                'secondary' => $secondaryOrganization->id,
            ];
        }
    }

    /**
     * توليد بيانات مسح ميداني واقعية (Pivot) عبر إعادة استخدام منطق BeneficiaryOrganizationFactory،
     * مع استبعاد المفاتيح الأجنبية التي يتكفل بها attach() نفسه.
     *
     * @return array<string, mixed>
     */
    protected function pivotData(): array
    {
        $pivot = BeneficiaryOrganization::factory()->definition();

        unset($pivot['beneficiary_id'], $pivot['organization_id']);

        return $pivot;
    }
}
