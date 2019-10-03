<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre');
            $table->string('razon_social');
            $table->string('direccion');
            $table->string('sitio_web');
            $table->string('telf_particular')->unique();
            $table->string('telf_oficina')->unique();
            $table->string('logo');
            $table->string('rep_legal');
            $table->string('ruc')->unique();
            $table->string('ruc_rep_legal')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companias');
    }
}
