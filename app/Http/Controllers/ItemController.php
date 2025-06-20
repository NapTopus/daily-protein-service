<?php

namespace App\Http\Controllers;

use App\Data\StoreItemData;
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
        $this->itemService->createWithRecordUpsert($request->validated(), auth()->user());
    }

    public function update(UpdateItemRequest $request, int $id)
    {
        $this->itemService->update($id, $request->validated(), auth()->user());
    }
}
