<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldMovTransToExistenciaTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('existencia_trans', function (Blueprint $table) {
            $table->unsignedInteger('movimientotrans_id')->after('unidad');

            $table->foreign('movimientotrans_id')
                ->references('id')
                ->on('movimiento_transformacion')
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
        Schema::table('existencia_trans', function (Blueprint $table) {
            $table->dropForeign('existencia_trans_movimientotrans_id_foreign');
            $table->dropColumn('movimientotrans_id');
        });
    }
}
