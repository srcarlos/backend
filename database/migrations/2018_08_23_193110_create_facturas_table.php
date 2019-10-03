<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('cotizacion_id')->nullable();
            $table->decimal('sub_total',10,2);
            $table->tinyInteger('porcentaje_descuento');
            $table->decimal('descuento_total',10,2);
            $table->decimal('total',10,2);
            $table->unsignedTinyInteger('iva');
            $table->string('comprobante')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cotizacion_id')
                ->references('id')
                ->on('cotizaciones')
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
        Schema::drop('facturas');
    }
}
