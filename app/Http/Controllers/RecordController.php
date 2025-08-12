<?php

namespace App\Http\Controllers;

use App\Http\Requests\Record\DestroyRecordRequest;
use App\Http\Requests\Record\ShowRecordRequest;
use App\Models\Record;
use App\Services\RecordService;

class RecordController extends Controller
{
    public function __construct(
        private readonly RecordService $recordService
    ) {
    }

    /**
     *  @OA\Get(
     *      path="/api/record",
     *      summary="查詢紀錄",
     *      tags={"Record"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          required=true,
     *          description="查詢日期起始，只傳 from 就只查一天",
     *      ),
     *      @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="查詢日期結束",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          ref="#/components/responses/InvalidInput"
     *      )
     *  )
     */
    public function index(ShowRecordRequest $request)
    {
        return $this->recordService->showByDate($request->validated(), auth()->user());
    }

    /**
     *  @OA\Delete(
     *      path="/api/record",
     *      summary="刪除紀錄",
     *      tags={"Record"},
     *      security={{"sanctumAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/Unauthorized"
     *      ),
     *  )
     */
    public function destroy(DestroyRecordRequest $request, Record $record)
    {
        $this->recordService->destroy($record);
    }
}
