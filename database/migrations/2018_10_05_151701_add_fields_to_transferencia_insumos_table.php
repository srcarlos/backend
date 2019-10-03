<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTransferenciaInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimiento_transferencias', function (Blueprint $table) {
            $table->unsignedInteger('orden_historico_id')->nullable()->after('id');
            $table->string('estatus')->default('pendiente')->after('orden_historico_id');
            $table->foreign('orden_historico_id')
                ->references('id')
                ->on('orden_historicos')
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
        Schema::table('movimiento_transferencias', function (Blueprint $table) {
            $table->dropForeign('movimiento_transferencias_orden_historico_id_foreign');
            $table->dropColumn('orden_historico_id');
            $table->dropColumn('estatus');
        });
    }
}
