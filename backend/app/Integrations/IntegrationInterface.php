<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/1/17
 * Time: 11:48 PM
 */

namespace App\Integrations;


interface IntegrationInterface
{
    /**
     * Get the data from the remote data source
     * @param array $locations  Array of location ids
     * @return mixed
     */
    public function get(Array $locations);
}