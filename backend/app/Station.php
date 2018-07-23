<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Station
 * A station - this will eventually be used to build different
 * dashboards for various accounts
 * @package App
 */
class Station extends Model
{
    protected $guarded = ['id'];

    public function widgets()
    {
        return $this->hasMany(LayoutWidget::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
