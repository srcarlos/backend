<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionPlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones_planes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cotizacion_id');

            $table->unsignedInteger('plan_id');
            $table->smallInteger('cantidad');
            $table->timestamps();
            $table->foreign('plan_id')
                ->references('id')
                ->on('planes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->foreign('cotizacion_id')
            ->references('id')
            ->on('cotizaciones')
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
        Schema::dropIfExists('cotizaciones_planes');
    }
}
