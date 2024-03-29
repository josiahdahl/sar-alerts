<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\WeatherIntegrationContract;
use App\Location;
use App\LocationDataSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends ApiController
{
    /**
     * Get the weather data for a location
     * @param Request $request
     * @param WeatherIntegrationContract $integration
     * @param $id   integer     The Location ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, WeatherIntegrationContract $integration, $id)
    {
        // TODO: This will break when we have more than one weather integration...for now it's ok.
        $locationDataSource = Location::find($id)->dataSources()->provides('weather')->first();

        if (!$locationDataSource) {
            return $this->response(404, [], 'Data Source Does Not Exist');
        }

        $data = $integration->get($locationDataSource);

        return $this->response(200, $data);
    }
}
