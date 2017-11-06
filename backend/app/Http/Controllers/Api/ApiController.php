<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function response($status = 200, $data = [], $message = 'Success')
    {
        $response = [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }
}
