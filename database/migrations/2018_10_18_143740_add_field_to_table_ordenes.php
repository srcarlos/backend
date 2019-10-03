<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToTableOrdenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->unsignedInteger('orden_produccion_id')->nullable()->after('planificacion_id');

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
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropForeign('ordenes_orden_produccion_id_foreign');
            $table->dropColumn('orden_produccion_id');
        });
    }
}
