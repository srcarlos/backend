<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSeccionIdToExistencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('existencias', function (Blueprint $table) {
            $table->unsignedInteger('seccion_id')->after('bodega_id');
            $table->unsignedInteger('unidad')->after('cantidad');

            $table->foreign('seccion_id')
                ->references('id')
                ->on('secciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('unidad')
                ->references('id')
                ->on('unidad_medidas')
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
        Schema::table('existencias', function (Blueprint $table) {
            $table->dropForeign('existencias_seccion_id_foreign');
            $table->dropForeign('existencias_unidad_foreign');
            $table->dropColumn('seccion_id');
            $table->dropColumn('unidad');
        });
    }
}
