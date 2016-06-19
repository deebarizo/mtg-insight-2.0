<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMdCountAndSbCountToEventDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_decks', function ($table) {
            
            $table->integer('md_count')->after('finish');
            $table->integer('sb_count')->after('md_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_decks', function ($table) {

            $table->dropColumn('md_count');
            $table->dropColumn('sb_count');
        });
    }
}
