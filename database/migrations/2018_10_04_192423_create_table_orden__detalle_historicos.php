<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdenDetalleHistoricos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_detalle_historicos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('unidad');
            $table->decimal('precio_unitario',10,2);
            $table->tinyInteger('cantidad');
            $table->decimal('total',10,2);
            $table->string('estatus')->default('por comprar');
            $table->unsignedInteger('orden_historico_id');
            $table->timestamps();

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
            $table->foreign('orden_historico_id')
                ->references('id')
                ->on('orden_historicos')
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
        Schema::drop('orden_detalle_historicos');
    }
}
