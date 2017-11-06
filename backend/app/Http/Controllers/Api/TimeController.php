<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\TimeIntegrationContract;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeController extends Controller
{
    public function get(Request $request, TimeIntegrationContract $time, $id)
    {
        $locationDataSource = Location::find($id)->dataSources()->provides('time')->first();

        if (!$locationDataSource) {
            return response()->json(['message' => 'Local time for this location does not exist'], 404);
        }

        $localTime = $time->localTime($locationDataSource);

        return response()->json(['time' => $localTime], 200);
    }
}
