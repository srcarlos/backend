<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoAjustes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_ajustes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cocina_id');
            $table->unsignedInteger('bodega_id');
            $table->unsignedInteger('seccion_id');
            $table->unsignedInteger('posicion_id');
            $table->boolean('accion');
            $table->tinyInteger('tipo');
            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->foreign('cocina_id')
                ->references('id')
                ->on('cocinas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('seccion_id')
                ->references('id')
                ->on('secciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('posicion_id')
                ->references('id')
                ->on('posiciones')
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
        Schema::drop('movimiento_ajustes');
    }
}
