<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardMetagamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_metagames', function($table)
        {
            $table->increments('id');
            $table->date('date'); 
            $table->integer('card_id')->unsigned();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->decimal('md_percentage', 5, 2);
            $table->decimal('sb_percentage', 5, 2);
            $table->decimal('total_percentage', 5, 2);
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
        Schema::dropIfExists('card_metagames');
    }
}
