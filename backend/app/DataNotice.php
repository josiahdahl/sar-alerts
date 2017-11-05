<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DataNotice
 * A data collection that can be rendered in a widget
 * @package App
 */
class DataNotice extends Model
{
    protected $guarded = ['id'];

    public function scopeNotExpired($query)
    {
        return $query->where('expires', '>=', Carbon::now());
    }
}
