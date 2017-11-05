<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DataTide
 * Tide data for a widget
 * @package App
 */
class DataTide extends Model
{
    protected $fillable = [
        'location_data_source_id',
        'time',
        'date',
        'timezone',
        'height',
        'high_low',
    ];
}
