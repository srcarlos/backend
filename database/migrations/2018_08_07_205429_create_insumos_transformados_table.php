<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsumosTransformadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_transformados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('equivalencia');
            $table->unsignedInteger('unidad_produccion');
            $table->decimal('costo_unidad_produccion');
            $table->timestamps();

            $table->foreign('unidad_produccion')
                ->references('id')
                ->on('unidad_medidas')
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
        Schema::drop('insumos_transformados');
    }
}


