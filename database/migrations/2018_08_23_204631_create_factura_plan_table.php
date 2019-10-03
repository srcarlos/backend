<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factura_id');
            $table->unsignedInteger('plan_id');
            $table->unsignedInteger('cliente_id');
            $table->tinyInteger('cantidad');
            $table->date('fecha_activacion');
            $table->date('fecha_expiracion');
            $table->unsignedInteger('beneficiario');
            $table->date('suspender_desde')->nullable();
            $table->date('suspender_hasta')->nullable();
            $table->boolean('estado')->nullable();
            $table->timestamps();

            $table->foreign('factura_id')
                ->references('id')
                ->on('facturas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('plan_id')
                ->references('id')
                ->on('planes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('beneficiario')
                ->references('id')
                ->on('clientes')
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
        Schema::drop('factura_plan');
    }
}
