<?php

namespace App\Services;

use App\Data\ItemData;
use App\Models\Item;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Carbon;

class ItemService
{
    public function createWithRecord(ItemData $itemData, User $user): Item
    {
        try {
            $date = Carbon::parse($itemData->date);
        } catch (\Throwable $th) {
            $date = Carbon::today();
        }

        $record = Record::firstOrCreate(
            ['date' => $date->toDateString(), 'user_id' => $user->id],
            ['target' => $user->default_target]
        );

        return $record->items()->create([
            'name'    => $itemData->name,
            'protein' => $itemData->protein
        ]);
    }
}
