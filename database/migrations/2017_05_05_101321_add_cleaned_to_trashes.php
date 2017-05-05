<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCleanedToTrashes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trashes', function (Blueprint $table) {
            $table->boolean('cleaned')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trashes', function (Blueprint $table) {
            $table->dropColumn('cleaned');
        });
    }
}
