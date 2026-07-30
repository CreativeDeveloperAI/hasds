<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/lang/{locale}', function (string $locale) {
    session(['locale' => $locale]);

    return response()->noContent();
})->whereIn('locale', ['ar', 'en']);
