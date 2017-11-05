<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationDataSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_data_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('data_source_id');
            $table->unsignedInteger('location_id');
            $table->string('provides');
            $table->string('endpoint');
            $table->text('location_identifier');
            $table->timestamps();

            $table->foreign('data_source_id')
                ->references('id')
                ->on('data_sources');
            $table->foreign('location_id')
                ->references('id')
                ->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_data_sources');
    }
}
