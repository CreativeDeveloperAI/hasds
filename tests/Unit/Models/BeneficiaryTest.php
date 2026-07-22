<?php

use App\Enums\Gender;
use App\Models\Beneficiary;

it('calculates full name correctly if needed', function () {
    $beneficiary = new Beneficiary([
        'full_name' => 'Ahmad Mahmoud Ali',
        'gender' => Gender::Male,
    ]);

    expect($beneficiary->full_name)->toBe('Ahmad Mahmoud Ali');
    expect($beneficiary->gender)->toBe(Gender::Male);
});

it('can check if it is female headed household', function () {
    $beneficiary = new Beneficiary([
        'full_name' => 'Fatima Ali',
        'gender' => Gender::Female,
    ]);

    expect($beneficiary->gender)->toBe(Gender::Female);
});
