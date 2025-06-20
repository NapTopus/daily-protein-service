<?php

namespace App\Services;

use App\Data\Item\StoreItemData;
use App\Data\Item\UpdateItemData;
use App\Models\Item;
use App\Models\Record;
use App\Models\User;
use App\Repositories\ItemRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class ItemService
{
    public function __construct(private readonly ItemRepository $itemRepository)
    {
    }

    public function createWithRecordUpsert(array $requestData, User $user): Item
    {
        $storeData = StoreItemData::fromRequest($requestData);

        try {
            $date = Carbon::parse($storeData->date);
        } catch (\Throwable $th) {
            $date = Carbon::today();
        }

        $record = Record::firstOrCreate(
            ['date' => $date->toDateString(), 'user_id' => $user->id],
            ['target' => $user->default_target]
        );

        return $record->items()->create([
            'name'    => $storeData->name,
            'protein' => $storeData->protein
        ]);
    }

    public function update(int $id, array $requestData, User $user): void
    {
        if (empty($requestData)) {
            return;
        }

        $item = $this->itemRepository->findById($id);
        if (!$item) {
            throw new ModelNotFoundException("Not Found");
        }

        if ($user->cannot('update', $item)) {
            throw new AuthorizationException();
        }

        $updateData = UpdateItemData::fromRequest($requestData);
        $this->itemRepository->update($item, $updateData);
    }
}
