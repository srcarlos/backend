<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoTransformacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_transformacion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bodega_id');
            $table->unsignedInteger('seccion_id');
            $table->unsignedInteger('posicion_id');
            $table->date('fecha');
            $table->string('observacion');
            $table->unsignedInteger('insumo_id');
            $table->tinyInteger('cantidad');
            $table->unsignedInteger('unidad');
            $table->timestamps();

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
            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('unidad')
                ->references('id')
                ->on('unidad_medidas')
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
        Schema::drop('movimiento_transformacion');
    }
}
