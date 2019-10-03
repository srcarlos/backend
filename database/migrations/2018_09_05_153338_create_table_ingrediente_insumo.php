<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIngredienteInsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_insumo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ingrediente_id');
            $table->unsignedInteger('insumo_id');
            $table->tinyInteger('cantidad');
            $table->timestamps();

            $table->foreign('ingrediente_id')
                ->references('id')
                ->on('ingredientes')
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
        Schema::drop('ingrediente_insumo');
    }
}
