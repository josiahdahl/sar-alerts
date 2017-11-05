<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\WeatherIntegrationContract;
use App\LocationDataSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    /**
     * Get the weather data for a location
     * @param Request $request
     * @param WeatherIntegrationContract $integration
     * @param $id   integer     The LocationDataSource ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, WeatherIntegrationContract $integration, $id)
    {
        $locationDataSource = LocationDataSource::with('location')
            ->where('id', $id)
            ->first();

        if (!$locationDataSource) {
            return response()->json(['message' => 'Data Source Does Not Exist'], 404);
        }

        if ($locationDataSource->provides !== 'weather') {
            return response()->json(['message' => 'Invalid source for this data'], 404);
        }

        $data = $integration->get($locationDataSource);

        return response()->json($data, 200);
    }
}
