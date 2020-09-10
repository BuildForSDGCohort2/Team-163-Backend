<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function successResponse($statusCode, array $response = null)
    {
        $response['success'] = true;

        return response()->json($response, $statusCode);
    }

    protected function errorResponse($statusCode, array $response)
    {
        $response['success'] = false;

        return response()->json($response, $statusCode);
    }
}
