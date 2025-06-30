<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *      schema="InputError",
 *      type="object",
 *      @OA\Property(property="message", type="string", example="The name field is required."),
 *      @OA\Property(
 *          property="errors",
 *          type="object",
 *          @OA\Property(
 *              property="name",
 *              type="array",
 *              @OA\Items(type="string", example="The name field is required.")
 *          )
 *      )
 *  )
 */
class ErrorSchema
{
}
