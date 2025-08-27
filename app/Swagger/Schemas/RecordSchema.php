<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *   schema="RecordSchema",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example="1"),
 *   @OA\Property(property="date", type="string", format="date", example="2025-08-01"),
 *   @OA\Property(property="target", type="number", example="10"),
 *   @OA\Property(
 *      property="items",
 *      type="array",
 *      @OA\Items(ref="#/components/schemas/ItemSchema")
 *   )
 * )
 */
class RecordSchema
{
}
