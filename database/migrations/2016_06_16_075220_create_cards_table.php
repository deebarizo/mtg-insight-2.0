<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

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
            $table->string('mana_cost')->nullable();
            $table->integer('cmc')->nullable();
            $table->string('middle_text');
            $table->text('rules_text')->nullable();
            $table->string('power')->nullable();
            $table->string('toughness')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('f_cost')->nullable();
            $table->text('note')->nullable();
            $table->string('layout');
            $table->date('created_at');
            $table->date('updated_at');
        }); 

        DB::insert("INSERT INTO `cards` VALUES (1,'Island',NULL,NULL,'Basic Land - Island',NULL,NULL,NULL,NULL,NULL,NULL,'normal','2016-06-16','2016-06-16'),(2,'Swamp',NULL,NULL,'Basic Land - Swamp',NULL,NULL,NULL,NULL,NULL,NULL,'normal','2016-06-16','2016-06-16'),(3,'Mountain',NULL,NULL,'Basic Land - Mountain',NULL,NULL,NULL,NULL,NULL,NULL,'normal','2016-06-16','2016-06-16'),(4,'Forest',NULL,NULL,'Basic Land - Forest',NULL,NULL,NULL,NULL,NULL,NULL,'normal','2016-06-16','2016-06-16'),(5,'Plains',NULL,NULL,'Basic Land - Plains',NULL,NULL,NULL,NULL,NULL,NULL,'normal','2016-06-16','2016-06-16');");
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
