<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marked_by')->unsigned();
            $table->foreign('marked_by')->references('id')->on('users');
            $table->string('latlngs');
            $table->integer('amount');
            $table->mediumText('note');
            $table->integer('feature_type');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE litters ADD geom geometry(LINESTRING,4326)' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('litters');
    }
}
