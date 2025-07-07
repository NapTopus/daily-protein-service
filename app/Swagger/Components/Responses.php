<?php

namespace App\Swagger\Components;

/**
 * @OA\Components(
 *     @OA\Response(
 *         response="Unauthorized",
 *         description="No permission, such as attempting to access or modify data belonging to another user.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="This action is unauthorized."),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="InvalidInput",
 *         description="Invalid input.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The name field is required."),
 *             @OA\Property(
 *                property="errors",
 *                type="object",
 *                @OA\Property(
 *                    property="name",
 *                    type="array",
 *                    @OA\Items(type="string", example="The name field is required.")
 *                )
 *             )
 *         )
 *     )
 * )
 */

class Responses
{
}
