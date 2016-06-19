<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventDeckIdAndQuantityColumnsToTemp1CardMetagamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp1_card_metagames', function ($table) {
            
            $table->integer('event_deck_id')->unsigned()->after('id');
            $table->foreign('event_deck_id')->references('id')->on('event_decks');
            $table->integer('quantity')->after('card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp1_card_metagames', function ($table) {

            $table->dropColumn('event_deck_id');
            $table->dropColumn('quantity');
        });
    }
}
