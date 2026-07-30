<?php

test('landing page renders the react root with the current locale', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('id="root"', false);
    $response->assertSee('data-locale="'.app()->getLocale().'"', false);
});
