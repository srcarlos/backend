<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->string('tipo');
            $table->unsignedInteger('unidad_compra');
            $table->string('equivalencia');
            $table->unsignedInteger('unidad_produccion');
            $table->unsignedInteger('impuesto_id');
            $table->string('marca');
            $table->decimal('costo_unidad',6,2);
            $table->decimal('costo_unidad_produccion',6,2);
            $table->timestamps();

            $table->foreign("unidad_compra")
                ->references('id')
                ->on('unidad_medidas')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign("unidad_produccion")
                ->references('id')
                ->on('unidad_medidas')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign("impuesto_id")
                ->references('id')
                ->on('impuestos')
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
        Schema::drop('insumos');
    }
}
