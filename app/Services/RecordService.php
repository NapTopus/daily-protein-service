<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\RecordRepo;
use Illuminate\Support\Carbon;

class RecordService
{
    public function __construct(
        private readonly RecordRepo $recordRepo,
    ) {
    }

    public function showByDate(array $requestData, User $user)
    {
        $from = Carbon::parse($requestData['from']);
        $to   = empty($requestData['to']) ? Carbon::today() : Carbon::parse($requestData['to']);
        return $this->recordRepo->getByDate($user, $from, $to);
    }
}
