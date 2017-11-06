<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetDataSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_data_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('layout_widget_id');
            $table->unsignedInteger('location_data_source_id');
            $table->timestamps();

            $table->foreign('layout_widget_id')
                ->references('id')
                ->on('layout_widgets');

            $table->foreign('location_data_source_id')
                ->references('id')
                ->on('location_data_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_data_sources');
    }
}
