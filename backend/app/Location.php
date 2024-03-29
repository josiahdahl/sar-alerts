<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * A location to get data for. I.e. a city or region
 * @package App
 */
class Location extends Model
{
    protected $guarded = ['id'];

    public function dataSources()
    {
        return $this->hasMany('App\LocationDataSource');
    }
}
