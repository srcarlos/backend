<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentrosProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros_produccion', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('descripcion');
            $table->string('responsable');
            $table->string('tlf_responsable');
            $table->unsignedInteger('compania_id');
            $table->timestamps();

            $table->foreign('compania_id')
                ->references('id')
                ->on('companias')
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
        Schema::drop('centros_produccion');
    }
}
