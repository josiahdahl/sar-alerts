<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/3/17
 * Time: 4:22 PM
 */

namespace App\Integrations;


use App\Contracts\Integrations\DataServicesIntegrationContract;
use App\LocationDataSource;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GCTidesIntegration implements DataServicesIntegrationContract
{
    // TODO: Move this into the .env/config
    static $api_url = 'http://localhost:5000/api/v1';
    static $data_source_name = 'GCTides';
    protected $client;
    protected $redisKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->redisKey = FALSE;
    }

    /**
     * Make a request to the data scraping service to get the information asynchronously.
     * Sets the key to retrieve from
     * @param LocationDataSource $locationDataSource
     */
    public function request(LocationDataSource $locationDataSource)
    {
        $identifier = $locationDataSource->location_identifier;
        $locationId = $identifier['locationId'];

        $request = $this->client->post(self::$api_url . '/tides/gov-canada', [
            'form_params' => [
                'location_id' => $locationId,
            ],
        ]);
        $response = json_decode($request->getBody(), true);

        $this->redisKey = $response['id'];
    }

    /**
     * Get our information from the data scraping service
     * We return an array of [
     *  'status' => 'started|failed|pending|finished',
     *  'result' => [array of results],
     *  'message' => 'An error'
     * ]
     * @return mixed
     */
    public function retrieve()
    {
        $request = $this->client->get(self::$api_url . '/jobs/' . $this->redisKey);
        $response = json_decode($request->getBody(), true);
        // look for status's -> failed, pending, finished
        return $response;
    }

    public function requestSent()
    {
        return $this->redisKey !== FALSE;
    }
}