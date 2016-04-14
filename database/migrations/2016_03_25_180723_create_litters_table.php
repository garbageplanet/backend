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
            $table->text('latlngs')->nullable();
            $table->integer('amount')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('todo')->nullable();
            $table->string('image_url')->nullable();
            $table->string('physical_length')->nullable();
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
