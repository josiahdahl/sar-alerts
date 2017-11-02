<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataTidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_tides', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('data_source_id');
            $table->dateTimeTz('time');
            $table->float('height');
            $table->enum('high_low', ['high', 'low']);
            $table->timestamps();

            $table->foreign('data_source_id')
                ->references('id')
                ->on('data_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_tides');
    }
}
