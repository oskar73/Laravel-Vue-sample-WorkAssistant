<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function jsonSuccess($data = []): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
    public function jsonError($data = []): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
        ]);
    }
    public function jsonExceptionError($e): JsonResponse
    {
        Log::info($e);

        return response()->json([
            'status' => 0,
            'data' => [json_encode($e->getMessage())],
        ]);
    }
    public function jsonUnauthorized($data = []): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data'  =>  $data
        ], 401);
    }
}
