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
            $table->unsignedInteger('col_xs')->nullable();
            $table->unsignedInteger('col_sm')->nullable();
            $table->unsignedInteger('col_md')->nullable();
            $table->unsignedInteger('col_lg')->nullable();
            $table->unsignedInteger('col_xl')->nullable();
            $table->timestamps();

            $table->foreign('station_id')
                ->references('id')
                ->on('stations');

            $table->foreign('widget_id')
                ->references('id')
                ->on('widgets');
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
