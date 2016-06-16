<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function($table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->date('release_date');
            $table->date('created_at');
            $table->date('updated_at');
        }); 

        DB::insert("INSERT INTO `sets` VALUES (1,'Lands','LANDS','2000-01-01','2015-08-13','2015-08-13'),(3,'Dragons of Tarkir','DTK','2015-03-27','2015-08-13','2015-08-13'),(4,'Magic Origins','ORI','2015-07-17','2015-08-13','2015-08-13'),(5,'Battle for Zendikar','BFZ','2015-10-02','2015-08-13','2015-08-13'),(6,'Oath of the Gatewatch','OGW','2016-01-22','2016-02-05','2016-02-05'),(7,'Shadows over Innistrad','SOI','2016-04-08','2016-02-22','2016-02-22')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets');
    }
}
