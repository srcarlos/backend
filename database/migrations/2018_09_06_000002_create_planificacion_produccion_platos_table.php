<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanificacionProduccionPlatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion_produccion_platos', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('production_id');
            $table->unsignedInteger('platos_id');
            $table->integer('cantidad');

            $table->foreign('production_id')
                ->references('id')
                ->on('planificacion_produccion')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('platos_id')
                ->references('id')
                ->on('platos')
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
        Schema::drop('planificacion_produccion_platos');
    }
}
