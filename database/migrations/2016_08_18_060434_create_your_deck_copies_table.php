<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYourDeckCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('your_deck_copies', function($table)
        {
            $table->increments('id');
            $table->integer('your_deck_id')->unsigned();
            $table->foreign('your_deck_id')->references('id')->on('your_decks');
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
        Schema::dropIfExists('your_deck_copies');
    }
}
