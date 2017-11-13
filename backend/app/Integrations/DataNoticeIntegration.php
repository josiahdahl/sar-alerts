<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/5/17
 * Time: 3:24 PM
 */

namespace App\Integrations;


use App\Contracts\Integrations\NotificationIntegrationContract;
use App\DataNotice;
use App\LocationDataSource;

class DataNoticeIntegration implements NotificationIntegrationContract
{
    public function get(LocationDataSource $locationDataSource)
    {
        return DataNotice::notExpired()
            ->where('location_data_source_id', $locationDataSource->id)
            ->get();
    }
}
