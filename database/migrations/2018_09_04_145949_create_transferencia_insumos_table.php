<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciaInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencia_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bodega_sal');
            $table->unsignedInteger('seccion_sal');
            $table->unsignedInteger('posicion_sal');
            $table->unsignedInteger('insumo_sal');
            $table->tinyInteger('cantidad_sal');
            $table->unsignedInteger('unidad');

            $table->unsignedInteger('bodega_ent');
            $table->unsignedInteger('seccion_ent');
            $table->unsignedInteger('posicion_ent');
            $table->unsignedInteger('movimientotransf_id');

            $table->timestamps();

            $table->foreign('bodega_sal')
                ->references('id')
                ->on('bodegas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('seccion_sal')
                ->references('id')
                ->on('secciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('posicion_sal')
                ->references('id')
                ->on('posiciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('insumo_sal')
                ->references('id')
                ->on('insumos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('unidad')
                ->references('id')
                ->on('unidad_medidas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('bodega_ent')
                ->references('id')
                ->on('bodegas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('seccion_ent')
                ->references('id')
                ->on('secciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('posicion_ent')
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
        Schema::drop('transferencia_insumos');
    }
}
