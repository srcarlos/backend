<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdenHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_historicos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('planificacion_id');
            $table->unsignedInteger('proveedor_id');
            $table->string('descripcion')->nullable();
            $table->date('fecha');
            $table->string('estatus')->nullable();
            $table->timestamps();

            $table->foreign('planificacion_id')
                ->references('id')
                ->on('planificaciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
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
        Schema::drop('orden_historicos');
    }
}
