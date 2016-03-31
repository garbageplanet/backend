<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trashes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marked_by')->unsigned();
            $table->foreign('marked_by')->references('id')->on('users');
            $table->string('lat');
            $table->string('lng');
            $table->integer('amount');
            $table->mediumText('note');
            $table->string('feature_type');
            $table->string('todo');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE trashes ADD geom geometry(POINT,4326)' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trashes');
    }
}
