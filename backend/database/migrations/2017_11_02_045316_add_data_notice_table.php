<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_data_source_id');
            $table->text('notice');
            $table->timestamp('expires');
            $table->timestamps();

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
        Schema::dropIfExists('data_notices');
    }
}
