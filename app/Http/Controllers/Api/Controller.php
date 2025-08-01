<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Learning Laravel API",
 *     version="1.0.0",
 *     description="API Documentation for Learning Laravel",
 *
 *     @OA\Contact(
 *         email="support@learninglaravel.com",
 *         name="API Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    //
}
