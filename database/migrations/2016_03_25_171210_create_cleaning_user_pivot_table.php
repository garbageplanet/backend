<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCleaningUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleaning_user', function(Blueprint $table) {
            $table->integer('cleaning_id')->unsigned()->index();
            $table->foreign('cleaning_id')->references('id')->on('cleanings')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cleaning_user');
    }
}
