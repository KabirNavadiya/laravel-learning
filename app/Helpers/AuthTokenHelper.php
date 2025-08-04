<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Str;

class AuthTokenHelper
{
    /**
     * Generate an authentication token for a user.
     *
     * @param  User  $user  The user to generate a token for
     * @return array<string, mixed> The generated plain text token
     */
    public function generateAccessToken(User $user): array
    {
        $tokenName = 'access-token-'.now()->timestamp.'-'.Str::random(10);

        // return $user->createToken($tokenName)->plainTextToken;

        return [
            'access_token' => $user->createToken($tokenName)->plainTextToken,
            'expires_in' => (int) config('sanctum.expiration', 15) * 60, // Convert minutes to seconds
        ];
    }
}
