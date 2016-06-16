<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateSetsCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets_cards', function($table)
        {
            $table->increments('id');
            $table->integer('set_id')->unsigned();
            $table->foreign('set_id')->references('id')->on('sets');
            $table->integer('card_id')->unsigned();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->string('rarity');
            $table->integer('multiverseid');
            $table->date('created_at');
            $table->date('updated_at');
        }); 

        DB::insert("INSERT INTO `sets_cards` VALUES (1,1,1,'Basic Land',410055,'2016-06-16','2016-06-16'),(2,1,2,'Basic Land',410058,'2016-06-16','2016-06-16'),(3,1,3,'Basic Land',410061,'2016-06-16','2016-06-16'),(4,1,4,'Basic Land',410064,'2016-06-16','2016-06-16'),(5,1,5,'Basic Land',410052,'2016-06-16','2016-06-16');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets_cards');
    }
}
