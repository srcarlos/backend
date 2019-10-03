<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaMetodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_metodo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factura_id');
            $table->unsignedInteger('metodo_pago_id');
            $table->timestamps();

            $table->foreign('factura_id')
                ->references('id')
                ->on('facturas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('metodo_pago_id')
                ->references('id')
                ->on('metodos_pagos')
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
        Schema::drop('factura_metodo');
    }
}
