<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('existencias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('posicion_id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('bodega_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('posicion_id')
                ->references('id')
                ->on('posiciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('existencias');
    }
}
