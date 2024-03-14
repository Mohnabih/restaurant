<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message,  $code = 200, $errorCode = 0)
    {
        return Response::json([
            'status' => $code,
            'errorCode' => $errorCode,
            'data' => $result,
            'message' => $message
        ], $code);
    }
}
