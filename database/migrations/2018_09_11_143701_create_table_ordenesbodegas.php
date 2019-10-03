<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdenesbodegas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_bodegas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');
            $table->unsignedInteger('cocina_id');
            $table->unsignedInteger('bodega_id');
            $table->date('fecha');
            $table->string('estatus')->default('preparada');
            $table->timestamps();

            $table->foreign('cocina_id')
                ->references('id')
                ->on('cocinas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
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
        Schema::drop('ordenes_bodegas');
    }
}
