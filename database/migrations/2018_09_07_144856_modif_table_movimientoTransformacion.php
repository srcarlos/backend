<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifTableMovimientoTransformacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimiento_transformacion', function (Blueprint $table) {

            $table->dropForeign('movimiento_transformacion_insumo_id_foreign');
            $table->dropColumn('insumo_id');
            $table->unsignedInteger('insumotrans_id')->after('observacion');

            $table->foreign('insumotrans_id')
                ->references('id')
                ->on('insumos_transformados')
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
        Schema::table('movimiento_transformacion', function (Blueprint $table) {

            $table->dropForeign('movimiento_transformacion_insumotrans_id_foreign');
            $table->dropColumn('insumotrans_id');
            $table->unsignedInteger('insumo_id')->after('posicion_id');

            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
