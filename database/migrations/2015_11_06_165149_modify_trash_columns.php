<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTrashColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trashes', function ($table) {
            $table->dropColumn('status');
            $table->dropColumn('marked_at');
            $table->dropColumn('cleaned_at');
            $table->string('image_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trashes', function ($table) {
            $table->integer('marked_by');
            $table->string('status'); //removed
            $table->dateTime('cleaned_at'); //removed
            $table->dropColumn('image_url');
        });
    }
}
