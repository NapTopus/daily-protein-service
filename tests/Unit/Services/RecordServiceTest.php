<?php

namespace Tests\Unit\Services;

use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Models\User;
use App\Services\RecordService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecordServiceTest extends TestCase
{
    use RefreshDatabase;
    protected RecordService $recordService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recordService = app(RecordService::class);
    }

    #[Test]
    public function it_can_show_records()
    {
        $user            = User::factory()->create();
        $record1         = Record::factory()->for($user)->state(['date' => '2025-07-01'])->create();
        $record2         = Record::factory()->for($user)->state(['date' => '2025-07-15'])->create();
        $record3         = Record::factory()->for($user)->state(['date' => '2025-07-30'])->create();
        $record4         = Record::factory()->for($user)->state(['date' => '2025-07-31'])->create();
        $expectedRecords = collect([$record1, $record2, $record3])->each->load('items');
        $expected        = RecordResource::collection($expectedRecords)->resolve();

        $actual = $this->recordService->showByDate(['from' => '2025-07-01', 'to' => '2025-07-30'], $user);
        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function it_show_one_day_record_when_to_is_missing()
    {
        $user            = User::factory()->create();
        $record1         = Record::factory()->for($user)->state(['date' => '2025-07-15'])->create();
        $record2         = Record::factory()->for($user)->state(['date' => '2025-07-16'])->create();
        $record3         = Record::factory()->for($user)->state(['date' => '2025-07-17'])->create();
        $expectedRecords = collect([$record2])->each->load('items');
        $expected        = RecordResource::collection($expectedRecords)->resolve();

        $actual = $this->recordService->showByDate(['from' => '2025-07-16'], $user);
        $this->assertEquals($expected, $actual);
    }
}
