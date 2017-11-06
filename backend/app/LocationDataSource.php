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
    protected $casts = [
        'location_identifier' => 'array'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function data_source()
    {
        return $this->belongsTo('App\DataSource');
    }

    public function scopeProvides($query, $dataType)
    {
        return $query->where('provides', $dataType);
    }
}
