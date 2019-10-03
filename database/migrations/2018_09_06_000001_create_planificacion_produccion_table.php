<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanificacionProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion_produccion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('planificacion_id');
            $table->date('dia');
            $table->string('turno');
            $table->integer('total_platos');
            $table->integer('status')->default(0); // 
            $table->timestamps();
            $table->foreign('planificacion_id')
                ->references('id')
                ->on('planificaciones')
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
        Schema::drop('planificacion_produccion');
    }
}
