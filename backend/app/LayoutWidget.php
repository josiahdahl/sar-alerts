<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LayoutWidget extends Model
{
    protected $guarded = ['id'];

    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    public function widget()
    {
        return $this->belongsTo('App\Widget');
    }

    public function widgetDataSource()
    {
        return $this->hasMany('App\WidgetDataSource');
    }

    public function getSizesAttribute()
    {
        return [
            'xs' => $this->col_xs,
            'sm' => $this->col_sm,
            'md' => $this->col_md,
            'lg' => $this->col_lg,
            'xl' => $this->col_xl,
        ];
    }
}
