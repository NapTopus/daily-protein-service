<?php

namespace App\Repositories;

use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Carbon;

class RecordRepo
{
    public function firstOrCreate(Carbon $date, User $user)
    {
        return Record::firstOrCreate(
            ['date' => $date->toDateString(), 'user_id' => $user->id],
            ['target' => $user->default_target]
        );
    }

    public function getByDate(User $user, Carbon $from, ?Carbon $to)
    {
        $to ??= Carbon::today();
        $records = Record::with('items')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->get();

        return RecordResource::collection($records)->resolve();
    }
}
