<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTEtlDbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_etl_db', function (Blueprint $table) {
            $table->increments('DBid');
            $table->string('DB');
            $table->string('Dbtype');
            $table->string('server')->nullable();
            $table->integer('port')->nullable();
            $table->string('DBname')->nullable();
            $table->string('UserName');
            $table->string('Password')->nullable();
            $table->string('precommand')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_etl_db');
    }
}
