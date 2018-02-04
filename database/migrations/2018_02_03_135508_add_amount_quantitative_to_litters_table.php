<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountQuantitativeToLittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('litters', function (Blueprint $table) {
          $table->string('amount_quantitative')->unique()->nullable();
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
          $table->dropColumn('amount_quantitative');
      });
    }
}
