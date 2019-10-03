<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdenIngreso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orden_compra_id');
            $table->unsignedInteger('bodega_id');
            $table->string('estatus')->default('recibido');
            $table->timestamps();

            $table->foreign('orden_compra_id')
                ->references('id')
                ->on('ordenes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
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
        Schema::drop('orden_ingresos');
    }
}
