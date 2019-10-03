<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('unidad');
            $table->unsignedInteger('proveedor_id');
            $table->decimal('precio_unitario',10,2);
            $table->tinyInteger('cantidad');
            $table->decimal('total',10,2);
            $table->unsignedInteger('orden_id');
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
            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('orden_id')
                ->references('id')
                ->on('ordenes')
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
        Schema::drop('orden_detalles');
    }
}
