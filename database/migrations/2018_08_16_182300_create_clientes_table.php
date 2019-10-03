<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cedula')->unique();
            $table->string('email')->unique();
            $table->string('celular')->unique();
            $table->string('convencional');
            $table->unsignedInteger('pais_id');
            $table->unsignedInteger('provincia_id');
            $table->unsignedInteger('ciudad_id');
            $table->string('calle1');
            $table->string('calle2');
            $table->string('casa_nro');
            $table->unsignedInteger('zona_id');
            $table->string('referencias');
            $table->timestamps();

            $table->foreign('pais_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('provincia_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('ciudad_id')
                ->references('id')
                ->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('zona_id')
                ->references('id')
                ->on('zonas')
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
        Schema::drop('clientes');
    }
}
