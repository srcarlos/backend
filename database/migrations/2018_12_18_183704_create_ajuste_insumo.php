<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjusteInsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajuste_insumo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ajuste_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('cantidad',8,2);
            $table->timestamps();

            $table->foreign('ajuste_id')
                ->references('id')
                ->on('movimiento_ajustes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
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
        Schema::drop('ajuste_insumo');
    }
}
