<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAmountInLitters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litters', function (Blueprint $table) {
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
        Schema::table('litters', function (Blueprint $table) {
            $table->integer('amount')->nullable()->change();
        });
    }
}
