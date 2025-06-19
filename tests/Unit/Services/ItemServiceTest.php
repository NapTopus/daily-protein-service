<?php

namespace Tests\Unit\Services;

use App\Data\StoreItemData;
use App\Models\Item;
use App\Models\Record;
use App\Models\User;
use App\Services\ItemService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ItemService $itemService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->itemService = app(ItemService::class);
    }

    #[Test]
    public function it_creates_item_and_record_if_record_not_exists()
    {
        $user          = User::factory()->create();
        $date          = Carbon::parse('2025-06-06');
        $StoreItemData = new StoreItemData(
            name: 'Chicken',
            protein: 30.5,
            date: $date
        );

        $this->itemService->createWithRecord($StoreItemData, $user);

        $this->assertDatabaseHas('records', [
            'user_id' => $user->id,
            'date'    => $date->toDateString(),
            'target'  => 100,
        ]);

        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken',
            'protein'   => 30.5,
            'record_id' => Record::where('user_id', $user->id)->where('date', $date->toDateString())->first()->id,
        ]);
    }

    #[Test]
    public function it_creates_item_with_existing_record()
    {
        $user = User::factory()->create();
        $date = Carbon::parse('2025-06-06');

        $record = Record::factory()->create([
            'user_id' => $user->id,
            'date'    => $date->toDateString(),
        ]);

        $StoreItemData = new StoreItemData(
            name: 'Egg',
            protein: 12.0,
            date: $date
        );

        $this->itemService->createWithRecord($StoreItemData, $user);

        $this->assertEquals(1, Record::where('user_id', $user->id)->where('date', $date->toDateString())->count());

        $this->assertDatabaseHas('items', [
            'name'      => 'Egg',
            'protein'   => 12.0,
            'record_id' => $record->id,
        ]);
    }

    #[Test]
    public function it_uses_today_if_date_not_specified()
    {
        $user  = User::factory()->create();
        $today = Carbon::today();

        $StoreItemData = new StoreItemData(
            name: 'Tofu',
            protein: 8.0,
        );

        $item = $this->itemService->createWithRecord($StoreItemData, $user);

        $this->assertDatabaseHas('items', [
            'name'    => 'Tofu',
            'protein' => 8.0,
        ]);
        $this->assertEquals($today->toDateString(), $item->record->date);
    }

    #[Test]
    public function it_uses_today_if_date_is_invalid()
    {
        $user  = User::factory()->create();
        $today = Carbon::today();

        $StoreItemData = new StoreItemData(
            name: 'Tofu',
            protein: 8.0,
            date: 'invalid-date',
        );

        $item = $this->itemService->createWithRecord($StoreItemData, $user);

        $this->assertDatabaseHas('items', [
            'name'    => 'Tofu',
            'protein' => 8.0,
        ]);
        $this->assertEquals($today->toDateString(), $item->record->date);
    }

    #[Test]
    public function it_updates_item()
    {
        $user   = User::factory()->create();
        $record = Record::factory()
            ->for($user)->has(Item::factory()->state(['name' => 'Chicken', 'protein' => 30.5]))
            ->create();
        $item = $record->items->first();

        $this->itemService->update($item->id, ['name' => 'Chicken leg', 'protein' => 40], $user);

        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken leg',
            'protein'   => 40,
            'record_id' => $record->id,
        ]);
    }

    #[Test]
    public function it_updates_item_one_property()
    {
        $user   = User::factory()->create();
        $record = Record::factory()
            ->for($user)->has(Item::factory()->state(['name' => 'Chicken', 'protein' => 30.5]))
            ->create();
        $item = $record->items->first();

        $this->itemService->update($item->id, ['protein' => 40], $user);

        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken',
            'protein'   => 40,
            'record_id' => $record->id,
        ]);
    }

    #[Test]
    public function it_should_not_update_item_if_no_data()
    {
        $user   = User::factory()->create();
        $record = Record::factory()
            ->for($user)->has(Item::factory()->state(['name' => 'Chicken', 'protein' => 30.5]))
            ->create();
        $item = $record->items->first();

        $this->itemService->update($item->id, [], $user);

        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken',
            'protein'   => 30.5,
            'record_id' => $record->id,
        ]);
    }

    #[Test]
    public function it_does_not_allow_to_update_item_from_another_user()
    {
        $user        = User::factory()->has(Record::factory()->has(Item::factory()))->create();
        $item        = $user->records->first()->items->first();
        $anotherUser = User::factory()->create();

        $this->expectException(AuthorizationException::class);
        $this->itemService->update($item->id, ['protein' => 40], $anotherUser);
    }

    #[Test]
    public function it_should_throw_exception_if_item_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->itemService->update(1, ['protein' => 40], User::factory()->create());
    }
}
