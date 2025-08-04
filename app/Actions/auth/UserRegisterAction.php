<?php

declare(strict_types=1);

namespace App\Actions\auth;

use App\Helpers\AuthTokenHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegisterAction
{
    public function __construct(
        private AuthTokenHelper $authTokenHelper
    ) {
        $this->authTokenHelper = $authTokenHelper;
    }

    /**
     * Handle the user registration action.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        $userData = [
            'phone_number' => $data['phone_number'],
            'display_name' => $data['display_name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ];

        $user = User::create($userData);

        $token = $this->authTokenHelper->generateAccessToken($user);

        return [
            'user' => $user,
            'access_token' => $token['access_token'],
            'expires_in' => $token['expires_in'],
        ];
    }
}
