<?php

namespace App\Repositories;

use App\Http\Resources\FavoriteResource;
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
        return FavoriteResource::collection($user->favorites)->resolve();
    }

    public function deleteById(string $id)
    {
        Favorite::destroy($id);
    }
}
