<?php

namespace App\Http\Controllers\Api;

use App\DataTide;
use App\Location;
use App\LocationDataSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TidesController extends Controller
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
            return response()->json(['message' => 'Tide data for this location does not exist'], 404);
        }
        $tideData = DataTide::where('location_data_source_id', $locationDataSource->id)->get();

        if (!$tideData) {
            return response()->json(['message' => 'Tide data for this location does not exist'], 404);
        }

        return response()->json($tideData, 200);
    }
}
