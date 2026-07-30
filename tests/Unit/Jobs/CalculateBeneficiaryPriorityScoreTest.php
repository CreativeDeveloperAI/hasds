<?php

use App\Enums\AiPriorityScoreStatus;
use App\Jobs\CalculateBeneficiaryPriorityScore;
use App\Models\BeneficiaryOrganization;
use Illuminate\Support\Facades\Http;

// ملاحظة: QUEUE_CONNECTION=sync في بيئة الاختبار، لذا فإن BeneficiaryOrganizationObserver
// يشغّل الجوب فوراً عند create() عبر afterCommit(). لهذا نجهّز Http::fake() قبل create()
// في اختبارات السيناريو الطبيعي، ونستخدم withoutEvents() عندما نريد استدعاء الجوب يدوياً.

it('stores the score and marks completed on a successful response', function () {
    Http::fake([
        config('services.ai_priority_score.url') => Http::response(['PriorityScore' => 0.75], 200),
    ]);

    $pivot = BeneficiaryOrganization::factory()->create();

    expect((float) $pivot->refresh()->ai_priority_score)->toBe(75.0)
        ->and($pivot->ai_priority_score_status)->toBe(AiPriorityScoreStatus::Completed);
});

it('reads the AI service URL from config rather than a hardcoded value', function () {
    config(['services.ai_priority_score.url' => 'https://example.test/custom-predict']);

    Http::fake([
        'https://example.test/custom-predict' => Http::response(['PriorityScore' => 0.5], 200),
    ]);

    BeneficiaryOrganization::factory()->create();

    Http::assertSent(fn ($request) => $request->url() === 'https://example.test/custom-predict');
});

it('throws when the AI response is missing a PriorityScore', function () {
    Http::fake([
        config('services.ai_priority_score.url') => Http::response(['PriorityScore' => null], 200),
    ]);

    expect(fn () => BeneficiaryOrganization::factory()->create())
        ->toThrow(RuntimeException::class);
});

it('throws when the AI response score is out of the expected 0-1 range', function () {
    Http::fake([
        config('services.ai_priority_score.url') => Http::response(['PriorityScore' => 1.5], 200),
    ]);

    expect(fn () => BeneficiaryOrganization::factory()->create())
        ->toThrow(RuntimeException::class);
});

it('throws when the AI response score is non-numeric', function () {
    Http::fake([
        config('services.ai_priority_score.url') => Http::response(['PriorityScore' => 'not-a-number'], 200),
    ]);

    expect(fn () => BeneficiaryOrganization::factory()->create())
        ->toThrow(RuntimeException::class);
});

it('throws when the AI service request fails', function () {
    Http::fake([
        config('services.ai_priority_score.url') => Http::response(null, 500),
    ]);

    expect(fn () => BeneficiaryOrganization::factory()->create())
        ->toThrow(RuntimeException::class);
});

it('sets status to processing before calling the AI service', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create()
    );

    expect($pivot->ai_priority_score_status)->toBe(AiPriorityScoreStatus::Pending);

    Http::fake([
        config('services.ai_priority_score.url') => function () use ($pivot) {
            expect($pivot->fresh()->ai_priority_score_status)->toBe(AiPriorityScoreStatus::Processing);

            return Http::response(['PriorityScore' => 0.5], 200);
        },
    ]);

    (new CalculateBeneficiaryPriorityScore($pivot))->handle();
});

it('marks the record as failed once the job permanently fails', function () {
    $pivot = BeneficiaryOrganization::withoutEvents(
        fn () => BeneficiaryOrganization::factory()->create()
    );

    (new CalculateBeneficiaryPriorityScore($pivot))->failed(new RuntimeException('boom'));

    expect($pivot->refresh()->ai_priority_score_status)->toBe(AiPriorityScoreStatus::Failed);
});
