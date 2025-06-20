<?php

namespace App\Repositories;

use App\Data\Item\UpdateItemData;
use App\Models\Item;

class ItemRepository
{
    public function findById(int $id): ?Item
    {
        return Item::find($id);
    }

    public function update(Item $item, UpdateItemData $updateData): Item
    {
        $item->update($updateData->toUpdateArray());
        $item->save();
        return $item;
    }
}
