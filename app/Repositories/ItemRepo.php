<?php

namespace App\Repositories;

use App\Data\Item\UpdateItemData;
use App\Models\Item;
use App\Models\Record;

class ItemRepo
{
    public function createForRecord(Record $record, array $attributes): Item
    {
        return $record->items()->create($attributes);
    }

    public function findById(int $id): ?Item
    {
        return Item::find($id);
    }

    public function update(Item $item, UpdateItemData $updateData): Item
    {
        $item->update($updateData->toArray());
        $item->save();
        return $item;
    }

    public function deleteById(int $id)
    {
        Item::destroy($id);
    }
}
