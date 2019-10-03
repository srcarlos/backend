<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('desde');
            $table->date('hasta');
            $table->unsignedInteger('cocina_id');
            $table->unsignedInteger('centro_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('centro_id')
                ->references('id')
                ->on('centros_produccion')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('cocina_id')
                ->references('id')
                ->on('cocinas')
                ->onUpdate('cascade')
                ->onDelete('restrict');


            //$table->foreign()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('planificaciones');
    }
}
