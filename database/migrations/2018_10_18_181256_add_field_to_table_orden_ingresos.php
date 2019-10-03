<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToTableOrdenIngresos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_ingresos', function (Blueprint $table) {
            $table->unsignedInteger('orden_produccion_id')->nullable()->after('orden_compra_id');

            $table->foreign('orden_produccion_id')
                ->references('id')
                ->on('planificacion_produccion')
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
        Schema::table('orden_ingresos', function (Blueprint $table) {
            $table->dropForeign('orden_ingresos_orden_produccion_id_foreign');
            $table->dropColumn('orden_produccion_id');
        });
    }
}
