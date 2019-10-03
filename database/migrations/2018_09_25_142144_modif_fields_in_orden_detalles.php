<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifFieldsInOrdenDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_detalles', function (Blueprint $table) {
            $table->dropForeign('orden_detalles_proveedor_id_foreign');
            $table->dropColumn('proveedor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_detalles', function (Blueprint $table) {
            $table->unsignedInteger('proveedor_id');

            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
