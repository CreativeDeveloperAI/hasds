<?php

use Illuminate\Support\Arr;

test('messages translation keys match between ar and en', function () {
    $en = include lang_path('en/messages.php');
    $ar = include lang_path('ar/messages.php');

    expect(array_keys($en))->toEqualCanonicalizing(array_keys($ar));
});

test('enums translation keys match between ar and en', function () {
    $en = include lang_path('en/enums.php');
    $ar = include lang_path('ar/enums.php');

    expect(array_keys(Arr::dot($en)))->toEqualCanonicalizing(array_keys(Arr::dot($ar)));
});
