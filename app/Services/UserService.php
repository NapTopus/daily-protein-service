<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepo;

class UserService
{
    public function __construct(private readonly UserRepo $userRepo)
    {
    }

    public function me(): User
    {
        return auth()->user();
    }

    public function updateDefaultTarget(array $requestData, User $user)
    {
        $this->userRepo->updateDefaultTarget($user, $requestData['target']);
    }
}
