<?php

namespace App\Http\Controllers;

use App\Data\ItemData;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\ItemService;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $itemService
    ) {
    }

    public function store(StoreItemRequest $request)
    {
        $itemData = new ItemData(
            $request->input('name'),
            $request->input('protein'),
            $request->input('date')
        );

        $this->itemService->createWithRecord($itemData, auth()->user());

        return response()->noContent();
    }

    public function update(UpdateItemRequest $request, int $id)
    {
        $this->itemService->update($id, $request->validated(), auth()->user());
    }
}
