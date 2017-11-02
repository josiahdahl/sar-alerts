<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_weather', function (Blueprint $table) {
            $table->increments('id');
            $table->float('temperature');
            $table->string('short_description');
            $table->string('long_description');
            $table->float('pressure');
            $table->float('humidity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_weather');
    }
}
