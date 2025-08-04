<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @property-read User $resource
 *
 * @mixin User
 *
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource representation with optional profile counts",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="display_name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="username", type="string", example="johndoe"),
 *     @OA\Property(property="phone_number", type="string", example="+1234567890"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2021-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2021-01-01T00:00:00.000000Z")
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authenticatedUser = $request->user();

        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'email' => $this->email,
            'username' => $this->username,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
