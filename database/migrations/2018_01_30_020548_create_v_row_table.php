<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_row', function (Blueprint $table) {
            //$table->increments('id');
            $table->string('etl_theme');
            $table->string('etl_db')->nullable();
            $table->string('etl_table')->nullable();
            $table->bigInteger('N')->nullable();
            $table->bigInteger('N_minus1')->nullable();
            $table->bigInteger('N_minus2')->nullable();
            $table->bigInteger('N_minus3')->nullable();
            $table->bigInteger('N_minus4')->nullable();
            $table->bigInteger('N_minus5')->nullable();
            $table->bigInteger('N_minus6')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_row');
    }
}
