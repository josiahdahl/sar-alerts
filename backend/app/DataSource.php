<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    protected $fillable = ['name', 'description', 'url'];
    protected $table = 'data_sources';

}
