<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('planes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre');
            $table->decimal('precio',10,3); // Nota: Tomado decimal por precision de almacenamiento. Puede cambiar a float segun se necesite en calculo de ventas.
            $table->integer('almuerzo')->default(0);
            $table->integer('cena')->default(0);
            $table->integer('snack')->default(0);
            $table->integer('duracion'); // en  cantidad  de dias
            $table->softDeletes();
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
        Schema::drop('planes');
    }
}
