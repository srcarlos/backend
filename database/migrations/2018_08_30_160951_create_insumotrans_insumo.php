<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsumotransInsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumotrans_insumo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_trans_id');
            $table->unsignedInteger('insumo_id');
            $table->timestamps();

            $table->foreign('insumo_trans_id')
                ->references('id')
                ->on('insumos_transformados')
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
        Schema::drop('insumotrans_insumo');
    }
}
