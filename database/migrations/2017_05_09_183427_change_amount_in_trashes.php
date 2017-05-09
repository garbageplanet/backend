<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAmountInTrashes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trashes', function (Blueprint $table) {
            $table->integer('amount')->default(3)->change();
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
            $table->integer('amount')->nullable()->change();
        });
    }
}
