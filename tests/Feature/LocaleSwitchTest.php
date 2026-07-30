<?php

test('switching to english sets the session and app locale', function () {
    $response = $this->get('/lang/en');

    $response->assertNoContent();
    $this->assertSame('en', session('locale'));

    $this->get('/')->assertSee('lang="en"', false);
});

test('switching to arabic sets the session and app locale', function () {
    $response = $this->get('/lang/ar');

    $response->assertNoContent();
    $this->assertSame('ar', session('locale'));

    $this->get('/')->assertSee('lang="ar"', false);
});

test('an unsupported locale is rejected', function () {
    $response = $this->get('/lang/fr');

    $response->assertNotFound();
});
