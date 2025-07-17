<?php

namespace App\Services;

use App\Data\Item\StoreItemData;
use App\Data\Item\UpdateItemData;
use App\Models\Item;
use App\Models\User;
use App\Repositories\ItemRepo;
use App\Repositories\RecordRepo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class ItemService
{
    public function __construct(
        private readonly RecordRepo $recordRepo,
        private readonly ItemRepo $itemRepository,
    ) {
    }

    public function createWithRecordUpsert(array $requestData, User $user): Item
    {
        $storeData = StoreItemData::fromRequest($requestData);

        try {
            $date = Carbon::parse($storeData->date);
        } catch (\Throwable $th) {
            $date = Carbon::today();
        }

        $record = $this->recordRepo->firstOrCreate($date, $user);

        return $this->itemRepository->createForRecord($record, [
            'name'    => $storeData->name,
            'protein' => $storeData->protein
        ]);
    }

    public function update(Item $item, array $requestData): void
    {
        $updateData = UpdateItemData::fromRequest($requestData);
        $this->itemRepository->update($item, $updateData);
    }

    public function destroy(Item $item)
    {
        $this->itemRepository->deleteById($item->id);
    }
}
