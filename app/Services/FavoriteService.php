<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\User;
use App\Repositories\FavoriteRepo;

class FavoriteService
{
    public function __construct(private readonly FavoriteRepo $favoriteRepo)
    {
    }

    public function create(array $requestData, User $user)
    {
        $this->favoriteRepo->create($user, $requestData);
    }

    public function queryAll(User $user)
    {
        return $this->favoriteRepo->queryAll($user);
    }

    public function destroy(Favorite $favorite)
    {
        return $this->favoriteRepo->deleteById($favorite->id);
    }
}
