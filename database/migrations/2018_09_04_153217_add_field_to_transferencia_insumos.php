<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToTransferenciaInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transferencia_insumos', function (Blueprint $table) {

            $table->foreign('movimientotransf_id')
                ->references('id')
                ->on('movimiento_transferencias')
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
        Schema::table('transferencia_insumos', function (Blueprint $table) {
            $table->dropForeign('transferencia_insumos_movimientotransf_id_foreign');
        });
    }
}
