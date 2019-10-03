<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarjetasCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarjetas_creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titular');
            $table->string('tipo_tarjeta');
            $table->string('marca');
            $table->string('banco');
            $table->string('forma_pago');
            $table->decimal('monto',10,2);
            $table->unsignedInteger('factura_id');
            $table->timestamps();

            $table->foreign('factura_id')
                ->references('id')
                ->on('facturas')
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
        Schema::drop('tarjetas_creditos');
    }
}
