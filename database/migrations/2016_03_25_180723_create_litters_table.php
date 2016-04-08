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
            $table->string('latlngs')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('feature_type')->nullable();
            $table->mediumText('note')->nullable();
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
