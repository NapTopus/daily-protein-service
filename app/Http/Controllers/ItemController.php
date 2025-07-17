<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\Item;
use App\Services\ItemService;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $itemService
    ) {
    }

    /**
     *  @OA\Post(
     *      path="/api/item",
     *      summary="創建項目",
     *      tags={"Item"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"name", "protein"},
     *              @OA\Property(property="name", type="string", example="雞腿"),
     *              @OA\Property(property="protein", type="number", example="100"),
     *              @OA\Property(property="date", type="string", example="2025-07-01"),
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
    public function store(StoreItemRequest $request)
    {
        $this->itemService->createWithRecordUpsert($request->validated(), auth()->user());
    }

    /**
     *  @OA\Patch(
     *      path="/api/item/{id}",
     *      summary="修改項目",
     *      tags={"Item"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
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
    public function update(UpdateItemRequest $request, Item $item)
    {
        $this->itemService->update($item, $request->validated());
    }

    /**
     *  @OA\Delete(
     *      path="/api/item/{id}",
     *      summary="刪除項目",
     *      tags={"Item"},
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
    public function destroy(int $id)
    {
        $this->itemService->destroy($id, auth()->user());
    }
}
