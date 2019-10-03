<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTurnosPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('turno_id');
            $table->unsignedInteger('plan_id');

            $table->integer('cantidad');
            $table->integer('duracion');
            $table->timestamps();
            $table->foreign('turno_id')
                ->references('id')
                ->on('turnos')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('plan_id')
                ->references('id')
                ->on('planes')
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
        Schema::drop('turnos_plan');
    }
}
