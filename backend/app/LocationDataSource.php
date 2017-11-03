<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LocationDataSource
 * Link a location with a data source along with the identifier
 * for the data source
 * @package App
 */
class LocationDataSource extends Model
{
    protected $fillable = ['data_source_id', 'location_id', 'location_identifier'];

    public function location()
    {
        return $this->hasOne('location');
    }

    public function data_source()
    {
        return $this->hasOne('data_source');
    }
}
