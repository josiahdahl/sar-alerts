<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WidgetDataSource extends Model
{
    protected $guarded = ['id'];

    public function locationDataSource()
    {
        return $this->belongsTo('App\LocationDataSource');
    }

}
