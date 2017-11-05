<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/4/17
 * Time: 6:55 PM
 */

namespace App\Contracts\Integrations;


use App\LocationDataSource;
use Illuminate\Database\Eloquent\Collection;

interface WeatherIntegrationContract
{
    /**
     * Handle retrieving and caching of remote data
     * Should return a properly formatted response.
     * [
     *  'data' => [],
     *  'status' => 200|404???
     *  'message' => 'Some message',
     * ]
     * @param LocationDataSource $locationDataSource
     * @return mixed
     */
    public function get(LocationDataSource $locationDataSource);
}