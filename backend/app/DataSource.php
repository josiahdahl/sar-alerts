<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DataSource
 * A source of data for a widget
 * @package App
 */
class DataSource extends Model
{
    protected $fillable = ['name', 'description', 'url'];
    protected $table = 'data_sources';

}
