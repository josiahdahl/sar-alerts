<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/3/17
 * Time: 4:22 PM
 */

namespace App\Contracts\Integrations;


use App\LocationDataSource;

interface DataServicesIntegrationContract
{
    public function request(LocationDataSource $locationDataSource);

    public function retrieve();

    public function requestSent();
}