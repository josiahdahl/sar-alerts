<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\NotificationIntegrationContract;
use App\Station;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use function response;

class StationNoticeController extends Controller
{
    /**
     * @var NotificationIntegrationContract
     */
    private $service;

    public function __construct(NotificationIntegrationContract $service)
    {

        $this->service = $service;
    }
    public function view($id)
    {
        /** @var Collection $data */
        $data = Station::find($id)->widgets()
            ->with('data')->get()
            ->pluck('data')
            ->flatten()
            ->reject(function ($value) {
                return $value['provides'] !== 'notices';
            });

        if ($data->count() === 0) {
            return response()->json('', 404);
        }

        try {
            $notifications = $data->map(function ($location) {
                return $this->service->get($location);
            })->flatMap(function ($n) {
                return $n;
            });
        } catch (RequestException $e) {
            return response()->json('', 500);
        }

        return response()->json($notifications, 200);
    }
}
