<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEtlLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_etl_log', function (Blueprint $table) {
			//$table->increments('id');
            $table->integer('snapshot_time')->nullable();
            $table->integer('row_count')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('etl_theme');
            $table->string('etl_db')->nullable();
            $table->string('etl_table')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_etl_log');
    }
}
