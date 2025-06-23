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

    public function destroy(int $id, User $user)
    {
        $item = $this->itemRepository->findById($id);
        if (!$item) {
            return;
        }

        if ($user->cannot('delete', $item)) {
            throw new AuthorizationException();
        }
        $this->itemRepository->deleteById($id);
    }
}
