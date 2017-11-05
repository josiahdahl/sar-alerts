<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/5/17
 * Time: 3:25 PM
 */

namespace App\Contracts\Integrations;


use App\LocationDataSource;

interface NotificationIntegrationContract
{
    public function get(LocationDataSource $locationDataSource);
}