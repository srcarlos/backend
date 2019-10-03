<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInsumotransformadoProveedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumotransformado_proveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumotrans_id');
            $table->unsignedInteger('proveedor_id');
            $table->decimal('precio');
            $table->timestamps();

            $table->foreign('insumotrans_id')
                ->references('id')
                ->on('insumos_transformados')
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
        Schema::drop('insumotransformado_proveedor');
    }
}
