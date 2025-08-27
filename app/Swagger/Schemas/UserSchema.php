<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *   schema="UserSchema",
 *   type="object",
 *   @OA\Property(property="id", type="number", example="1"),
 *   @OA\Property(property="name", type="string", example="user1"),
 *   @OA\Property(property="email", type="string", example="user1@example.com"),
 *   @OA\Property(property="defaultTarget", type="number", example="80"),
 * )
 */
class UserSchema
{
}
