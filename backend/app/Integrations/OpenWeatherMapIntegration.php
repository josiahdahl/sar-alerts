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
use function base64_encode;
use Carbon\Carbon;
use ErrorException;
use function file_get_contents;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use function is_file;
use const PATHINFO_EXTENSION;
use function public_path;

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

        $iconName = $this->mapIcon($response['weather'][0]['icon']);

        $iconPath = public_path('images/icons/weather/' . $iconName);

        try {
            $icon = $this->imgToBase64($iconPath);
        } catch (ErrorException $e) {
            Log::error("{$e->getMessage()}. OWM ID {$iconName}");
            $icon = $this->imgToBase64(
                public_path('images/icons/weather/' . $this->mapIcon('01d'))
            );
        }

        return [
            'main' => $response['weather'][0]['main'],
            'description' => $response['weather'][0]['description'],
            'temperature' => $response['main']['temp'],
            'windSpeed' => number_format(self::M_TO_KNOTS * $response['wind']['speed'], 1),
            'windDirection' => $this->windFromDeg(isset($response['wind']['deg']) ? $response['wind']['deg'] : null),
            'city' => $response['name'],
            'icon' => $icon,
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

    /**
     * Map an OWM icon code to the corresponding SVG icon
     * @param $iconCode
     * @return string Icon name, with an appropriate default if none exists.
     */
    private function mapIcon($iconCode)
    {
        $mapping = [
            /* Clear Sky - 01d */
            '01d' => 'sunny.svg',
            '01n' => 'clear_night.svg',

            /* Few Clouds */
            '02d' => 'sunny_intervals.svg',
            '02n' => 'clear_night_intervals.svg',

            /* Scattered Clouds */
            '03d' => 'white_cloud.svg',
            '03n' => 'white_cloud.svg',

            /* Broken Clouds */
            '04d' => 'black_low_cloud.svg',
            '04n' => 'black_low_cloud.svg',

            /* Shower Rain */
            '09d' => 'light_rain_showers.svg',
            '09n' => 'light_rain_showers.svg',
            // '' => 'heavy_rain_showers.svg',


            /* Rain */
            '10d' => 'cloudy_with_light_rain.svg',
            '10n' => 'cloudy_with_light_rain.svg',
//            '' => 'cloudy_with_heavy_rain.svg',
//            '' => 'cloudy_with_sleet.svg',
//            '' => 'sleet_showers.svg',

            /* Thunderstorm */
            '11d' => 'thunderstorms.svg',
            '11n' => 'thunderstorms.svg',
//            '' => 'thundery_showers.svg',

            /* Snow */
            '13d' => 'cloudy_with_light_snow.svg',
            '13n' => 'cloudy_with_light_snow.svg',
            // '' => 'cloudy_with_heavy_snow.svg',
            // '' => 'heavy_snow_showers.svg',
            // '' => 'light_snow_showers.svg',

            /* Mist */
            '50d' => 'mist.svg',
            '50n' => 'mist.svg',
//            '' => 'fog.svg',
        ];
        return isset($mapping[$iconCode]) ? $mapping[$iconCode] : $mapping['09d'];
    }

    private function imgToBase64($imgPath)
    {
        $type = pathinfo($imgPath, PATHINFO_EXTENSION);
        $data = file_get_contents($imgPath);
        // TODO: If adding image that aren't SVGs, this needs to be updated
        return "data:image/svg+xml;base64," . base64_encode($data);
    }
}