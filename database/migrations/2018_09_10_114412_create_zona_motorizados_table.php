<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZonaMotorizadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zona_motorizados', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('zona_id')->unsigned()->index();
            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('cascade');
            $table->integer('motorizado_id')->unsigned()->index();
            $table->foreign('motorizado_id')->references('id')->on('motorizados')->onDelete('cascade');
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
        Schema::drop('zona_motorizados');
    }
}
