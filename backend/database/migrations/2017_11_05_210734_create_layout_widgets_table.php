<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayoutWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('widget_id');
            $table->unsignedInteger('row');
            $table->unsignedInteger('cols_default')->default(0);
            $table->unsignedInteger('cols_xs')->nullable();
            $table->unsignedInteger('cols_sm')->nullable();
            $table->unsignedInteger('cols_md')->nullable();
            $table->unsignedInteger('cols_lg')->nullable();
            $table->unsignedInteger('cols_xl')->nullable();
            $table->timestamps();

            $table->foreign('station_id')
                ->references('stations')
                ->on('id');

            $table->foreign('widget_id')
                ->references('widgets')
                ->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout_widgets');
    }
}
