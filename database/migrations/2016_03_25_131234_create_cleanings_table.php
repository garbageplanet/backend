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
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('modified_by')->unsigned();
            $table->string('lat');
            $table->string('lng');
            $table->string('place');
            $table->string('feature_type');
            $table->mediumText('note');
            $table->string('organizer');
            $table->dateTime('datetime');
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
