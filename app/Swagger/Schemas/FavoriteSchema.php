<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *   schema="FavoriteSchema",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example="1"),
 *   @OA\Property(property="name", type="string", example="egg"),
 *   @OA\Property(property="protein", type="number", example="10"),
 * )
 */
class FavoriteSchema
{
}
