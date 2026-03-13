<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreNfcLogRequest;
use App\Services\NfcLogIngestService;

class NfcLogIngestController extends Controller
{
    public function store(StoreNfcLogRequest $request, NfcLogIngestService $ingestService)
    {
        $result = $ingestService->store($request->validated());

        return response()->json($result, 201);
    }
}
