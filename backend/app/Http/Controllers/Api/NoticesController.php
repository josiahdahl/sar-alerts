<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\NotificationIntegrationContract;
use App\DataNotice;
use App\Location;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticesController extends ApiController
{
    /**
     * Get the notices for a location
     * @param Request $request
     * @param NotificationIntegrationContract $integration
     * @param $locationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, NotificationIntegrationContract $integration, $locationId)
    {
        $locationDataSource = Location::find($locationId)->dataSources()->provides('notices')->first();

        if (!$locationDataSource) {
            return $this->response(404, [], 'Notifications for this location does not exist');
        }
        try {
            $notifications = $integration->get($locationDataSource);
        } catch (RequestException $e) {
            try {
                $notifications = $integration->get($locationDataSource);
            } catch (RequestException $e) {
                return $this->response(500, [], 'Error getting notifications - try again shortly');
            }
        }

        return $this->response(200, $notifications);
    }
}
