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
            $table->integer('marked_by')->unsigned()->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('amount')->nullable();
            $table->string('image_url')->nullable();
            $table->string('feature_type')->nullable();
            $table->mediumText('note')->nullable();
            $table->integer('todo')->nullable();
            $table->integer('sizes')->nullable();
            $table->integer('embed')->nullable();
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
