<?php

namespace App\Repositories;

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
}
