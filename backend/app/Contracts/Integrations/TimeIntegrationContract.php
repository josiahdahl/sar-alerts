<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/5/17
 * Time: 4:23 PM
 */

namespace App\Contracts\Integrations;


use App\LocationDataSource;

interface TimeIntegrationContract
{
    public function localTime(LocationDataSource $locationDataSource);
}