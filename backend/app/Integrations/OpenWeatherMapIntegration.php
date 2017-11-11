<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/1/17
 * Time: 11:33 PM
 */

namespace App\Integrations;

use App\Contracts\Integrations\WeatherIntegrationContract;
use App\LocationDataSource;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenWeatherMapIntegration implements WeatherIntegrationContract
{
    private $client;
    private $api_key;
    static $api_url = 'http://api.openweathermap.org/data/2.5/weather';
    static $units = 'metric';
    const M_TO_KNOTS = 1.94384;

    /**
     * OpenWeatherMapIntegration constructor.
     * id={{city_id}}&APPID={{api_key}}&units=metric
     * Call with ID 6151264 for Sooke
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api_key = config('services.openweathermap.key');
    }

    /**
     * Sample Response:
     * ```json
     * {
     * "coord": {
     * "lon": -123.73,
     * "lat": 48.37
     * },
     * "weather": [
     * {
     * "id": 800,
     * "main": "Clear",
     * "description": "clear sky",
     * "icon": "02d"
     * }
     * ],
     * "base": "stations",
     * "main": {
     * "temp": 14.74,
     * "pressure": 1023,
     * "humidity": 28,
     * "temp_min": 14,
     * "temp_max": 16
     * },
     * "visibility": 24140,
     * "wind": {
     * "speed": 2.6,
     * "deg": 40
     * },
     * "clouds": {
     * "all": 5
     * },
     * "dt": 1509397200,
     * "sys": {
     * "type": 1,
     * "id": 3364,
     * "message": 0.1748,
     * "country": "CA",
     * "sunrise": 1509375575,
     * "sunset": 1509411402
     * },
     * "id": 6151264,
     * "name": "Sooke",
     * "cod": 200
     * }
     * ```
     * @param LocationDataSource $locationDataSource
     * @return array the response body
     */
    public function get(LocationDataSource $locationDataSource)
    {
        $cityId = $locationDataSource->location_identifier['cityId'];
        $timezone = $locationDataSource->location->timezone;
        $cacheKey = "owm_{$cityId}";
        $lastUpdatedKey = "{$cacheKey}_age";

        // If we don't know when it was last updated, get it again.
        if (Cache::has($cacheKey) && Cache::has($lastUpdatedKey)) {
            Log::info("Got weather for LocationDataSource {$locationDataSource->id} from cache");
            return $this->successResponse(Cache::get($cacheKey), Cache::get($lastUpdatedKey));
        }

        $query = ['id' => $cityId, 'APPID' => $this->api_key, 'units' => self::$units];
        $response = $this->client->get(self::$api_url, [
            'query' => $query,
        ]);

        $responseData = json_decode($response->getBody(), true);
        if ($response->getStatusCode() === 200) {
            Log::info("Added weather for LocationDataSource {$locationDataSource->id} to cache");
            Cache::put($cacheKey, $responseData, Carbon::now()->addMinutes(30));
            $lastUpdated = Carbon::now()->setTimezone($timezone);
            Cache::put($lastUpdatedKey, $lastUpdated, Carbon::now()->addMinutes(30));
            return $this->successResponse($responseData, $lastUpdated);
        } else {
            Log::error("Error getting OpenWeatherMap data for city {$cityId}. LocationDataSource {$locationDataSource->id}", $responseData);
            return $this->errorResponse($responseData);
        }
    }

    private function windFromDeg($deg)
    {
        if (is_null($deg)) {
            return '';
        }
        if ($deg < 22.5) {
            return 'N';
        }
        if ($deg < 67.5) {
            return 'NE';
        }
        if ($deg < 112.5) {
            return 'E';
        }
        if ($deg < 157.5) {
            return 'SE';
        }
        if ($deg < 202.5) {
            return 'S';
        }
        if ($deg < 247.5) {
            return 'SW';
        }
        if ($deg < 292.5) {
            return 'W';
        }
        if ($deg < 337.5) {
            return 'NW';
        }
        return 'N';
    }

    /**
     * [
     *  'data' => [],
     *  'status' => 200|404???
     *  'message' => 'Some message',
     * ]
     * @param array $response
     * @param $created
     * @return array
     */
    private function successResponse(Array $response, $created)
    {
        return [
            'shortDescription' => $response['weather'][0]['main'],
            'temperature' => $response['main']['temp'],
            'windSpeed' => number_format(self::M_TO_KNOTS * $response['wind']['speed'], 1),
            'windDirection' => $this->windFromDeg(isset($response['wind']['deg']) ? $response['wind']['deg'] : null),
            'city' => $response['name'],
            'created' => $created,
        ];
    }

    /**
     * @param array $response
     * @return array
     */
    private function errorResponse(Array $response)
    {
        return [
            'status' => $response['cod'],
            'message' => $response['message']
        ];
    }
}