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
            $table->string('color')->nullable();
            $table->string('mana_cost')->nullable();
            $table->integer('cmc')->nullable();
            $table->string('type');
            $table->text('rules_text')->nullable();
            $table->string('power')->nullable();
            $table->string('toughness')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('f_cost')->nullable();
            $table->text('note')->nullable();
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
