<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/1/17
 * Time: 11:33 PM
 */

namespace App\Integrations;

use GuzzleHttp\Client;

class OpenWeatherMapIntegration extends Integration implements IntegrationInterface
{
    private $client;
    private $api_key;
    static $api_url = 'http://api.openweathermap.org/data/2.5/';
    static $units = 'metric';

    /**
     * OpenWeatherMapIntegration constructor.
     * id={{city_id}}&APPID={{api_key}}&units=metric
     * Call with ID 6151264 for Sooke
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::$api_url
        ]);
        $this->api_key = getenv('OWM_API_KEY');
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
     * @param array $locations
     * @return array the response body
     */
    public function get(Array $locations)
    {
        $locationsString = count($locations) > 1 ? implode(',', $locations) : $locations[0];

        $query = ['id' => $locationsString, 'APPID' => $this->api_key, 'units' => self::$units];
        // API Takes max 20 locations at once - handle this later
        $response = $this->client->get('weather', [
            'query' => $query,
        ]);
        return json_decode($response->getBody(), true);
    }
}