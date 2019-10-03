<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseInsumosTransformados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_insumos_transformados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumotrans_id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('cantidad');
            $table->timestamps();

            //$table->primary(['insumotrans_id','insumo_id']);

            $table->foreign('insumotrans_id')
                ->references('id')
                ->on('insumos')
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
        Schema::drop('base_insumos_transformados');
    }
}
