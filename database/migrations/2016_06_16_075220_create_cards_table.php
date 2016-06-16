<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function($table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('color');
            $table->string('mana_cost');
            $table->integer('cmc');
            $table->string('type');
            $table->text('rules_text');
            $table->string('power');
            $table->string('toughness');
            $table->string('loyalty');
            $table->string('f_cost');
            $table->text('note');
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
        Schema::dropIfExists('cards');
    }
}
