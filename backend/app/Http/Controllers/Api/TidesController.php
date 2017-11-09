<?php

namespace App\Http\Controllers\Api;

use App\DataTide;
use App\Location;
use App\LocationDataSource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TidesController extends ApiController
{
    /**
     * Get the tide data for a location
     * @param Request $request
     * @param $id   integer     The Location ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $id)
    {
        $locationDataSource = Location::find($id)->dataSources()->provides('tides')->first();

        if (!$locationDataSource) {
            return $this->response(404, [], 'Tide data for this location does not exist');
        }
        $locationTimezone = $locationDataSource->location->timezone;
        $lowerLimit = Carbon::now()->subDay()->subHours(12)->setTimezone($locationTimezone);
        $upperLimit = Carbon::now()->addDay()->addHours(12)->setTimezone($locationTimezone);
        $tideData = DataTide::where([
            ['location_data_source_id', $locationDataSource->id],
            ['date', '>=', $lowerLimit],
            ['date', '<=', $upperLimit]
        ])->get();

        return $this->response(200, $tideData);

    }
}
