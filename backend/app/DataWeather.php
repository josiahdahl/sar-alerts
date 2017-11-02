<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataWeather extends Model
{
    protected $fillable = [
        'temperature',
        'short_description',
        'long_description',
        'pressure',
        'humidity',
    ];
    protected $table = 'data_sources';

    public function data_source()
    {
        return $this->belongsTo('App\DataSource');
    }
}
