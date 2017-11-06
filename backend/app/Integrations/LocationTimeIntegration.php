<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/5/17
 * Time: 4:23 PM
 */

namespace App\Integrations;


use App\Contracts\Integrations\TimeIntegrationContract;
use App\LocationDataSource;
use Carbon\Carbon;

class LocationTimeIntegration implements TimeIntegrationContract
{

    /**
     * Return a nicely formatted date time object in the appropriate time zone
     * @param LocationDataSource $locationDataSource
     * @return string
     */
    public function localTime(LocationDataSource $locationDataSource)
    {
        $timezone = $locationDataSource->location->timezone;

        return Carbon::now($timezone)->toIso8601String();
    }
}