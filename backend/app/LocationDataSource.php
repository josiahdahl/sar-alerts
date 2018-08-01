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

    public function dataSource()
    {
        return $this->belongsTo('App\DataSource');
    }

    /**
     * @todo Should this be moved to the DataSource?
     * @param $query
     * @param $dataType
     * @return mixed
     */
    public function scopeProvides($query, $dataType)
    {
        return $query->where('provides', $dataType);
    }

    /**
     * @todo Should the provides attribute be moved to the Data Source? Probably...
     * @return string
     */
    public function getEndpointAttribute()
    {
        return "/api/v1/locations/{$this->location_id}/{$this->provides}";
    }

    public function tides()
    {
        return $this->hasMany(DataTide::class);
    }
}
