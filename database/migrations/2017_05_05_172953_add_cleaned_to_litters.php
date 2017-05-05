<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCleanedToLitters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litters', function (Blueprint $table) {
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
        Schema::table('litters', function (Blueprint $table) {
            $table->dropColumn('cleaned');
        });
    }
}
