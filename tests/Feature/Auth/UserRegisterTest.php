<?php

declare(strict_types=1);
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Constants\HttpStatusCodesConstants as STATUS;
use Tests\Constants\UserRegisterTestConstants;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('can register a user with valid data', function () {
    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    $response->assertStatus(STATUS::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'data' => [
                'user' => [
                    'id',
                    'display_name',
                    'email',
                    'username',
                    'phone_number',
                ],
                'access_token',
                'expires_in',
            ],
            'success',
        ]);

    assertDatabaseHas('users', [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);
});

it('fails if email is already taken', function () {

    // First register a user to ensure the email is taken
    postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    // Attempt to register again with the same email
    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    // Assert the response status and structure
    $response->assertStatus(STATUS::HTTP_VALIDATION_ERROR)
        ->assertJsonValidationErrors([
            'email' => [__('validation.unique', ['attribute' => 'email'])],
        ]);
});

it('fails if username is already taken', function () {

    postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => 'USER2',
        'email' => 'user2@gmail.com',
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => 'user2password123',
        'phone_number' => '2345678901',
    ]);
    $response->assertStatus(STATUS::HTTP_VALIDATION_ERROR)
        ->assertJsonValidationErrors([
            'username' => [__('validation.unique', ['attribute' => 'username'])],
        ]);
});

it('fails if phone number is already registered', function () {
    postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => 'USER2',
        'email' => 'user2@gmail.com',
        'username' => 'user2',
        'password' => 'user2password123',
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    $response->assertStatus(STATUS::HTTP_VALIDATION_ERROR)
        ->assertJsonValidationErrors([
            'phone_number' => [__('validation.unique', ['attribute' => 'phone number'])],
        ]);
});

it('fails if not a valid phone number', function () {

    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::VALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::INVALID_PHONE_NUMBER,
    ]);

    $response->assertStatus(STATUS::HTTP_VALIDATION_ERROR)
        ->assertJsonValidationErrors([
            'phone_number' => __('auth.register.invalid_phone_number'),
        ]);
});

it('fails if password is too short', function () {
    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, [
        'display_name' => UserRegisterTestConstants::VALID_DISPLAY_NAME,
        'email' => UserRegisterTestConstants::VALID_EMAIL,
        'username' => UserRegisterTestConstants::VALID_USERNAME,
        'password' => UserRegisterTestConstants::INVALID_PASSWORD,
        'phone_number' => UserRegisterTestConstants::VALID_PHONE_NUMBER,
    ]);

    $response->assertStatus(STATUS::HTTP_VALIDATION_ERROR)
        ->assertJsonValidationErrors([
            'password' => [__('validation.min.string', ['attribute' => 'password', 'min' => 8])],
        ]);
});

it('returns validation errors when required fields are missing', function () {
    $response = postJson(UserRegisterTestConstants::REGISTER_USER_ENDPOINT, []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'display_name' => [__('validation.required', ['attribute' => 'display name'])],
            'email' => [__('validation.required', ['attribute' => 'email'])],
            'username' => [__('validation.required', ['attribute' => 'username'])],
            'password' => [__('validation.required', ['attribute' => 'password'])],
            'phone_number' => [__('validation.required', ['attribute' => 'phone number'])],
        ]);
});
