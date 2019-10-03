<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('prospecto'); // hace referencia a la tabla clientes
            $table->unsignedInteger('compania_id'); // hace referencia a la tabla companias
            $table->date('fecha_activacion');
            $table->date('fecha_expiracion');
            $table->date('fecha_email');
            $table->boolean('email_enviado');
            $table->decimal('descuento_porcentaje',9,3); // porcentaje
            $table->decimal('descuento_total',9,3); // descuento del total. 
            $table->decimal('sub_total',9,3);
            $table->decimal('total',9,3);

            // Datos para la zona de entrega de la cotizacion
            $table->unsignedInteger('pais_id');
            $table->unsignedInteger('provincia_id');
            $table->unsignedInteger('ciudad_id');
            $table->string('calle1');
            $table->string('calle2');
            $table->string('casa_nro');
            $table->unsignedInteger('zona_id');
            $table->string('referencias');
            $table->timestamps();

            $table->foreign('prospecto')
            ->references('id')
            ->on('clientes')
            ->onUpdate('cascade')
            ->onDelete('cascade');


             $table->foreign('compania_id')
            ->references('id')
            ->on('companias')
            ->onUpdate('cascade')
            ->onDelete('cascade');


            
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
        Schema::dropIfExists('cotizaciones');
    }
}
