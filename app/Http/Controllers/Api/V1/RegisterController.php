<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\auth\UserRegisterAction;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\Auth\UserRegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     *
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Register a new user and returns access and refresh tokens. If the phone number matches an existing record, the existing user will be deleted and a new user will be created.",
     *     operationId="register",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"display_name", "email", "username", "password", "phone_number"},
     *
     *             @OA\Property(property="display_name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 example="2345678901",
     *                 description="Indian phone number (10 digits) without country code"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string",
     *                     description="Access token for API authentication",
     *                     example="1|fJNKlHtgJIHGDMkOX9HYjLAStjgkLd1yhZ0z1Izp"
     *                 ),
     *                 @OA\Property(
     *                     property="expires_in",
     *                     type="integer",
     *                     description="Access token expiration time in seconds",
     *                     example=900
     *                 ),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors_meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The email field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(UserRegisterRequest $request, UserRegisterAction $action): JsonResponse
    {
        // get validated data from the request.
        $data = $request->validated();

        $result = $action->execute($data);

        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'access_token' => $result['access_token'],
            'expires_in' => $result['expires_in'],
        ], __('api.user.registered'), Response::HTTP_CREATED);
    }
}
