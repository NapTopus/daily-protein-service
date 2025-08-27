<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateDefaultTargetRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    public function updateDefaultTarget(UpdateDefaultTargetRequest $request, User $user)
    {
        $this->userService->updateDefaultTarget($request->validated(), $user);
    }
}
