<?php

namespace App\Http\Controllers\Api;

use App\DataTide;
use App\LocationDataSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TidesController extends Controller
{
    /**
     * Get the tide data for a location
     * @param Request $request
     * @param $id   integer     The LocationDataSource ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $id)
    {
        $locationDataSource = LocationDataSource::with('location')
            ->where('id', $id)
            ->first();

        $tideData = DataTide::where('location_data_source_id', $id);

        if (!$locationDataSource) {
            return response()->json(['message' => 'Data Source Does Not Exist'], 404);
        }

        if ($locationDataSource->provides !== 'tides') {
            return response()->json(['message' => 'Invalid source for this data'], 404);
        }

        $data = $integration->get($locationDataSource);

        return response()->json($data, 200);
    }
}
