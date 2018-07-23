<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function response($status = 200, $data = [], $message = 'Success')
    {
        return response()->json($status, $data);
    }
}
