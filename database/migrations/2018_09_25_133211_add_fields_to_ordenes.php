<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOrdenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->unsignedInteger('planificacion_id')->nullable()->after('id');
            $table->string('descripcion')->nullable()->change();
            $table->unsignedInteger('proveedor_id')->after('planificacion_id');
            $table->string('estatus',50)->default('borrador')->change();

            $table->foreign('planificacion_id')
                ->references('id')
                ->on('planificaciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
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
            $table->dropForeign('ordenes_proveedor_id_foreign');
            $table->dropForeign('ordenes_planificacion_id_foreign');
            $table->dropColumn(['planificacion_id','proveedor_id']);
            $table->string('descripcion')->after('id');
            $table->date('fecha')->after('descripcion');
        });
    }
}

