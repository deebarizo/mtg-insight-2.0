<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatestSetIdToYourDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('your_decks', function ($table) {

            $table->integer('latest_set_id')->unsigned()->after('id');
            $table->foreign('latest_set_id')->references('id')->on('sets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function ($table) {

            $table->dropColumn('latest_set_id');
        });
    }
}
