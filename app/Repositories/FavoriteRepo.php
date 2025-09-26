<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Models\User;

class FavoriteRepo
{
    public function create(User $user, array $attributes)
    {
        $user->favorites()->create($attributes);
    }

    public function queryAll(User $user)
    {
        return $user->favorites;
    }

    public function deleteById(string $id)
    {
        Favorite::destroy($id);
    }
}
