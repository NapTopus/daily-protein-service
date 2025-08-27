<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateDefaultTargetRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    /**
     *  @OA\Get(
     *      path="/api/users/me",
     *      summary="取得用戶資訊",
     *      tags={"User"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserSchema")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          ref="#/components/responses/InvalidInput"
     *      )
     *  )
     */
    public function show()
    {
        $user = $this->userService->show();
        return (new UserResource($user))->resolve();
    }

    /**
     *  @OA\Patch(
     *      path="/api/users/{id}/defaultTarget",
     *      summary="修改預設蛋白質目標",
     *      tags={"User"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="target", type="number", example="120"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          ref="#/components/responses/InvalidInput"
     *      )
     *  )
     */
    public function updateDefaultTarget(UpdateDefaultTargetRequest $request, User $user)
    {
        $this->userService->updateDefaultTarget($request->validated(), $user);
    }
}
