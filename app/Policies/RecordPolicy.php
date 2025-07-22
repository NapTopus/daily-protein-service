<?php

namespace App\Policies;

use App\Models\Record;
use App\Models\User;

class RecordPolicy
{
    public function delete(User $user, Record $record): bool
    {
        return $record->user_id === $user->id;
    }
}
