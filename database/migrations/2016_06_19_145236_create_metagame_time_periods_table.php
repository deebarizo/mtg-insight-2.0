<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetagameTimePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metagame_time_periods', function($table)
        {
            $table->increments('id');
            $table->integer('set_id')->unsigned();
            $table->foreign('set_id')->references('id')->on('sets');
            $table->date('start_date'); // start on Friday
            $table->date('end_date'); // end on Thursday
            $table->date('created_at');
            $table->date('updated_at');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metagame_time_periods');
    }
}
