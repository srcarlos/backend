<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdenIngresoDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_ingreso_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orden_ingreso_id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('unidad');
            $table->decimal('precio_unitario');
            $table->smallInteger('cantidad');
            $table->decimal('total',8,2);
            $table->smallInteger('cantidad_ingresada');
            $table->smallInteger('cantidad_recibida')->nullable();
            $table->unsignedInteger('seccion_id')->nullable();
            $table->unsignedInteger('posicion_id')->nullable();
            $table->timestamps();

            $table->foreign('orden_ingreso_id')
                ->references('id')
                ->on('orden_ingresos')
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
        Schema::drop('orden_ingreso_detalles');
    }
}
