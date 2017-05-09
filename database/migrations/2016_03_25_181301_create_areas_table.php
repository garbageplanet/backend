<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned();
            $table->string('title', 12)->unique();
            $table->text('latlngs')->nullable();
            $table->string('contact')->nullable();
            $table->boolean('game')->nullable();
            $table->mediumText('note')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE areas ADD geom geometry(POLYGON,4326)' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('areas');
    }
}
