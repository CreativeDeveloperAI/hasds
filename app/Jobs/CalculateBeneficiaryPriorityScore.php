<?php

namespace App\Jobs;

use App\Enums\AiPriorityScoreStatus;
use App\Models\BeneficiaryOrganization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalculateBeneficiaryPriorityScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 90; // اقل من الـ retry_after بتاع الـ queue

    // backoff تصاعدي: 10 ثواني، دقيقة، 5 دقايق
    public array $backoff = [10, 60, 300];

    public function __construct(public BeneficiaryOrganization $beneficiaryOrganization) {}

    public function handle(): void
    {
        $this->beneficiaryOrganization->updateQuietly([
            'ai_priority_score_status' => AiPriorityScoreStatus::Processing->value,
        ]);

        $response = Http::timeout(60)->post(config('services.ai_priority_score.url'), [
            'SpecialCase' => $this->beneficiaryOrganization->has_disability,
            'Income' => $this->beneficiaryOrganization->monthly_income,
            'FamilyMembers' => $this->beneficiaryOrganization->family_members_count,
            'Housing' => $this->beneficiaryOrganization->toAiHousingLabel(),
        ]);

        $priorityScore = $response->json('PriorityScore');

        if ($response->failed() || ! is_numeric($priorityScore) || $priorityScore < 0 || $priorityScore > 1) {
            Log::warning('AI priority score prediction failed', [
                'beneficiary_organization_id' => $this->beneficiaryOrganization->id,
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Priority score prediction failed');
        }

        // updateQuietly حتى ما يعيد إطلاق الـ Observer من جديد (infinite loop)
        $this->beneficiaryOrganization->updateQuietly([
            'ai_priority_score' => $priorityScore * 100,
            'ai_priority_score_status' => AiPriorityScoreStatus::Completed->value,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->beneficiaryOrganization->updateQuietly([
            'ai_priority_score_status' => AiPriorityScoreStatus::Failed->value,
        ]);

        Log::error('AI priority score job failed permanently', [
            'beneficiary_organization_id' => $this->beneficiaryOrganization->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
