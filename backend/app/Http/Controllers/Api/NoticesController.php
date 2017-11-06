<?php

namespace App\Http\Controllers\Api;

use App\DataNotice;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticesController extends ApiController
{
    /**
     * Get the notices for a location
     * @param Request $request
     * @param $id   integer     The Location ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $id)
    {
        $locationDataSource = Location::find($id)->dataSources()->provides('notices')->first();

        if (!$locationDataSource) {
            return $this->response(404, [], 'Notifications for this location does not exist');
        }
        $notifications = DataNotice::where('location_data_source_id', $locationDataSource->id)->get();

        return $this->response(200, $notifications);
    }
}
