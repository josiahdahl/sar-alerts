<?php

namespace App;

use Carbon\Carbon;
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
        'tz',
        'height',
        'high_low',
    ];

    protected $casts = [
        'time' => 'datetime',
    ];

//
    public function scopeBetween($query, Carbon $lowerLimit, Carbon $upperLimit)
    {
        return $query->where('time', '>=', $lowerLimit->toDateTimeString())
            ->where('time', '<=', $upperLimit->toDateTimeString());
    }
}
