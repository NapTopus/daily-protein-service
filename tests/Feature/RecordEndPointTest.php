<?php

namespace Tests\Feature;

use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecordEndPointTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_show_record()
    {
        $user     = User::factory()->create();
        $record1  = Record::factory()->for($user)->state(['date' => '2025-07-01'])->create();
        $record2  = Record::factory()->for($user)->state(['date' => '2025-07-15'])->create();
        $record3  = Record::factory()->for($user)->state(['date' => '2025-07-30'])->create();
        $record4  = Record::factory()->for($user)->state(['date' => '2025-07-31'])->create();
        $records  = collect([$record1, $record2, $record3])->each->load('items');
        $expected = RecordResource::collection($records)->resolve();

        Sanctum::actingAs($user);

        $response = $this->get('/api/record?from=2025-07-01&to=2025-07-30');
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
