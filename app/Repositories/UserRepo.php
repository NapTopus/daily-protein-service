<?php

namespace App\Repositories;

use App\Models\User;

class UserRepo
{
    public function update(User $user, float $newDefaultTarget): User
    {
        $user->update(['default_target' => $newDefaultTarget]);
        return $user;
    }
}
