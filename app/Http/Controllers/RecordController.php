<?php

namespace App\Http\Controllers;

use App\Http\Requests\Record\ShowRecordRequest;
use App\Services\RecordService;

class RecordController extends Controller
{
    public function __construct(
        private readonly RecordService $recordService
    ) {
    }

    public function showByDate(ShowRecordRequest $request)
    {
        return $this->recordService->showByDate($request->validated(), auth()->user());
    }
}
