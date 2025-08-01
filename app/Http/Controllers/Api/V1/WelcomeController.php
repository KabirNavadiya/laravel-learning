<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class WelcomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/welcome",
     *     summary="Welcome endpoint",
     *     description="Returns a welcome message for API version 1",
     *     tags={"Welcome"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Welcome to Chance Your Arm API - v1"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success(
            message: __('api.welcome_message')
        );
    }
}
