<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYourDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('your_decks', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('md_count');
            $table->integer('sb_count');
            $table->string('saved_at');
            $table->integer('unix_saved_at');
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
        Schema::dropIfExists('your_decks');
    }
}
