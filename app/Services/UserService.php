<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepo;

class UserService
{
    public function __construct(
        private readonly UserRepo $userRepo
    ) {
    }

    public function show(): User
    {
        return auth()->user();
    }

    public function update(array $requestData, User $user)
    {
        $this->userRepo->update($user, $requestData['target']);
    }
}
