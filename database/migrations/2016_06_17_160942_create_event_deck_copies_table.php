<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDeckCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_deck_copies', function($table)
        {
            $table->increments('id');
            $table->integer('event_deck_id')->unsigned();
            $table->foreign('event_deck_id')->references('id')->on('event_decks');
            $table->integer('quantity');
            $table->integer('card_id')->unsigned();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->string('role'); // md or sb
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
        Schema::dropIfExists('event_deck_copies');
    }
}
