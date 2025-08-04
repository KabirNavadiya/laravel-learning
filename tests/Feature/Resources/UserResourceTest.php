<?php

declare(strict_types=1);

use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('checks if user resource properly transforms a user model', function () {
    // Create a user with our factory
    $user = User::factory()->create([
        'display_name' => 'Test User',
        'username' => 'testuser',
        'phone_number' => '2345678901',
        'email' => 'test@gmail.com',
    ]);

    // Create the resource and convert to array
    $resource = new UserResource($user);
    $resourceArray = $resource->toArray(request());

    // Verify the structure of the resource
    expect($resourceArray)->toBeArray()
        ->toHaveKeys([
            'id',
            'display_name',
            'username',
            'phone_number',
            'email',
            'created_at',
            'updated_at',
        ]);

    // Verify the values match our test data
    expect($resourceArray['id'])->toBe($user->id);
    expect($resourceArray['display_name'])->toBe('Test User');
    expect($resourceArray['username'])->toBe('testuser');
    expect($resourceArray['phone_number'])->toBe('2345678901');
    expect($resourceArray['email'])->toBe('test@gmail.com');

    // Verify that sensitive data is not included
    expect($resourceArray)->not->toHaveKey('password');
    expect($resourceArray)->not->toHaveKey('remember_token');
});

it('checks if user resource can be converted to json response', function () {
    // Create a user
    $user = User::factory()->create();

    // Create a response from the resource
    $response = (new UserResource($user))->toResponse(request());

    // Verify the response has the correct content type
    expect($response->headers->get('Content-Type'))->toContain('application/json');

    // Verify the response status code (Laravel's JsonResource returns 201 by default)
    expect($response->getStatusCode())->toBe(201);

    // Verify we can get valid JSON from the response
    $jsonContent = $response->getContent();
    // Ensure we have a string before decoding
    expect($jsonContent)->toBeString();
    $decodedJson = json_decode((string) $jsonContent, true);
    expect($decodedJson)->toBeArray();
    expect($decodedJson)->toHaveKey('data');
});
