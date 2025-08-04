<?php

namespace Tests\Feature\Helpers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('checks if auth token can be generated for a user', function () {
    // Create a user
    $user = User::factory()->create();

    $token = $user->createToken('Test Token')->plainTextToken;

    expect($token)->toBeString();
    expect($user->tokens)->toHaveCount(1);

    assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => User::class,
        'name' => 'Test Token',
    ]);
});
