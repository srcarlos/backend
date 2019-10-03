<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMovimientoReservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observacion')->default('reserva stock');
            $table->date('fecha');
            $table->unsignedInteger('centro_id');
            $table->unsignedInteger('cocina_id');
            $table->unsignedInteger('production_id');

            $table->timestamps();

            $table->foreign('centro_id')
            ->references('id')
            ->on('centros_produccion')
            ->onUpdate('cascade')
            ->onDelete('restrict');


            $table->foreign('cocina_id')
            ->references('id')
            ->on('cocinas')
            ->onUpdate('cascade')
            ->onDelete('restrict');



            $table->foreign('production_id')
            ->references('id')
            ->on('planificacion_produccion')
            ->onUpdate('cascade')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimiento_reservas');
    }
}
