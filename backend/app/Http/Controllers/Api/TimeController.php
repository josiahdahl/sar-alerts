<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\TimeIntegrationContract;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeController extends ApiController
{
    public function get(Request $request, TimeIntegrationContract $time, $id)
    {
        $locationDataSource = Location::find($id)->dataSources()->provides('time')->first();

        if (!$locationDataSource) {
            return $this->response(404, [], 'Local time for this location does not exist');
        }

        $localTime = $time->localTime($locationDataSource);

        return $this->response(200, ['time' => $localTime]);

    }
}
