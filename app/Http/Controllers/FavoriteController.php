<?php

namespace App\Http\Controllers;

use App\Http\Requests\Favorite\DestroyFavoriteRequest;
use App\Http\Requests\Favorite\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Services\FavoriteService;

class FavoriteController extends Controller
{
    public function __construct(private readonly FavoriteService $favoriteService)
    {
    }

    /**
     *  @OA\Post(
     *      path="/api/favorites",
     *      summary="創建最愛",
     *      tags={"Favorite"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"name", "protein"},
     *              @OA\Property(property="name", type="string", example="雞腿"),
     *              @OA\Property(property="protein", type="number", example="100"),
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
    public function store(StoreFavoriteRequest $request)
    {
        $this->favoriteService->create($request->validated(), auth()->user());
    }

    /**
     *  @OA\Get(
     *      path="/api/favorites",
     *      summary="查詢最愛",
     *      tags={"Favorite"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/FavoriteSchema")
     *          )
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
    public function index()
    {
        return $this->favoriteService->queryAll(auth()->user());
    }

    /**
     *  @OA\Delete(
     *      path="/api/favorites/{id}",
     *      summary="刪除最愛",
     *      tags={"Favorite"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/Unauthorized"
     *      )
     *  )
     */
    public function destroy(DestroyFavoriteRequest $request, Favorite $favorite)
    {
        $this->favoriteService->destroy($favorite);
    }
}
