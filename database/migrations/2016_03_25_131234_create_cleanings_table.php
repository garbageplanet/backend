<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCleaningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleanings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned();
            $table->integer('modified_by')->unsigned()->nullable();
            $table->string('latlng')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->string('recurrence')->nullable();
            $table->mediumText('note')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE cleanings ADD geom geometry(POINT,4326)' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cleanings');
    }
}
