<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanificacionProduccionInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion_produccion_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produccion_plato_id');
            $table->unsignedInteger('insumo_id');
            $table->integer('cantidad');

            $table->foreign('produccion_plato_id')
                ->references('id')
                ->on('planificacion_produccion_platos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
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
        Schema::drop('planificacion_produccion_insumos');
    }
}
