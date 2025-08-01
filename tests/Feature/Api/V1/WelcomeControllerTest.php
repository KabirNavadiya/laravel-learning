<?php

declare(strict_types=1);

use Tests\Constants\WelcomeControllerTestConstants;

use function Pest\Laravel\getJson;

it('returns a welcome message', function () {
    $response = getJson(WelcomeControllerTestConstants::WELCOME_API_URL);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => __('api.welcome_message'),
            'data' => null,
        ]);
});
